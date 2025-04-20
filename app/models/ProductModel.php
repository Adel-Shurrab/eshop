<?php

namespace App\Models;

use App\Core\Database;
use App\Models\CategoryModel;
use App\Models\ImageModel;
use Exception;

class ProductModel
{

    public $msg = '';
    public $fieldErrors = [];
    private const ERROR_QUERY_FAILURE = 'Something went wrong. Please try again later.';
    private const ERROR_DUPLICATE_PRODUCT = 'Product already exists!<br>';
    private const ERROR_NO_PRODUCT = 'No product provided.<br>';
    private const ERROR_NO_PRIMARY_IMAGE = 'Primary image is required.';
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];
    private const MAX_IMAGE_SIZE_MB = 5;


    // Create a new product
    public function createProduct(object $data, array $files): bool
    {
        $db = Database::getInstance();

        // Validate and sanitize input
        if ($this->isEmptyProductData($data)) {
            $this->setErrorMessage(self::ERROR_NO_PRODUCT);
            return false;
        }

        $description = $this->sanitizeInput($data->description);
        $slag = $this->generateSlag($description);
        $category = $this->sanitizeInput($data->category);
        $price = (float)$this->sanitizeInput($data->price);
        $quantity = (int)$this->sanitizeInput($data->quantity);
        $date = date('Y-m-d H:i:s');

        // Handle file uploads
        $imagesResult = $this->handleFileUploads($files);
        if (isset($imagesResult['success']) && $imagesResult['success'] === false) {
            // Return validation errors for frontend display
            return false;
        }

        $images = $imagesResult;

        // Validate product data
        if (!$this->isValidProduct($description, $category, $price, $quantity)) {
            return false;
        }

        // Check for duplicate product
        if (!$this->isUniqueProduct($description)) {
            $this->setErrorMessage(self::ERROR_DUPLICATE_PRODUCT);
            return false;
        }

        // Insert into database
        return $this->insertProduct($db, $description, $slag, $category, $price, $quantity, $images, $date);
    }

    private function insertProduct($db, $description, $slag, $category, $price, $quantity, $images, $date): bool
    {
        $saveQuery = "INSERT INTO products (description, slag, category_id, price, quantity, image, image2, image3, image4, date) VALUES (:description, :slag, :category, :price, :quantity, :img1, :img2, :img3, :img4, :date)";
        $arr = [
            'description' => ucwords($description),
            'slag' => $slag,
            'category' => $category,
            'price' => $price,
            'quantity' => $quantity,
            'img1' => $images['product_primary_image'],
            'img2' => $images['img2'] ?? '',
            'img3' => $images['img3'] ?? '',
            'img4' => $images['img4'] ?? '',
            'date' => $date
        ];
        try {
            $result = $db->write($saveQuery, $arr);

            if ($result) {
                $this->setSuccessMessage('Added successfully!');
                return true;
            } else {
                throw new Exception("Database write operation failed.");
            }
        } catch (Exception $e) {
            $this->logError($e);
            $this->setErrorMessage(self::ERROR_QUERY_FAILURE);
            return false;
        }
    }

    public function editProduct(int $id, object $data, array $files): bool
    {
        $db = Database::getInstance();

        // Validate and sanitize input
        if ($this->isEmptyProductData($data)) {
            $this->setErrorMessage(self::ERROR_NO_PRODUCT);
            return false;
        }

        $description = $this->sanitizeInput($data->description);
        $slag = $this->generateSlag($description);
        $category = $this->sanitizeInput($data->category);
        $price = (float)$this->sanitizeInput($data->price);
        $quantity = (int)$this->sanitizeInput($data->quantity);

        // Get product data
        $product = $this->getOneProduct($id);
        if (empty($product)) {
            $this->setErrorMessage('Product not found.');
            return false;
        }

        // Validate product data
        if (!$this->isValidProduct($description, $category, $price, $quantity)) {
            return false;
        }

        // Check for duplicate product only if the description has changed
        if ($description !== $product['description'] && !$this->isUniqueProduct($description)) {
            $this->setErrorMessage(self::ERROR_DUPLICATE_PRODUCT);
            return false;
        }

        // Handle file uploads - don't require primary image on edit if we already have one
        $requirePrimary = empty($product['image']);
        $imagesResult = $this->handleFileUploads($files, $requirePrimary);
        if (isset($imagesResult['success']) && $imagesResult['success'] === false) {
            // Return validation errors for frontend display
            return false;
        }

        $images = $imagesResult;

        // Delete old images if new ones are provided
        $this->deleteOldImages($product, $images);

        // Use existing images if not provided
        $this->useExistingImages($product, $images);

        // Update product data
        return $this->updateProduct($db, $id, $description, $slag, $category, $price, $quantity, $images);
    }

    private function updateProduct($db, $id, $description, $slag, $category, $price, $quantity, $images): bool
    {
        $updateQuery = "UPDATE products SET description = :description, slag = :slag, category_id = :category, price = :price, quantity = :quantity, image = :img1, image2 = :img2, image3 = :img3, image4 = :img4 WHERE id = :id";
        $arr = [
            'description' => ucwords($description),
            'slag'        => $slag,
            'category'    => $category,
            'price'       => $price,
            'quantity'    => $quantity,
            'img1'        => $images['product_primary_image'],
            'img2'        => $images['img2'] ?? '',
            'img3'        => $images['img3'] ?? '',
            'img4'        => $images['img4'] ?? '',
            'id'          => $id
        ];
        try {
            $result = $db->write($updateQuery, $arr);

            if ($result) {
                $this->setSuccessMessage('Updated successfully!');
                return true;
            } else {
                throw new Exception("Database update operation failed.");
            }
        } catch (Exception $e) {
            $this->logError($e);
            $this->setErrorMessage(self::ERROR_QUERY_FAILURE);
            return false;
        }
    }

    private function deleteOldImages(array $product, array $images): void
    {
        if (!empty($images['product_primary_image']) && !empty($product['image'])) {
            $this->deleteImage($product['image']);
        }
        if (!empty($images['img2']) && !empty($product['image2'])) {
            $this->deleteImage($product['image2']);
        }
        if (!empty($images['img3']) && !empty($product['image3'])) {
            $this->deleteImage($product['image3']);
        }
        if (!empty($images['img4']) && !empty($product['image4'])) {
            $this->deleteImage($product['image4']);
        }
    }

    private function useExistingImages(array $product, array &$images): void
    {
        if (empty($images['product_primary_image'])) {
            $images['product_primary_image'] = $product['image'];
        }
        if (empty($images['img2'])) {
            $images['img2'] = $product['image2'];
        }
        if (empty($images['img3'])) {
            $images['img3'] = $product['image3'];
        }
        if (empty($images['img4'])) {
            $images['img4'] = $product['image4'];
        }
    }

    // Delete a single image
    private function deleteImage(string $imagePath): void
    {
        $fullPath = UPLOADS_DIR . $imagePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    // Handle file uploads
    private function handleFileUploads(array $files, bool $requirePrimary = true): array|bool
    {
        $imageModel = new ImageModel();
        $result = $imageModel->processProductImages($files, $requirePrimary);

        if (isset($result['errors'])) {
            // Set overall error message
            $this->setErrorMessage(implode("<br>", $result['errors']));

            // Store field errors for controller access
            $this->fieldErrors = $result['fieldErrors'];

            // Return both normal errors and field-specific errors
            return [
                'success' => false,
                'fieldErrors' => $result['fieldErrors']
            ];
        }

        return $result;
    }

    // Delete product images
    private function deleteProductImages(array $product): void
    {
        $images = ['image', 'image2', 'image3', 'image4'];
        foreach ($images as $image) {
            if (!empty($product[$image]) && file_exists(UPLOADS_DIR . $product[$image])) {
                unlink(UPLOADS_DIR . $product[$image]);
            }
        }
    }

    // Delete a product
    public function deleteProduct(int $id): bool
    {
        $db = Database::getInstance();
        $deleteQuery = "UPDATE products SET is_deleted = 1, deleted_at = NOW() WHERE id = :id";
        $product = $this->getOneProduct($id);

        if (!$product) {
            $this->setErrorMessage('Product not found.');
            return false;
        }

        // Begin transaction
        $db->beginTransaction();
        try {
            $result = $db->write($deleteQuery, ['id' => $id]);

            if (!$result) {
                throw new Exception("Database delete operation failed.");
            }

            // Commit transaction
            $db->commit();

            $this->setSuccessMessage('Deleted successfully!');
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();
            $this->logError($e);
            $this->setErrorMessage(self::ERROR_QUERY_FAILURE);
            return false;
        }
    }

    // Restore a product
    public function restoreProduct(int $id): bool
    {
        $db = Database::getInstance();
        $product = $this->getProductById($id);

        if (empty($product)) {
            $this->setErrorMessage('Product does not exist.');
            return false;
        }

        // Begin transaction
        $db->beginTransaction();
        try {
            $restoreQuery = "UPDATE products SET is_deleted = 0, deleted_at = null WHERE id = :id";
            $db->write($restoreQuery, ['id' => $id]);

            // Commit transaction
            $db->commit();

            $this->setSuccessMessage('Product restored successfully!');
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();
            $this->logError($e);
            $this->setErrorMessage(self::ERROR_QUERY_FAILURE);
            return false;
        }
    }

    // Permanently delete a product
    public function deletePermanentProduct(int $id): bool
    {
        $db = Database::getInstance();
        $deleteQuery = "DELETE FROM products WHERE id = :id";
        $product = $this->getProductById($id);

        // Begin transaction
        $db->beginTransaction();
        try {
            $db->write($deleteQuery, ['id' => $id]);

            // Delete associated images
            $this->deleteProductImages($product);

            // Commit transaction
            $db->commit();

            $this->setSuccessMessage('Product permanently deleted!');
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();
            $this->logError($e);
            $this->setErrorMessage(self::ERROR_QUERY_FAILURE);
            return false;
        }
    }

    // Check if trash is empty
    public function checkTrash(): bool
    {
        $db = Database::getInstance();
        try {
            $query = "SELECT COUNT(*) AS count FROM products WHERE is_deleted = 1";
            $result = $db->read($query);

            if (!$result) {
                $this->setErrorMessage('Error checking trash.');
                return false;
            }

            if ($result[0]['count'] == 0) {
                $this->setInfoMessage('Trash is empty');
                return false;
            } else {
                $this->setSuccessMessage('Retrieved deleted products.');
                return true;
            }
        } catch (Exception $e) {
            $this->logError($e);
            $this->setErrorMessage('Database error while checking trash.');
            return false;
        }
    }

    // Get all products
    public function getProducts(): array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM products WHERE is_deleted = 0";
        $result = $db->read($query);
        if ($result) {
            return $result;
        } else {
            error_log("No products found.");
            return [];
        }
    }

    // Get a single product by ID
    public function getOneProduct(int $id): array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM products WHERE id = :id AND is_deleted = 0";
        $result = $db->read($query, ['id' => $id]);
        return $result ? $result[0] : [];
    }

    // Get a single product by ID regardless of deleted status
    public function getProductById(int $id): array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM products WHERE id = :id";
        $result = $db->read($query, ['id' => $id]);
        return $result ? $result[0] : [];
    }

    // Validate product data
    private function isValidProduct(string $description, string $category, float $price, int $quantity): bool
    {
        if (!preg_match("/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9\s\.,'\"'\-%\/:&|()]{10,255}$/u", $description)) {
            $this->msg .= 'Invalid description. It must be between 10 and 255 characters long and only contain letters, numbers, spaces, and specific characters (.,\'"-/:).';
            return false;
        }

        if (empty($category)) {
            $this->msg .= 'Category cannot be empty.';
            return false;
        }

        $categoryModel = new CategoryModel();
        $cat_id = $categoryModel->getCategoryId($category);
        if ($this->hasSubCategories($cat_id)) {
            $this->msg .= 'Category cannot be a parent category.';
            return false;
        }

        $db = Database::getInstance();
        $query = "SELECT COUNT(*) AS count FROM categories WHERE id = :id";
        $cat = $db->read($query, ['id' => $category]);

        if (!$cat || $cat[0]['count'] == 0) {
            $this->msg .= 'Category does not exist.';
            return false;
        }

        if ($price <= 0) {
            $this->msg .= 'Invalid price. Price must be a positive number.';
            return false;
        }

        if ($quantity < 0 || intval($quantity) != $quantity) {
            $this->msg .= 'Invalid quantity. Quantity must be a non-negative integer.';
            return false;
        }

        return true;
    }

    // Sanitize filename
    private function sanitizeFilename(string $filename): string
    {
        $filename = preg_replace('/[^\w\-\.]/', '', $filename); // Remove invalid chars
        $filename = preg_replace('/\.\.+/', '.', $filename); // Prevent directory traversal
        return trim($filename);
    }

    // Check if product is unique
    private function isUniqueProduct(string $description): bool
    {
        $db = Database::getInstance();
        $checkQuery = "SELECT COUNT(*) AS count FROM products WHERE description = :description";
        $result = $db->read($checkQuery, ['description' => $description]);
        return $result && $result[0]['count'] == 0;
    }

    // Sanitize input
    private function sanitizeInput(string $input): string
    {
        $input = htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        $input = htmlspecialchars_decode($input, ENT_QUOTES);
        return $input;
    }

    // Get all enabled categories
    public function getEnabledCategories(): array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM categories WHERE disabled = 1 AND is_deleted = 0 ORDER BY id DESC";
        return $db->read($query) ?? [];
    }

    // Check if product data is empty
    private function isEmptyProductData(object $data): bool
    {
        return empty($data->description) && empty($data->category) && empty($data->price) && empty($data->quantity);
    }

    // Generate slag from description
    private function generateSlag(string $description): string
    {
        $slag = strtolower($description);
        $slag = preg_replace('/[^a-z0-9\s-]/', '', $slag);
        $slag = preg_replace('/[\s-]+/', ' ', $slag);
        $slag = preg_replace('/\s/', '-', $slag);
        $slag = trim($slag, '-');
        return $slag;
    }

    // Get deleted products
    public function getDeletedProducts(): array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM products WHERE is_deleted = 1";
        return $db->read($query);
    }

    // Generate HTML table for products
    public function makeTable($products): string
    {
        $html = '';
        $categoryModel = new CategoryModel();

        foreach ($products as $product) {
            $description = htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');
            $truncatedDescription = strlen($description) > 50 ? substr($description, 0, 45) . '...' : $description;
            $category = $categoryModel->getOneCategory($product['category_id']);
            $categoryName = $category['category'] ?? 'Unknown';
            $deletedAt = $product['deleted_at'] ? date('d/m/Y', strtotime($product['deleted_at'])) : 'N/A';
            $imagePath = UPLOADS_URL . $product['image'];

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= "<td><a href=\"#\" title=\"{$description}\">{$truncatedDescription}</a></td>";
            $html .= '<td>' . htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>$' . number_format((float)$product['price'], 2) . '</td>';
            $html .= '<td>' . (int)$product['quantity'] . ' stock</td>';
            $html .= '<td>';
            $html .= "<img src=\"{$imagePath}\" style=\"width: 60px; height: auto;\">";
            $html .= '</td>';
            $html .= '<td>' . htmlspecialchars(date('d/m/Y', strtotime($product['date'])), ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . htmlspecialchars($deletedAt, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>';
            if ($product['is_deleted'] == 1) {
                $html .= '<button class="btn btn-success btn-xs btn-action" style="margin-right: 3px;" title="Restore" onclick="restoreProduct(' . $product['id'] . ')"><i class="fa fa-undo"></i></button>';
                $html .= '<button class="btn btn-danger btn-xs btn-action" style="margin-right: 3px;" title="Permanent Delete" onclick="deletePermanentProduct(' . $product['id'] . ')"><i class="fa fa-trash-o"></i></button>';
            } else {
                $html .= '<button class="btn btn-primary btn-xs btn-action" style="margin-right: 3px;" title="Edit" onclick="showEdit(' . htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') . ', \'' . addslashes($description) . '\', \'' . htmlspecialchars($product['category_id'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($product['quantity'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($product['image2'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($product['image3'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($product['image4'], ENT_QUOTES, 'UTF-8') . '\')"><i class="fa fa-pencil"></i></button>';
                $html .= '<button class="btn btn-danger btn-xs btn-action" title="Delete" onclick="deleteProduct(' . htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') . ')"><i class="fa fa-trash-o"></i></button>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }

    // Generate parent options for categories
    public function makeParentOptions($categories, $selectedId = null): string
    {
        $html = '<option value="" disabled selected>Select category</option>';
        foreach ($categories as $category) {
            if ($category['parent'] == 0) {
                $html .= $this->buildParentOption($category, $selectedId);
                $html .= $this->buildSubParentOptions($category['id'], $categories, 1, $selectedId);
            }
        }
        return $html;
    }

    // Build parent option for category
    private function buildParentOption(array $category, ?int $selectedId, int $indentLevel = 0): string
    {
        $indent = str_repeat('&nbsp;', $indentLevel * 5);
        $selected = $category['id'] == $selectedId ? 'selected' : '';
        $disabled = $this->hasSubCategories($category['id']) ? 'disabled' : '';
        return '<option value="' . htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') . '" ' . $selected  . '>' . $indent . htmlspecialchars($category['category'], ENT_QUOTES, 'UTF-8') . '</option>';
    }

    private function buildSubParentOptions($parentId, $categories, $indentLevel, $selectedId)
    {
        $html = '';
        foreach ($categories as $category) {
            if ($category['parent'] == $parentId) {
                $html .= $this->buildParentOption($category, $selectedId, $indentLevel);
                $html .= $this->buildSubParentOptions($category['id'], $categories, $indentLevel + 1, $selectedId);
            }
        }
        return $html;
    }

    private function hasSubCategories($id)
    {
        $db = Database::getInstance();
        $query = "SELECT COUNT(*) AS count FROM categories WHERE parent = :id";
        $result = $db->read($query, ['id' => $id]);
        if ($result && $result[0]['count'] > 0) {
            return true;
        }
        return false;
    }

    private function setInfoMessage(string $message): void
    {
        $this->msg = $message;
    }

    // Log error
    private function logError(Exception $e): void
    {
        error_log($e->getMessage());
    }

    private function setSuccessMessage(string $message): void
    {
        $this->msg = $message;
    }

    private function setErrorMessage(string $message): void
    {
        $this->msg = $message;
    }

    /**
     * Validate product data and return field-specific errors
     * 
     * @param object $data Product data to validate
     * @param bool $isNewProduct Whether this is a new product or an existing one
     * @return array Array of validation errors by field
     */
    public function validateProductData(object $data, bool $isNewProduct = true): array
    {
        $errors = [];

        // Validate description
        if (empty($data->description)) {
            $errors['description'] = 'Product description is required.';
        } elseif (!preg_match("/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9\s\.,'\"'\-%\/:&|()]{10,255}$/u", $data->description)) {
            $errors['description'] = 'Description must be between 10-255 characters and contain only letters, numbers, and basic punctuation.';
        } elseif ($isNewProduct && !$this->isUniqueProduct($data->description)) {
            $errors['description'] = 'A product with this description already exists.';
        }

        // Validate category
        if (empty($data->category)) {
            $errors['category'] = 'Category is required.';
        } else {
            $db = Database::getInstance();
            $query = "SELECT COUNT(*) AS count FROM categories WHERE id = :id";
            $cat = $db->read($query, ['id' => $data->category]);

            if (!$cat || $cat[0]['count'] == 0) {
                $errors['category'] = 'Selected category does not exist.';
            } else {
                $categoryModel = new CategoryModel();
                $cat_id = $categoryModel->getCategoryId($data->category);
                if ($this->hasSubCategories($cat_id)) {
                    $errors['category'] = 'Please select a subcategory, not a parent category.';
                }
            }
        }

        // Validate price
        if (empty($data->price)) {
            $errors['price'] = 'Price is required.';
        } elseif (!is_numeric($data->price) || floatval($data->price) <= 0) {
            $errors['price'] = 'Price must be a positive number.';
        }

        // Validate quantity
        if (empty($data->quantity)) {
            $errors['quantity'] = 'Quantity is required.';
        } elseif (!is_numeric($data->quantity) || intval($data->quantity) <= 0 || intval($data->quantity) != $data->quantity) {
            $errors['quantity'] = 'Quantity must be a positive whole number.';
        }

        return $errors;
    }

    /**
     * Search products with optional category and status filters
     * 
     * @param string $search Search term
     * @param string|null $status Status filter (in_stock, almost_out_of_stock, or out_of_stock)
     * @param float|null $minPrice Minimum price filter
     * @param float|null $maxPrice Maximum price filter
     * @return array|false Array of products or false on failure
     */
    public function searchProducts(string $search, ?string $status = null, ?float $minPrice = null, ?float $maxPrice = null)
    {
        try {
            $params = [];

            // Start with a base query
            $query = "SELECT * FROM products WHERE is_deleted = 0";

            // Add search filter if not empty
            if (!empty(trim($search))) {
                $searchTerm = '%' . trim($search) . '%';
                $query .= " AND (
                    id LIKE :search OR
                    description LIKE :search OR 
                    slag LIKE :search
                )";
                $params['search'] = $searchTerm;
            }

            // Add price range filters
            if ($minPrice !== null && $minPrice >= 0) {
                $query .= " AND price >= :min_price";
                $params['min_price'] = $minPrice;
            }

            if ($maxPrice !== null && $maxPrice > 0) {
                $query .= " AND price <= :max_price";
                $params['max_price'] = $maxPrice;
            }

            // Add stock status filter
            if ($status !== null) {
                if ($status === 'in_stock') {
                    $query .= " AND quantity > 5";
                } else if ($status === 'almost_out_of_stock') {
                    $query .= " AND quantity > 0 AND quantity <= 5";
                } else if ($status === 'out_of_stock') {
                    $query .= " AND quantity = 0";
                }
            }

            $query .= " ORDER BY id DESC";

            $db = Database::getInstance();
            $result = $db->read($query, $params);

            // Return empty array instead of false for no results
            if ($result === false) {
                return [];
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error searching products: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while searching products.');
            return [];
        }
    }

    /**
     * Get product statistics
     * 
     * @return array|false Statistics or false on failure
     */
    public function getProductStats()
    {
        try {
            $db = Database::getInstance();

            // Get total products count
            $totalQuery = "SELECT COUNT(*) as count FROM products WHERE is_deleted = 0";
            $totalResult = $db->read($totalQuery);
            $totalCount = $totalResult ? $totalResult[0]['count'] : 0;

            // Get in-stock products count
            $inStockQuery = "SELECT COUNT(*) as count FROM products WHERE is_deleted = 0 AND quantity > 0";
            $inStockResult = $db->read($inStockQuery);
            $inStockCount = $inStockResult ? $inStockResult[0]['count'] : 0;

            // Get out-of-stock products count
            $outOfStockQuery = "SELECT COUNT(*) as count FROM products WHERE is_deleted = 0 AND quantity = 0";
            $outOfStockResult = $db->read($outOfStockQuery);
            $outOfStockCount = $outOfStockResult ? $outOfStockResult[0]['count'] : 0;

            // Get deleted products count
            $trashQuery = "SELECT COUNT(*) as count FROM products WHERE is_deleted = 1";
            $trashResult = $db->read($trashQuery);
            $trashCount = $trashResult ? $trashResult[0]['count'] : 0;

            // Get category breakdown
            $categoryQuery = "SELECT c.category, COUNT(p.id) as count 
                             FROM products p 
                             JOIN categories c ON p.category_id = c.id
                             WHERE p.is_deleted = 0 
                             GROUP BY p.category_id 
                             ORDER BY count DESC 
                             LIMIT 5";
            $categoryResult = $db->read($categoryQuery);

            return [
                'total' => $totalCount,
                'in_stock' => $inStockCount,
                'out_of_stock' => $outOfStockCount,
                'trash' => $trashCount,
                'categories' => $categoryResult ?: []
            ];
        } catch (Exception $e) {
            error_log("Error getting product stats: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while retrieving product statistics.');
            return false;
        }
    }
}
