<?php

namespace App\Models;

class ImageModel
{
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];
    private const MAX_SIZE = 2 * 1024 * 1024; // 2MB
    private const MAX_DIMENSION = 1500;
    
    public function validateImage($file): array
    {
        $errors = [];
        
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'No file was uploaded or an error occurred during upload.';
            return $errors;
        }

        // Check file size
        if ($file['size'] > self::MAX_SIZE) {
            $errors[] = 'File is too large. Maximum size is 2MB.';
        }

        // Check file type
        if (!in_array($file['type'], self::ALLOWED_TYPES)) {
            $errors[] = 'Invalid file type. Only JPG and PNG files are allowed.';
        }

        // Additional security checks
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            $errors[] = 'File type verification failed.';
        }

        return $errors;
    }

    public function processAvatar($file, $userId): ?string
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Create avatars directory if it doesn't exist
        $uploadDir = 'uploads/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $userId . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return null;
        }

        // Resize and compress image
        $this->resizeAndCompress($filepath);

        return $filepath;
    }
    
    /**
     * Process product images with improved validation
     * 
     * @param array $files Array of $_FILES for product images
     * @param bool $requirePrimary Whether primary image is required
     * @return array|false Array of processed images or array with errors
     */
    public function processProductImages(array $files, bool $requirePrimary = true): array|false
    {
        $images = [];
        $errors = [];
        $fieldErrors = [];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Ensure uploads directory exists
        if (!file_exists(UPLOADS_DIR)) {
            mkdir(UPLOADS_DIR, 0777, true);
        }
        
        // Check if primary image is provided when required
        if ($requirePrimary && 
            (!isset($files['product_primary_image']) || 
             $files['product_primary_image']['error'] === UPLOAD_ERR_NO_FILE)) {
            $fieldErrors['product_primary_image'] = 'Primary image is required.';
        }
        
        foreach ($files as $key => $file) {
            // Skip empty files
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            
            // Validate file
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errorMsg = $this->getFileUploadErrorMessage($file['error'], $key);
                $errors[] = $errorMsg;
                $fieldErrors[$key] = $errorMsg;
                continue;
            }
            
            // Validate file size
            if ($file['size'] > $maxSize) {
                $errorMsg = "File '{$file['name']}' is too large. Maximum size is 5MB.";
                $errors[] = $errorMsg;
                $fieldErrors[$key] = 'File is too large. Maximum size is 5MB.';
                continue;
            }
            
            // Validate mime type
            if (!in_array($file['type'], self::ALLOWED_TYPES)) {
                $errorMsg = "File '{$file['name']}' has invalid type. Only JPG and PNG files are allowed.";
                $errors[] = $errorMsg;
                $fieldErrors[$key] = 'Only JPG and PNG files are allowed.';
                continue;
            }
            
            // Additional security check for file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, self::ALLOWED_TYPES)) {
                $errorMsg = "File '{$file['name']}' failed type verification.";
                $errors[] = $errorMsg;
                $fieldErrors[$key] = 'File type verification failed.';
                continue;
            }
            
            // Process file
            $filename = $this->sanitizeFilename(pathinfo($file['name'], PATHINFO_FILENAME)) . '_' . 
                        $this->generate_filename(10) . '.' . 
                        pathinfo($file['name'], PATHINFO_EXTENSION);
            $destination = UPLOADS_DIR . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $images[$key] = $filename;
                $this->resize_image($destination, $destination, 1500, 1500);
            } else {
                $errorMsg = "Failed to upload file '{$file['name']}'. Please try again.";
                $errors[] = $errorMsg;
                $fieldErrors[$key] = 'Failed to upload file. Please try again.';
            }
        }
        
        if (!empty($errors)) {
            return [
                'errors' => $errors,
                'fieldErrors' => $fieldErrors
            ];
        }
        
        return $images;
    }
    
    /**
     * Get human-readable file upload error message
     */
    private function getFileUploadErrorMessage(int $errorCode, string $fieldName): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file for '{$fieldName}' exceeds the upload_max_filesize directive in php.ini.";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file for '{$fieldName}' exceeds the MAX_FILE_SIZE directive in the HTML form.";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file for '{$fieldName}' was only partially uploaded.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder for '{$fieldName}'.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file for '{$fieldName}' to disk.";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload for '{$fieldName}'.";
            default:
                return "Unknown upload error for '{$fieldName}'.";
        }
    }
    
    /**
     * Sanitize a filename to prevent security issues
     */
    private function sanitizeFilename(string $filename): string
    {
        $filename = preg_replace('/[^\w\-\.]/', '', $filename); // Remove invalid chars
        $filename = preg_replace('/\.\.+/', '.', $filename); // Prevent directory traversal
        return trim($filename);
    }

    private function resizeAndCompress($filepath): void
    {
        list($width, $height) = getimagesize($filepath);
        
        // Calculate new dimensions while maintaining aspect ratio
        $ratio = min(self::MAX_DIMENSION / $width, self::MAX_DIMENSION / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
        
        // Create new image
        $srcImage = $this->createImageFromFile($filepath);
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG images
        if (pathinfo($filepath, PATHINFO_EXTENSION) === 'png') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
        }
        
        // Resize
        imagecopyresampled(
            $dstImage, $srcImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height
        );
        
        // Save with compression
        $this->saveImage($dstImage, $filepath);
        
        // Clean up
        imagedestroy($srcImage);
        imagedestroy($dstImage);
    }

    private function createImageFromFile($filepath)
    {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($filepath);
            case 'png':
                return imagecreatefrompng($filepath);
            default:
                throw new \Exception('Unsupported image type');
        }
    }

    private function saveImage($image, $filepath)
    {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $filepath, 85); // 85% quality
                break;
            case 'png':
                imagepng($image, $filepath, 8); // Compression level 8 (0-9)
                break;
        }
    }

    public function deleteAvatar($filepath): bool
    {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }

    // Generate a random filename
    public function generate_filename(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

    // Resize an image
    public function resize_image($source, $destination, $width, $height): bool
    {
        list($orig_width, $orig_height) = getimagesize($source);
        
        // Calculate new dimensions while maintaining aspect ratio
        $ratio = min($width / $orig_width, $height / $orig_height);
        $new_width = $orig_width * $ratio;
        $new_height = $orig_height * $ratio;
        
        // Create new image
        $src_image = $this->createImageFromFile($source);
        $dst_image = imagecreatetruecolor($new_width, $new_height);
        
        // Preserve transparency for PNG images
        if (pathinfo($source, PATHINFO_EXTENSION) === 'png') {
            imagealphablending($dst_image, false);
            imagesavealpha($dst_image, true);
        }
        
        // Resize
        imagecopyresampled(
            $dst_image, $src_image,
            0, 0, 0, 0,
            $new_width, $new_height,
            $orig_width, $orig_height
        );
        
        // Save with compression
        $this->saveImage($dst_image, $destination);
        
        // Clean up
        imagedestroy($src_image);
        imagedestroy($dst_image);
        
        return true;
    }
} 