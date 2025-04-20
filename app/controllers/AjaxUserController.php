<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\CountriesModel;
use App\Models\CheckoutModel;
use Exception;

class AjaxUserController extends Controller
{
    private UserModel $userModel;
    private CountriesModel $countriesModel;
    private CheckoutModel $checkoutModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->countriesModel = $this->model('Countries');
        $this->checkoutModel = $this->model('Checkout');
    }

    public function index(): void
    {
        try {
            $files = $_FILES;
            $data = null;

            if (count($_POST) > 0) {
                $data = (object)$_POST;
            } else {
                $rawData = file_get_contents('php://input');
                if (!empty($rawData)) {
                    $data = json_decode($rawData);
                }
            }

            if (!is_object($data) || !isset($data->dataType)) {
                $this->sendErrorResponse('Invalid data format.', 400);
                return;
            }

            switch ($data->dataType) {
                case 'add_user':
                    $this->addUser($data, $files);
                    break;
                case 'edit_user':
                    $this->editUser($data, $files);
                    break;
                case 'change_user_status':
                    $this->changeUserStatus($data);
                    break;
                case 'get_users':
                    $this->getUsers();
                    break;
                case 'delete_user':
                    $this->deleteUser($data);
                    break;
                case 'get_states':
                    if (isset($data->id)) {
                        $this->getStates($data->id);
                    } else {
                        $this->sendErrorResponse('Missing country ID.', 400);
                    }
                    break;
                case 'get_country_name':
                    if (isset($data->c_id)) {
                        $this->getCountryName($data->c_id);
                    } else {
                        $this->sendErrorResponse('Missing country ID.', 400);
                    }
                    break;
                case 'get_state_name':
                    if (isset($data->s_id)) {
                        $this->getStateName($data->s_id);
                    } else {
                        $this->sendErrorResponse('Missing state ID.', 400);
                    }
                    break;
                case 'check_trash':
                    $this->checkTrash();
                    break;
                case 'restore_user':
                    $this->restoreUser($data);
                    break;
                case 'force_delete_user':
                    $this->forceDeleteUser($data);
                    break;
                case 'get_deleted_users':
                    $this->getDeletedUsers();
                    break;
                case 'search_users':
                    $this->searchUsers($data);
                    break;
                case 'get_user_stats':
                    $this->getUserStats();
                    break;
                default:
                    $this->sendErrorResponse('Invalid dataType.', 400);
                    break;
            }
        } catch (Exception $e) {
            error_log('AjaxUserController error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while processing your request.', 500);
        }
    }

    public function getUsers()
    {
        try {
            $users = $this->userModel->getUsers();
            $this->addOrderCountsToUsers($users);
            $tableHtml = $this->generateUsersTable($users);
            echo json_encode(['success' => true, 'users' => $users, 'table_html' => $tableHtml]);
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    public function getStates($id)
    {
        try {
            if (!$this->isValidId($id)) {
                $this->sendErrorResponse('Invalid country ID.', 400);
                return;
            }

            $states = $this->countriesModel->getStates($id);
            echo json_encode(['states' => $states]);
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    public function getCountryName($id)
    {
        $id = (int)$id;

        try {
            if (!$this->isValidId($id)) {
                $this->sendErrorResponse('Invalid country ID.', 400);
                return;
            }

            $c_name = $this->countriesModel->getCountryName($id);
            if ($c_name) {
                echo json_encode(['success' => true, 'c_name' => $c_name, 'c_id' => $id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Country not found']);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    public function getStateName($id)
    {
        $id = (int)$id;
        try {
            if (!$this->isValidId($id)) {
                $this->sendErrorResponse('Invalid state ID.', 400);
                return;
            }

            $s_name = $this->countriesModel->getStateName($id);
            if ($s_name) {
                echo json_encode(['success' => true, 's_name' => $s_name, 's_id' => $id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'State not found']);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function isValidId($id): bool
    {
        return isset($id) && is_numeric($id);
    }

    private function sendErrorResponse(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        echo json_encode(['success' => false, 'message' => $message]);
    }

    private function addUser($data, $files): void
    {
        if (empty($data)) {
            $this->sendErrorResponse('User data cannot be empty.', 400);
            return;
        }

        try {
            $result = $this->userModel->addUser((object)$data, $files);
            if ($result) {
                $users = $this->userModel->getAllUsers();
                $tableHtml = $this->generateUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'message' => 'User added successfully!',
                    'table_html' => $tableHtml
                ]);
            } else {
                error_log('Failed to add user. UserModel error message: ' . $this->userModel->msg);
                echo json_encode(['success' => false, 'message' => $this->userModel->msg ?: 'Failed to add user.']);
            }
        } catch (Exception $e) {
            error_log('Error adding user: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            $this->sendErrorResponse('An error occurred while adding the user: ' . $e->getMessage(), 500);
        }
    }

    private function editUser($data, $files): void
    {
        if (empty($data)) {
            $this->sendErrorResponse('User data cannot be empty.', 400);
            return;
        }

        try {
            $result = $this->userModel->editUser((object)$data, $files);
            if ($result) {
                // Get the updated user data
                $updatedUser = $this->userModel->getUserById((int)$data->id);
                
                $users = $this->userModel->getAllUsers();
                $tableHtml = $this->generateUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'table_html' => $tableHtml,
                    'user' => $updatedUser
                ]);
            } else {
                error_log('Failed to update user. UserModel error message: ' . $this->userModel->msg);
                echo json_encode(['success' => false, 'message' => $this->userModel->msg ?: 'Failed to update user.']);
            }
        } catch (Exception $e) {
            error_log('Error updating user: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'An error occurred while updating the user: ' . $e->getMessage()]);
        }
    }

    private function changeUserStatus($data): void
    {
        try {
            $result = $this->userModel->changeStatus($data);
            if ($result) {
                $users = $this->userModel->getAllUsers();
                $tableHtml = $this->generateUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'table_html' => $tableHtml
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $this->userModel->msg]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function generateUsersTable(?array $users, ?int $orderCount = null): string
    {
        if ($users === null) {
            return '<tr><td colspan="5" class="text-center">Error loading users</td></tr>';
        }
        return $this->userModel->generateUsersTable($users, $orderCount);
    }

    private function deleteUser($data): void
    {
        try {
            if (empty($data->id) || !$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid or missing user ID.', 400);
                return;
            }

            $result = $this->userModel->deleteUser((int)$data->id);
            if ($result) {
                $users = $this->userModel->getUsers();
                $tableHtml = $this->generateUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'table_html' => $tableHtml
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $this->userModel->msg]);
            }
        } catch (Exception $e) {
            error_log('Delete user controller error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while processing your request.', 500);
        }
    }

    public function getDeletedUsers(): void
    {
        try {
            $users = $this->userModel->getDeletedUsers();

            if ($users === false) {
                echo json_encode([
                    'success' => false,
                    'message' => $this->userModel->msg ?: 'Failed to retrieve deleted users.',
                    'table_html' => '<tr><td colspan="6" class="text-center">Error loading deleted users</td></tr>'
                ]);
                return;
            }
            
            $this->addOrderCountsToUsers($users);
            $tableHtml = $this->generateDeletedUsersTable($users);
            echo json_encode([
                'success' => true,
                'message' => 'Deleted users retrieved successfully.',
                'table_html' => $tableHtml
            ]);
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function generateDeletedUsersTable(?array $users): string
    {
        if ($users === null) {
            return '<tr><td colspan="5" class="text-center text-muted">No users found</td></tr>';
        }

        try {
            return $this->userModel->generateDeletedUsersTable($users);
        } catch (Exception $e) {
            error_log('Error generating deleted users table: ' . $e->getMessage());
            return '<tr><td colspan="5" class="text-center text-danger">Error displaying users</td></tr>';
        }
    }

    private function restoreUser($data): void
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid user ID.', 400);
                return;
            }

            $result = $this->userModel->restoreUser($data->id);
            if ($result) {
                $users = $this->userModel->getDeletedUsers();
                $tableHtml = $this->generateDeletedUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'table_html' => $tableHtml
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $this->userModel->msg]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function forceDeleteUser($data): void
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid user ID.', 400);
                return;
            }

            $result = $this->userModel->forceDeleteUser($data->id);
            if ($result) {
                $users = $this->userModel->getAllUsers();
                $tableHtml = $this->generateUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'table_html' => $tableHtml
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $this->userModel->msg]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function checkTrash(): void
    {
        try {
            $hasDeletedUsers = $this->userModel->checkTrash();

            if ($hasDeletedUsers === false) {
                // Error occurred while checking trash
                echo json_encode([
                    'success' => false,
                    'message' => $this->userModel->msg,
                    'has_deleted_users' => false
                ]);
                return;
            }

            if ($hasDeletedUsers) {
                // Trash has deleted users, get them
                $users = $this->userModel->getDeletedUsers();
                $tableHtml = $this->generateDeletedUsersTable($users);

                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'has_deleted_users' => true,
                    'table_html' => $tableHtml
                ]);
            } else {
                // Trash is empty
                echo json_encode([
                    'success' => true,
                    'message' => $this->userModel->msg,
                    'has_deleted_users' => false,
                    'table_html' => '<tr><td colspan="5" class="text-center">Trash is empty</td></tr>'
                ]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred while checking trash: ' . $e->getMessage(), 500);
        }
    }

    private function searchUsers($data): void
    {
        try {
            if (!isset($data->search)) {
                $this->sendErrorResponse('Search term is required.', 400);
                return;
            }

            $role = isset($data->role) && in_array($data->role, ['admin', 'customer', '']) ? $data->role : null;
            $status = isset($data->status) && in_array($data->status, ['0', '1', '']) ? $data->status : null;

            $users = $this->userModel->searchUsers($data->search, $role ?: null, $status !== '' ? $status : null);
            
            if ($users !== false) {
                $this->addOrderCountsToUsers($users);
                $tableHtml = $this->generateUsersTable($users);
                echo json_encode([
                    'success' => true,
                    'users' => $users,
                    'table_html' => $tableHtml
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->userModel->msg
                ]);
            }
        } catch (Exception $e) {
            error_log('Search users error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while searching users.', 500);
        }
    }

    private function getUserStats(): void
    {
        try {
            $activeUsers = $this->userModel->getUsers();
            $deletedUsers = $this->userModel->getDeletedUsers();
            
            $adminCount = 0;
            $customerCount = 0;
            
            if ($activeUsers) {
                foreach ($activeUsers as $user) {
                    if ($user['rank'] === 'admin') {
                        $adminCount++;
                    } else {
                        $customerCount++;
                    }
                }
            }
            
            $trashCount = $deletedUsers ? count($deletedUsers) : 0;
            $totalCount = $adminCount + $customerCount;
            
            echo json_encode([
                'success' => true,
                'statistics' => [
                    'total' => $totalCount,
                    'admin' => $adminCount,
                    'customer' => $customerCount,
                    'trash' => $trashCount
                ]
            ]);
        } catch (Exception $e) {
            error_log('Get user stats error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving user statistics.', 500);
        }
    }

    private function addOrderCountsToUsers(&$users): void
    {
        if ($users) {
            foreach ($users as &$user) {
                if (isset($user['url_address'])) {
                    $user['order_count'] = $this->checkoutModel->getUserOrdersCount($user['url_address']);
                } else {
                    $user['order_count'] = 0;
                }
            }
            unset($user); // Break the reference
        }
    }
}
