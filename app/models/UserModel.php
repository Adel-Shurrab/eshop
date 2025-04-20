<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class UserModel
{
    private $db;
    public $msg = '';
    private const ERROR_REQUIRED_FIELDS = 'All required fields must be filled.<br>';
    private const ERROR_INVALID_USERNAME = 'Username must be 6-12 characters long and contain only alphanumeric characters.<br>';
    private const ERROR_INVALID_NAME = 'Name must be between 2 and 50 characters and can only contain letters, spaces, apostrophes, and hyphens.<br>';
    private const ERROR_INVALID_EMAIL = 'Invalid email address.<br>';
    private const ERROR_PASSWORD_MISMATCH = 'Passwords do not match.<br>';
    private const ERROR_INVALID_PASSWORD = 'Password must be 8-20 characters long and contain only allowed special characters.<br>';
    private const ERROR_EMAIL_EXISTS = 'Email is already registered.<br>';
    private const ERROR_QUERY_FAILURE = 'Database query failed. Please try again later.<br>';
    private const ERROR_INVALID_ADDRESS = 'Address must be between 4 and 100 characters with allowed symbols.<br>';
    private const ERROR_INVALID_PHONE = 'Phone number must be 7-15 digits with optional + prefix.<br>';
    private const ERROR_INVALID_ZIP = 'ZIP code must be 3-10 alphanumeric characters with optional spaces or hyphens.<br>';

    // Constructor & Initialization
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Authentication Methods
    public function signup($post)
    {
        $data = $this->sanitizeData($post);

        // Validate the input
        if (!$this->validateSignupData($data)) {
            error_log("Validation failed: " . $this->msg);
            setSessionMessage('danger', $this->msg);
            header('Location:' . BASE_URL . 'signup');
            exit;
        }

        unset($data['password2']);
        unset($data['remember']);

        $data['rank'] = 'customer';
        $data['url_address'] = strtolower($this->getRandomStringMax(60));
        $data['date'] = date('Y-m-d H:i:s');
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $insertUserQuery = "INSERT INTO users (rank, name, email, password, url_address, date) VALUES (:rank, :username, :email, :password, :url_address, :date)";
        try {
            $result = $this->db->write($insertUserQuery, $data);
            if ($result) {
                $this->mergeGuestCartWithUserCart();
                $this->msg = 'Signup successful!';
                error_log("Signup successful: " . json_encode($data));
                setSessionMessage('success', $this->msg);

                header('Location:' . BASE_URL . 'login');
                exit;
            } else {
                throw new Exception("Database write operation failed.");
            }
        } catch (Exception $e) {
            $this->msg = self::ERROR_QUERY_FAILURE;
            error_log("Signup failed: " . $e->getMessage() . " | Data: " . json_encode($data));
            setSessionMessage('danger', $this->msg);
            header('Location:' . BASE_URL . 'signup');
            exit;
        }
    }

    public function login($post)
    {
        $data = $this->sanitizeData($post);

        // Extract only email and password
        $data = [
            'email' => $data['email'],
            'password' => $data['password'],
            'remember' => $data['remember'],
        ];

        // Validate the input
        if (!$this->validateLoginData($data)) {
            error_log("Validation failed: " . $this->msg);
            setSessionMessage('danger', $this->msg);
            header('Location:' . BASE_URL . 'login');
            exit;
        }

        $getUserQuery = "SELECT * FROM users WHERE email = :email LIMIT 1";
        try {
            $user = $this->db->read($getUserQuery, ['email' => $data['email']]);
            if ($user && password_verify($data['password'], $user[0]['password'])) {
                session_regenerate_id(true);

                $_SESSION['user_url'] = $user[0]['url_address'];
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

                if ($data['remember'] === 'on') {
                    $token = bin2hex(random_bytes(16));
                    $expiry = time() + 60 * 60 * 24; // 1 day
                    setcookie('remember_me', $token, $expiry, '/', true, true);
                } else {
                    setcookie('remember_me', '', time() - 3600, '/', true, true);
                }

                $this->updateLastLogin($user[0]['id']);

                $this->mergeGuestCartWithUserCart();
                header('Location:' . BASE_URL);
                exit;
            } else {
                $this->msg = 'Invalid email or password.';
                error_log("Login failed: " . $this->msg);
                setSessionMessage('danger', $this->msg);
                header('Location:' . BASE_URL . 'login');
                exit;
            }
        } catch (Exception $e) {
            error_log("Database query failed: " . $e->getMessage());
            $this->msg = self::ERROR_QUERY_FAILURE;
            setSessionMessage('danger', $this->msg);
            header('Location:' . BASE_URL . 'login');
            exit;
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user_url'])) {
            unset($_SESSION['user_url']);
        }
        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    public function checkLogin($redirect = false, $allowed = array())
    {
        if (isset($_SESSION['user_url']) && !empty($_SESSION['user_url'])) {
            if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                header('Location: ' . BASE_URL . 'logout');
                exit;
            }

            $urlAddress = $_SESSION['user_url'];
            $query = "SELECT * FROM users WHERE url_address = :url_address LIMIT 1";
            $user = $this->db->read($query, ['url_address' => $urlAddress]);

            if (is_array($user) && count($user) > 0) {
                if (count($allowed) > 0 && in_array($user[0]['rank'], $allowed)) {
                    return $user[0];
                } else {
                    header('Location: ' . BASE_URL . 'home');
                    exit;
                }
            }
        }

        if ($redirect) {
            header('Location: ' . BASE_URL . 'logout');
            exit;
        }

        return false;
    }

    // Validation Methods
    private function validateSignupData($data): bool
    {
        $this->msg = '';

        if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['password2'])) {
            $this->msg .= self::ERROR_REQUIRED_FIELDS;
        }

        if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $data['username'])) {
            $this->msg .= self::ERROR_INVALID_USERNAME;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->msg .= self::ERROR_INVALID_EMAIL;
        }

        if ($data['password'] !== $data['password2']) {
            $this->msg .= self::ERROR_PASSWORD_MISMATCH;
        }

        if (!preg_match('/^[a-zA-Z0-9!@#$%^&*_\-()]{8,20}$/', $data['password'])) {
            $this->msg .= self::ERROR_INVALID_PASSWORD;
        }

        // Check if the email already exists in the database
        $checkEmailQuery = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $emailCheck = $this->db->read($checkEmailQuery, ['email' => $data['email']]);
        if ($emailCheck[0]['count'] > 0) {
            $this->msg .= self::ERROR_EMAIL_EXISTS;
        }

        return $this->msg === '';
    }

    private function validateLoginData($data): bool
    {
        $this->msg = '';

        if (empty($data['email']) || empty($data['password'])) {
            $this->msg .= self::ERROR_REQUIRED_FIELDS;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->msg .= self::ERROR_INVALID_EMAIL;
        }

        return $this->msg === '';
    }

    public function validateUserData(object $data, bool $isNewUser = true): bool
    {
        $this->msg = '';

        // Check required fields for new users
        if ($isNewUser) {
            $requiredFields = [
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
                'confirm_password' => $data->confirm_password,
                'rank' => $data->rank,
                'status' => isset($data->status) ? $data->status : null
            ];

            foreach ($requiredFields as $field => $value) {
                if ($field === 'status') {
                    if (!isset($data->status) || $data->status === '') {
                        $this->msg .= "Field $field is required.<br>";
                    }
                } else if (empty($value)) {
                    $this->msg .= "Field $field is required.<br>";
                }
            }
        } else {
            // For existing users, only check name and email
            if (empty($data->name) || empty($data->email) || empty($data->rank) || !isset($data->status)) {
                $this->msg .= self::ERROR_REQUIRED_FIELDS;
            }
        }

        // Validate name
        if (!empty($data->name) && !preg_match('/^[a-zA-Z\s\-\']{2,50}$/', $data->name)) {
            $this->msg .= self::ERROR_INVALID_NAME;
        }

        // Validate email
        if (!empty($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            $this->msg .= self::ERROR_INVALID_EMAIL;
        }

        // Check if email exists, but only for new users or if email has changed
        if (!empty($data->email)) {
            $existingUser = $this->getUserByEmail($data->email);
            if ($existingUser) {
                if ($isNewUser || (!$isNewUser && $existingUser['id'] != $data->id)) {
                    $this->msg .= self::ERROR_EMAIL_EXISTS;
                }
            }
        }

        // Validate password only if it's provided (required for new users)
        if (!empty($data->password)) {
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?])[A-Za-z\d!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]{8,64}$/', $data->password)) {
                $this->msg .= self::ERROR_INVALID_PASSWORD;
            }

            // Check if passwords match only for new users
            if ($isNewUser && $data->password !== $data->confirm_password) {
                $this->msg .= self::ERROR_PASSWORD_MISMATCH;
            }
        } else if ($isNewUser) {
            $this->msg .= self::ERROR_INVALID_PASSWORD;
        }

        // Optional fields validation - only validate if they are provided
        if (!empty($data->address)) {
            if (!preg_match('/^[a-zA-Z0-9\s\.,\-\'\/]{4,100}$/', $data->address)) {
                $this->msg .= self::ERROR_INVALID_ADDRESS;
            }
        }

        if (!empty($data->phone)) {
            if (!preg_match('/^\+?\d{7,15}$/', $data->phone)) {
                $this->msg .= self::ERROR_INVALID_PHONE;
            }
        }

        if (!empty($data->zip)) {
            if (!preg_match('/^[A-Za-z0-9\s\-]{3,10}$/', $data->zip)) {
                $this->msg .= self::ERROR_INVALID_ZIP;
            }
        }

        // Validate rank
        if (!in_array($data->rank, ['admin', 'customer'])) {
            $this->msg .= 'Invalid rank.<br>';
        }

        // Validate status
        if (!isset($data->status) || ($data->status !== '0' && $data->status !== 0 && $data->status !== '1' && $data->status !== 1)) {
            $this->msg .= 'Invalid status.<br>';
        }

        return $this->msg === '';
    }

    // Helper/Utility Methods
    private function updateLastLogin($userId)
    {
        $query = "UPDATE users SET last_login = :last_login WHERE id = :id";
        $this->db->write($query, ['last_login' => date('Y-m-d H:i:s'), 'id' => $userId]);
    }

    public function mergeGuestCartWithUserCart()
    {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $cartModel = new CartModel();
            foreach ($_SESSION['cart'] as $item) {
                $cartModel->addToCart($item['id'], $item['quantity']);
            }
            unset($_SESSION['cart']);
        }
    }

    private function sanitizeData($post)
    {
        return [
            'username' => trim($post['username']),
            'email' => trim($post['email']),
            'password' => trim($post['password']),
            'password2' => trim($post['confirm_password']),
            'remember' => trim($post['remember']),
        ];
    }

    private function sanitizeInput(string $input): string
    {
        $input = htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        $input = htmlspecialchars_decode($input, ENT_QUOTES);
        return $input;
    }

    private function getRandomStringMax($length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = rand(4, $length);

        for ($i = 0; $length > $i; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    private function setSuccessMessage(string $message): void
    {
        $this->msg = $message;
    }

    private function setErrorMessage(string $message): void
    {
        $this->msg = $message;
    }

    // User CRUD Operations
    public function getAllUsers()
    {
        $query = "SELECT * FROM users ORDER BY id DESC";
        return $this->db->read($query);
    }

    public function getUsers()
    {
        $query = "SELECT * FROM users WHERE is_deleted = 0 ORDER BY id DESC";
        $users = $this->db->read($query);
        
        // Initialize order count for each user
        if ($users) {
            foreach ($users as &$user) {
                $user['order_count'] = 0;
            }
            unset($user); // Break the reference
        }
        
        return $users;
    }

    public function getAdminUsers()
    {
        $query = "SELECT * FROM users WHERE rank = 'admin' AND is_deleted = 0 ORDER BY id DESC";
        return $this->db->read($query);
    }

    public function getCustomersUsers()
    {
        $query = "SELECT * FROM users WHERE rank = 'customer' AND is_deleted = 0 ORDER BY id DESC";
        return $this->db->read($query);
    }

    public function getUserById(int $id): ?array
    {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $result = $this->db->read($query, ['id' => $id]);
        return $result ? $result[0] : null;
    }

    public function getUserByEmail(string $email)
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $result = $this->db->read($query, ['email' => $email]);
        return $result ? $result[0] : null;
    }

    private function handleAvatarUpload($files, $userId): ?string
    {
        if (empty($files['avatar']) || $files['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $imageModel = new ImageModel();

        // Validate image
        $errors = $imageModel->validateImage($files['avatar']);
        if (!empty($errors)) {
            $this->msg .= implode('<br>', $errors);
            return null;
        }

        // Process and save avatar
        $avatarPath = $imageModel->processAvatar($files['avatar'], $userId);
        if (!$avatarPath) {
            $this->msg .= 'Failed to process avatar image.<br>';
            return null;
        }

        return $avatarPath;
    }

    private function deleteOldAvatar($userId): void
    {
        $user = $this->getUserById($userId);
        if ($user && !empty($user['avatar'])) {
            $imageModel = new ImageModel();
            $imageModel->deleteAvatar($user['avatar']);
        }
    }

    public function addUser(object $data, array $files)
    {
        if (!$this->validateUserData($data)) {
            $this->setErrorMessage($this->msg);
            return false;
        }

        try {
            $this->db->beginTransaction();

            $user_url = strtolower($this->getRandomStringMax(60));
            $name     = $this->sanitizeInput($data->name);
            $email    = $this->sanitizeInput($data->email);
            $password = password_hash($data->password, PASSWORD_DEFAULT);
            $phone    = $this->sanitizeInput($data->phone);
            $address  = $this->sanitizeInput($data->address);
            $zip      = $this->sanitizeInput($data->zip);
            $rank     = $this->sanitizeInput($data->rank);
            $status   = (int)$this->sanitizeInput($data->status);
            $date     = date('Y-m-d H:i:s');

            $query = "INSERT INTO users (url_address, name, email, password, phone, address, gender, zip, rank, country, state, status, date) 
                  VALUES (:user_url, :name, :email, :password, :phone, :address, :gender, :zip, :rank, :country, :state, :status, :date)";

            $arr = [
                'user_url'  => $user_url,
                'name'      => $name,
                'email'     => $email,
                'password'  => $password,
                'phone'     => $phone,
                'address'   => $address,
                'country'   => $data->country,
                'state'     => $data->state,
                'gender'    => $data->gender,
                'zip'       => $zip,
                'rank'      => $rank,
                'status'    => $status,
                'date'      => $date
            ];

            $result = $this->db->write($query, $arr);
            if (!$result) {
                throw new Exception("Failed to insert user data");
            }

            $userId = $this->db->lastInsertId();

            // Handle avatar upload
            if (!empty($files['avatar'])) {
                $avatarPath = $this->handleAvatarUpload($files, $userId);
                if ($avatarPath) {
                    $updateQuery = "UPDATE users SET avatar = :avatar WHERE id = :id";
                    $this->db->write($updateQuery, ['avatar' => $avatarPath, 'id' => $userId]);
                }
            }

            $this->db->commit();
            $this->setSuccessMessage('User added successfully!');
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Database error: ' . $e->getMessage());
            $this->setErrorMessage('An error occurred while saving the user.');
            return false;
        }
    }

    public function editUser(object $data, array $files)
    {
        try {
            $this->db->beginTransaction();

            if (!$this->validateUserData($data, false)) {
                $this->setErrorMessage($this->msg);
                $this->db->rollBack();
                return false;
            }

            $currentUser = $this->getUserById($data->id);
            if (!$currentUser) {
                $this->setErrorMessage('User not found.');
                $this->db->rollBack();
                return false;
            }

            $arr = [
                'id' => (int)$data->id,
                'name' => $this->sanitizeInput($data->name),
                'email' => $this->sanitizeInput($data->email),
                'phone' => preg_replace('/[^+\d]/', '', $this->sanitizeInput($data->phone ?? '')),
                'address' => $this->sanitizeInput($data->address ?? ''),
                'country' => $this->sanitizeInput($data->country ?? ''),
                'state' => $this->sanitizeInput($data->state ?? ''),
                'gender' => $this->sanitizeInput($data->gender ?? ''),
                'zip' => $this->sanitizeInput($data->zip ?? ''),
                'rank' => $this->sanitizeInput($data->rank),
                'status' => (int)$this->sanitizeInput($data->status)
            ];

            if (!empty($data->password)) {
                $arr['password'] = password_hash($data->password, PASSWORD_DEFAULT);
            }

            // Handle avatar upload
            if (!empty($files['avatar'])) {
                $avatarPath = $this->handleAvatarUpload($files, $data->id);
                if ($avatarPath) {
                    $this->deleteOldAvatar($data->id);
                    $arr['avatar'] = $avatarPath;
                }
            }

            $query = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address, 
                     country = :country, state = :state, gender = :gender, zip = :zip, rank = :rank, 
                     status = :status" .
                (!empty($arr['password']) ? ", password = :password" : "") .
                (!empty($arr['avatar']) ? ", avatar = :avatar" : "") .
                " WHERE id = :id";

            $result = $this->db->write($query, $arr);
            if ($result) {
                $this->db->commit();
                $this->setSuccessMessage('User updated successfully!');
                return true;
            }
            throw new Exception("Database write operation failed.");
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating user: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while saving the user.');
            return false;
        }
    }

    public function changeStatus($data)
    {
        try {
            $db = Database::getInstance();
            $query = "SELECT status FROM users WHERE id = :id";
            $result = $db->read($query, ['id' => $data->id]);

            if (!$result) {
                throw new Exception("User not found");
            }

            $newStatus = $result[0]['status'] == 0 ? 1 : 0;
            $updateQuery = "UPDATE users SET status = :status WHERE id = :id";
            $result = $db->write($updateQuery, [
                'status' => $newStatus,
                'id' => (int)$data->id
            ]);

            if ($result) {
                $this->setSuccessMessage('User status updated successfully');
                return true;
            }

            throw new Exception("Failed to update user status");
        } catch (Exception $e) {
            error_log("Error changing user status: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while updating user status');
            return false;
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Check if user exists and is not already deleted
            $checkQuery = "SELECT * FROM users WHERE id = :id AND is_deleted = 0";
            $user = $this->db->read($checkQuery, ['id' => $id]);

            if (!$user) {
                $this->setErrorMessage('User not found or already deleted.');
                $this->db->rollBack();
                return false;
            }

            $deleteQuery = "UPDATE users SET is_deleted = 1, deleted_at = NOW() WHERE id = :id";
            $result = $this->db->write($deleteQuery, ['id' => $id]);

            if (!$result) {
                throw new Exception("Failed to delete user");
            }

            $this->db->commit();
            $this->setSuccessMessage('User deleted successfully.');
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error deleting user: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while deleting the user.');
            return false;
        }
    }

    public function getDeletedUsers()
    {
        try {
            $query = "SELECT * FROM users WHERE is_deleted = 1 ORDER BY deleted_at DESC, id DESC";
            $result = $this->db->read($query);

            if ($result === false) {
                $this->setErrorMessage('Failed to retrieve deleted users from database.');
                return false;
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error retrieving deleted users: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while retrieving deleted users.');
            return false;
        }
    }

    public function restoreUser($id)
    {
        try {
            $this->db->beginTransaction();

            // Check if user exists and is deleted
            $checkQuery = "SELECT * FROM users WHERE id = :id AND is_deleted = 1";
            $user = $this->db->read($checkQuery, ['id' => (int)$id]);

            if (!$user) {
                $this->setErrorMessage('User not found or is not in trash.');
                $this->db->rollBack();
                return false;
            }

            $query = "UPDATE users SET is_deleted = 0, deleted_at = NULL WHERE id = :id";
            $result = $this->db->write($query, ['id' => (int)$id]);

            if (!$result) {
                throw new Exception("Failed to restore user");
            }

            $this->db->commit();
            $this->setSuccessMessage('User restored successfully.');
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error restoring user: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while restoring the user.');
            return false;
        }
    }

    public function forceDeleteUser($id)
    {
        try {
            $this->db->beginTransaction();

            // Check if user exists and is deleted
            $checkQuery = "SELECT * FROM users WHERE id = :id AND is_deleted = 1";
            $user = $this->db->read($checkQuery, ['id' => (int)$id]);

            if (!$user) {
                $this->setErrorMessage('User not found or is not in trash.');
                $this->db->rollBack();
                return false;
            }

            // Delete user permanently
            $query = "DELETE FROM users WHERE id = :id";
            $result = $this->db->write($query, ['id' => (int)$id]);

            if (!$result) {
                throw new Exception("Failed to permanently delete user");
            }

            $this->db->commit();
            $this->setSuccessMessage('User permanently deleted.');
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error permanently deleting user: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while permanently deleting the user.');
            return false;
        }
    }

    public function checkTrash(): bool
    {
        try {
            $query = "SELECT COUNT(*) as count FROM users WHERE is_deleted = 1";
            $result = $this->db->read($query);

            if ($result === false) {
                $this->setErrorMessage('Failed to check trash status.');
                return false;
            }

            $hasDeletedUsers = ($result && $result[0]['count'] > 0);

            if (!$hasDeletedUsers) {
                $this->setSuccessMessage('Trash is empty.');
            } else {
                $this->setSuccessMessage('Found ' . $result[0]['count'] . ' deleted user(s) in trash.');
            }

            return $hasDeletedUsers;
        } catch (Exception $e) {
            error_log("Error checking trash: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while checking trash status.');
            return false;
        }
    }

    public function generateUsersTable($users, $orderCount = null)
    {
        if (empty($users)) {
            return '<tr><td colspan="6" class="text-center text-muted">No users found</td></tr>';
        }
        
        return $this->generateUserRows($users);
    }

    public function generateDeletedUsersTable($users)
    {
        if (empty($users)) {
            return '<tr><td colspan="6" class="text-center text-muted">No users found</td></tr>';
        }
        
        return $this->generateDeletedUserRows($users);
    }
    
    /**
     * Generate HTML table rows for active users
     * 
     * @param array $users Array of user data
     * @return string HTML of table rows
     */
    private function generateUserRows($users)
    {
        $html = '';
        
        foreach ($users as $user) {
            $html .= $this->generateCommonUserCells($user);
            $html .= $this->generateActionButtons($user);
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    /**
     * Generate HTML table rows for deleted users
     * 
     * @param array $users Array of deleted user data
     * @return string HTML of table rows
     */
    private function generateDeletedUserRows($users)
    {
        $html = '';
        
        foreach ($users as $user) {
            $html .= $this->generateCommonUserCells($user, true);
            $html .= $this->generateDeletedActionButtons($user);
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    /**
     * Generate common cells for both active and deleted users
     * 
     * @param array $user User data
     * @param bool $isDeleted Whether the user is deleted
     * @return string HTML of common cells
     */
    private function generateCommonUserCells($user, $isDeleted = false)
    {
        $html = '<tr>';
        $html .= '<td>';
        $html .= '<img src="' . BASE_URL . (!empty($user['avatar']) ? $user['avatar'] : 'uploads/avatars/default.png') . '" class="user-avatar-sm" alt="avatar">';
        $html .= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
        $html .= '</td>';
        $html .= '<td>' . htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td>';
        $html .= '<span class="user-badge bg-' . ($user['rank'] === 'admin' ? 'primary' : 'info') . '">';
        $html .= ucfirst($user['rank']);
        $html .= '</span>';
        $html .= '</td>';
        
        // Different fourth column for deleted vs active users
        if ($isDeleted) {
            $html .= '<td>' . date('M d, Y', strtotime($user['deleted_at'])) . '</td>';
        } else {
            $html .= '<td>';
            $html .= '<span class="user-badge bg-' . ($user['status'] == '1' ? 'success' : 'danger') . '">';
            $html .= $user['status'] == 1 ? 'Active' : 'Inactive';
            $html .= '</span>';
            $html .= '</td>';
        }
        
        $html .= '<td>';
        $html .= '<span class="badge badge-info">';
        $html .= $user['order_count'] . ' orders';
        $html .= '</span>';
        $html .= '</td>';
        
        return $html;
    }
    
    /**
     * Generate action buttons for active users
     * 
     * @param array $user User data
     * @return string HTML of action buttons
     */
    private function generateActionButtons($user)
    {
        $html = '<td class="text-center">';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-xs btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html .= '<i class="fa fa-ellipsis-v"></i>';
        $html .= '</button>';
        $html .= '<div class="dropdown-menu dropdown-menu-right">';
        
        // View details action
        $html .= $this->generateViewDetailsAction($user);
        
        $html .= '<div class="dropdown-divider"></div>';
        
        // Edit action
        $html .= $this->generateEditAction($user);
        
        $html .= '<div class="dropdown-divider"></div>';
        
        // Status toggle action
        $html .= $this->generateStatusToggleAction($user);
        
        $html .= '<div class="dropdown-divider"></div>';
        
        // Delete action
        $html .= '<a class="dropdown-item text-danger" href="javascript:void(0)" ';
        $html .= 'onclick="deleteUser(' . $user['id'] . ')">';
        $html .= '<i class="fa fa-trash-o fa-fw"></i> Delete';
        $html .= '</a>';
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</td>';
        
        return $html;
    }
    
    /**
     * Generate action buttons for deleted users
     * 
     * @param array $user Deleted user data
     * @return string HTML of action buttons
     */
    private function generateDeletedActionButtons($user)
    {
        $html = '<td class="text-center">';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-xs btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $html .= '<i class="fa fa-ellipsis-v"></i>';
        $html .= '</button>';
        $html .= '<div class="dropdown-menu dropdown-menu-right">';
        $html .= '<a class="dropdown-item text-info" href="javascript:void(0)" onclick="restoreUser(' . $user['id'] . ')">';
        $html .= '<i class="fa fa-undo fa-fw"></i> Restore';
        $html .= '</a>';
        $html .= '<div class="dropdown-divider"></div>';
        $html .= '<a class="dropdown-item text-danger" href="javascript:void(0)" onclick="forceDeleteUser(' . $user['id'] . ')">';
        $html .= '<i class="fa fa-trash fa-fw"></i> Delete Permanently';
        $html .= '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</td>';
        
        return $html;
    }
    
    /**
     * Generate view details action button
     * 
     * @param array $user User data
     * @return string HTML of view details action
     */
    private function generateViewDetailsAction($user)
    {
        $html = '<a class="dropdown-item text-info" href="javascript:void(0)" onclick="viewCustomer(this)" ';
        $html .= 'data-id="' . (int)$user['id'] . '" ';
        $html .= 'data-name="' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-email="' . htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-gender="' . htmlspecialchars($user['gender'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-phone="' . htmlspecialchars($user['phone'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-address="' . htmlspecialchars($user['address'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-country="' . htmlspecialchars($user['country'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-state="' . htmlspecialchars($user['state'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-zip="' . htmlspecialchars($user['zip'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-last_login="' . htmlspecialchars($user['last_login'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-date="' . htmlspecialchars($user['date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-rank="' . htmlspecialchars($user['rank'], ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-status="' . (int)$user['status'] . '" ';
        $html .= 'data-country-id="' . htmlspecialchars($user['country'] ?? '', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-state-id="' . htmlspecialchars($user['state'] ?? '', ENT_QUOTES, 'UTF-8') . '" ';
        $html .= 'data-avatar="' . (!empty($user['avatar']) ? htmlspecialchars($user['avatar'], ENT_QUOTES, 'UTF-8') : 'uploads/avatars/default.png') . '" ';
        $html .= 'data-order-count="' . (int)$user['order_count'] . '" ';
        $html .= 'data-url-address="' . htmlspecialchars($user['url_address'], ENT_QUOTES, 'UTF-8') . '">';
        $html .= '<i class="fa fa-eye fa-fw"></i> View Details';
        $html .= '</a>';
        
        return $html;
    }
    
    /**
     * Generate edit action button
     * 
     * @param array $user User data
     * @return string HTML of edit action
     */
    private function generateEditAction($user)
    {
        $html = '<a class="dropdown-item text-primary" href="javascript:void(0)" ';
        $html .= 'onclick="showEdit(';
        $html .= "'" . $user['id'] . "',";
        $html .= "'" . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['address'] ?? '', ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['country'] ?? '', ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['state'] ?? '', ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['zip'] ?? '', ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['status'], ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['gender'] ?? '', ENT_QUOTES, 'UTF-8') . "',";
        $html .= "'" . htmlspecialchars($user['rank'], ENT_QUOTES, 'UTF-8') . "'";
        $html .= ')">';
        $html .= '<i class="fa fa-edit fa-fw"></i> Edit';
        $html .= '</a>';
        
        return $html;
    }
    
    /**
     * Generate status toggle action button
     * 
     * @param array $user User data
     * @return string HTML of status toggle action
     */
    private function generateStatusToggleAction($user)
    {
        $html = '';
        if ((int)$user['status'] === 1) {
            $html .= '<a class="dropdown-item text-warning" href="javascript:void(0)" ';
            $html .= 'onclick="changeUserStatus(' . (int)$user['id'] . ', 0)" ';
            $html .= 'title="Deactivate user account">';
            $html .= '<i class="fa fa-times-circle fa-fw"></i> Deactivate';
            $html .= '</a>';
        } else {
            $html .= '<a class="dropdown-item text-success" href="javascript:void(0)" ';
            $html .= 'onclick="changeUserStatus(' . (int)$user['id'] . ', 1)" ';
            $html .= 'title="Activate user account">';
            $html .= '<i class="fa fa-check-circle fa-fw"></i> Activate';
            $html .= '</a>';
        }
        
        return $html;
    }

    public function searchUsers(string $search, ?string $role = null, ?string $status = null)
    {
        try {
            $search = '%' . trim($search) . '%';
            $params = ['search' => $search];
            
            $query = "SELECT * FROM users WHERE is_deleted = 0 AND (
                id LIKE :search OR
                name LIKE :search OR 
                email LIKE :search OR 
                phone LIKE :search OR 
                address LIKE :search
            )";
            
            if ($role !== null && in_array($role, ['admin', 'customer'])) {
                $query .= " AND rank = :role";
                $params['role'] = $role;
            }
            
            if ($status !== null && ($status === '0' || $status === '1')) {
                $query .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $query .= " ORDER BY id DESC";
            
            $result = $this->db->read($query, $params);
            
            if ($result === false) {
                $this->setErrorMessage('Failed to search users.');
                return false;
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error searching users: " . $e->getMessage());
            $this->setErrorMessage('An error occurred while searching users.');
            return false;
        }
    }
}
