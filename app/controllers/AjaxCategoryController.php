<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CategoryModel;
use Exception;

class AjaxCategoryController extends Controller
{
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = $this->model('Category');
    }

    public function index(): void
    {
        try {
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
                case 'add_category':
                    $this->addCategory($data);
                    break;
                case 'edit_category':
                    $this->editCategory($data);
                    break;
                case 'delete_category':
                    $this->deleteCategory($data);
                    break;
                case 'get_categories':
                    $this->getCategories();
                    break;
                case 'change_category_status':
                    $this->changeCategoryStatus($data);
                    break;
                case 'restore_category':
                    $this->restoreCategory($data);
                    break;
                case 'get_deleted_categories':
                    $this->getDeletedCategories();
                    break;
                case 'delete_permanent_category':
                    $this->forceDeleteCategory($data);
                    break;
                case 'check_trash':
                    $this->checkTrash();
                    break;
                case 'search_categories':
                    $this->searchCategories($data);
                    break;
                case 'get_category_stats':
                    $this->getCategoryStats();
                    break;
                case 'get_categories_data_only':
                    $this->getCategoriesDataOnly();
                    break;
                case 'get_deleted_categories_count':
                    $this->getDeletedCategoriesCount();
                    break;
                default:
                    $this->sendErrorResponse('Invalid dataType.', 400);
                    break;
            }
        } catch (Exception $e) {
            error_log('AjaxCategoryController error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while processing your request.', 500);
        }
    }

    public function getCategories(): void
    {
        try {
            $categories = $this->categoryModel->getCategories();
            $tableHtml = $this->generateCategoriesTable($categories);
            $enabledCategories = $this->categoryModel->getEnabledCategories();
            $parentOptions = $this->categoryModel->makeParentOptions($enabledCategories);
            
            echo json_encode([
                'success' => true, 
                'categories' => $categories, 
                'tbl_rows' => $tableHtml,
                'parentOptions' => $parentOptions
            ]);
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function addCategory(object $data): void
    {
        if (empty($data->category)) {
            $this->sendErrorResponse('Category name cannot be empty.', 400);
            return;
        }

        try {
            $result = $this->categoryModel->createCategory($data);
            if ($result) {
                $categories = $this->categoryModel->getCategories();
                $tableHtml = $this->generateCategoriesTable($categories);
                $enabledCategories = $this->categoryModel->getEnabledCategories();
                $parentOptions = $this->categoryModel->makeParentOptions($enabledCategories);
                
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'tbl_rows' => $tableHtml,
                    'parentOptions' => $parentOptions
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg ?: 'Failed to add category.'
                ]);
            }
        } catch (Exception $e) {
            error_log('Error adding category: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            $this->sendErrorResponse('An error occurred while adding the category: ' . $e->getMessage(), 500);
        }
    }

    private function editCategory(object $data): void
    {
        if (!$this->isValidId($data->id) || empty($data->category)) {
            $this->sendErrorResponse('Invalid data. Please provide a valid category ID and category name.', 400);
            return;
        }

        try {
            $result = $this->categoryModel->editCategory($data->id, $data);
            if ($result) {
                $categories = $this->categoryModel->getCategories();
                $tableHtml = $this->generateCategoriesTable($categories);
                $enabledCategories = $this->categoryModel->getEnabledCategories();
                $parentOptions = $this->categoryModel->makeParentOptions($enabledCategories);
                
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'tbl_rows' => $tableHtml,
                    'parentOptions' => $parentOptions
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg ?: 'Failed to update category.'
                ]);
            }
        } catch (Exception $e) {
            error_log('Error updating category: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            $this->sendErrorResponse('An error occurred while updating the category: ' . $e->getMessage(), 500);
        }
    }

    private function deleteCategory(object $data): void
    {
        if (!$this->isValidId($data->id)) {
            $this->sendErrorResponse('Invalid category ID. Please provide a valid category ID.', 400);
            return;
        }

        try {
            $result = $this->categoryModel->deleteCategory($data->id);
            if ($result) {
                $categories = $this->categoryModel->getCategories();
                $tableHtml = $this->generateCategoriesTable($categories);
                $enabledCategories = $this->categoryModel->getEnabledCategories();
                $parentOptions = $this->categoryModel->makeParentOptions($enabledCategories);
                
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'tbl_rows' => $tableHtml,
                    'parentOptions' => $parentOptions
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg
                ]);
            }
        } catch (Exception $e) {
            error_log('Delete category controller error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while processing your request.', 500);
        }
    }

    private function changeCategoryStatus(object $data): void
    {
        if (!$this->isValidId($data->id)) {
            $this->sendErrorResponse('Invalid category ID. Please provide a valid category ID.', 400);
            return;
        }

        try {
            $result = $this->categoryModel->changeCategoryStatus($data);
            if ($result) {
                $categories = $this->categoryModel->getCategories();
                $tableHtml = $this->generateCategoriesTable($categories);
                $enabledCategories = $this->categoryModel->getEnabledCategories();
                $parentOptions = $this->categoryModel->makeParentOptions($enabledCategories);
                
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'tbl_rows' => $tableHtml,
                    'parentOptions' => $parentOptions
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg
                ]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function getDeletedCategories(): void
    {
        try {
            $categories = $this->categoryModel->getDeletedCategories();
            
            if ($categories === false) {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg ?: 'Failed to retrieve deleted categories.',
                    'tbl_rows' => '<tr><td colspan="5" class="text-center">Error loading deleted categories</td></tr>'
                ]);
                return;
            }
            
            $tableHtml = $this->generateDeletedCategoriesTable($categories);
            echo json_encode([
                'success' => true,
                'message' => 'Deleted categories retrieved successfully.',
                'tbl_rows' => $tableHtml
            ]);
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function restoreCategory(object $data): void
    {
        if (!$this->isValidId($data->id)) {
            $this->sendErrorResponse('Invalid category ID. Please provide a valid category ID.', 400);
            return;
        }

        try {
            $result = $this->categoryModel->restoreCategory($data->id);
            if ($result) {
                $categories = $this->categoryModel->getDeletedCategories();
                $tableHtml = $this->generateDeletedCategoriesTable($categories);
                
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'tbl_rows' => $tableHtml
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg
                ]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function forceDeleteCategory(object $data): void
    {
        if (!$this->isValidId($data->id)) {
            $this->sendErrorResponse('Invalid category ID. Please provide a valid category ID.', 400);
            return;
        }

        try {
            $result = $this->categoryModel->deletePermanentCategory($data->id);
            if ($result) {
                $categories = $this->categoryModel->getDeletedCategories();
                $tableHtml = $this->generateDeletedCategoriesTable($categories);
                
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'tbl_rows' => $tableHtml
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg
                ]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }

    private function checkTrash(): void
    {
        try {
            $hasDeletedCategories = $this->categoryModel->checkTrash();

            if ($hasDeletedCategories === false) {
                // Error occurred while checking trash
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg,
                    'has_deleted_categories' => false
                ]);
                return;
            }

            if ($hasDeletedCategories) {
                // Trash has deleted categories, get them
                $categories = $this->categoryModel->getDeletedCategories();
                $tableHtml = $this->generateDeletedCategoriesTable($categories);

                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'has_deleted_categories' => true,
                    'tbl_rows' => $tableHtml
                ]);
            } else {
                // Trash is empty
                echo json_encode([
                    'success' => true,
                    'message' => $this->categoryModel->msg,
                    'has_deleted_categories' => false,
                    'tbl_rows' => '<tr><td colspan="5" class="text-center">Trash is empty</td></tr>'
                ]);
            }
        } catch (Exception $e) {
            $this->sendErrorResponse('An error occurred while checking trash: ' . $e->getMessage(), 500);
        }
    }

    private function searchCategories($data): void
    {
        try {
            if (!isset($data->search)) {
                $data->search = ''; // Default to empty search
            }

            $status = isset($data->status) && in_array($data->status, ['0', '1', '']) ? $data->status : null;
            $onlyStatusFilter = (empty($data->search) && $status !== null && $status !== '');
            
            // Use the model to get categories based on search/filter criteria
            $categories = $this->categoryModel->searchCategories($data->search, $status !== '' ? $status : null);
            
            if ($categories !== false) {
                // For status-only filtering, we need to apply a special table generation method 
                // that respects the status filter for subcategories
                if ($onlyStatusFilter) {
                    $tableHtml = $this->categoryModel->makeStatusFilteredTable($categories, $status);
                } else {
                    $tableHtml = $this->categoryModel->makeTable($categories);
                }
                
                echo json_encode([
                    'success' => true,
                    'categories' => $categories,
                    'tbl_rows' => $tableHtml
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $this->categoryModel->msg
                ]);
            }
        } catch (Exception $e) {
            error_log('Search categories error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while searching categories.', 500);
        }
    }

    private function generateCategoriesTable(?array $categories): string
    {
        if ($categories === null) {
            return '<tr><td colspan="5" class="text-center">Error loading categories</td></tr>';
        }
        return $this->categoryModel->makeTable($categories);
    }

    private function generateDeletedCategoriesTable(?array $categories): string
    {
        if ($categories === null) {
            return '<tr><td colspan="5" class="text-center text-muted">No categories found</td></tr>';
        }

        try {
            return $this->categoryModel->makeTable($categories);
        } catch (Exception $e) {
            error_log('Error generating deleted categories table: ' . $e->getMessage());
            return '<tr><td colspan="5" class="text-center text-danger">Error displaying categories</td></tr>';
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

    private function getCategoryStats(): void
    {
        try {
            $activeCategories = $this->categoryModel->getCategories();
            $deletedCategories = $this->categoryModel->getDeletedCategories();
            
            $activeCount = 0;
            $disabledCount = 0;
            
            if ($activeCategories) {
                foreach ($activeCategories as $category) {
                    if ($category['disabled'] == 1) {
                        $activeCount++;
                    } else {
                        $disabledCount++;
                    }
                }
            }
            
            $trashCount = $deletedCategories ? count($deletedCategories) : 0;
            $totalCount = $activeCount + $disabledCount;
            
            echo json_encode([
                'success' => true,
                'statistics' => [
                    'total' => $totalCount,
                    'active' => $activeCount,
                    'disabled' => $disabledCount,
                    'trash' => $trashCount
                ]
            ]);
        } catch (Exception $e) {
            error_log('Get category stats error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving category statistics.', 500);
        }
    }
    
    private function getCategoriesDataOnly(): void
    {
        try {
            $categories = $this->categoryModel->getCategories();
            echo json_encode([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            error_log('Get categories data only error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving categories data.', 500);
        }
    }
    
    private function getDeletedCategoriesCount(): void
    {
        try {
            $deletedCategories = $this->categoryModel->getDeletedCategories();
            $count = $deletedCategories ? count($deletedCategories) : 0;
            
            echo json_encode([
                'success' => true,
                'count' => $count,
                'categories' => $deletedCategories
            ]);
        } catch (Exception $e) {
            error_log('Get deleted categories count error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving deleted categories count.', 500);
        }
    }
}
