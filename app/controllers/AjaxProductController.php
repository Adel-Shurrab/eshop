<?php

namespace App\Controllers;

use App\Core\Controller;
use Exception;

class AjaxProductController extends Controller
{
    public function index()
    {
        $files = $_FILES;

        if (count($_POST) > 0) {
            $data = (object)$_POST;
        } else {
            $data = file_get_contents('php://input');
            if (!is_string($data)) {
                $data = (object)[];
            } else {
                $data = json_decode($data);
            }
        }

        if (!is_object($data) || !isset($data->dataType)) {
            $this->sendErrorResponse('Invalid data format.', 400);
            return;
        }

        $productModel = $this->model('Product');

        switch ($data->dataType) {
            case 'add_product':
                $this->addProduct($data, $files, $productModel);
                break;
            case 'edit_product':
                $this->editProduct($data, $files, $productModel);
                break;
            case 'delete_product':
                $this->deleteProduct($data, $productModel);
                break;
            case 'delete_permanent_product':
                $this->deletePermanentProduct($data, $productModel);
                break;
            case 'restore_product':
                $this->restoreProduct($data, $productModel);
                break;
            case 'get_deleted_products':
                $this->getDeletedProducts($productModel);
                break;
            case 'get_products':
                $this->getProducts($productModel);
                break;
            case 'check_trash':
                $this->checkTrash($productModel);
                break;
            case 'search_products':
                $this->searchProducts($data, $productModel);
                break;
            case 'get_product_stats':
                $this->getProductStats($productModel);
                break;
            default:
                $this->sendErrorResponse('Invalid action.', 400);
                return;
        }
    }

    private function addProduct($data, $files, $productModel)
    {
        try {
            if (empty($data)) {
                $this->sendErrorResponse('Product data cannot be empty.', 400);
                return;
            }

            // First validate basic fields
            $validationErrors = $productModel->validateProductData((object)$data);
            
            // Check if any files were submitted for validation
            $hasFiles = false;
            foreach ($files as $file) {
                if (!empty($file['tmp_name'])) {
                    $hasFiles = true;
                    break;
                }
            }
            
            // If no validation errors and files exist, attempt to create product
            if (empty($validationErrors) && $hasFiles) {
                $result = $productModel->createProduct((object)$data, $files);
                
                if ($result) {
                    $products = $productModel->getProducts();
                    $tbl_rows = $productModel->makeTable($products);
                    $enabledCategories = $productModel->getEnabledCategories();
                    $parentOptions = $productModel->makeParentOptions($enabledCategories);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => $productModel->msg,
                        'tbl_rows' => $tbl_rows,
                        'parentOptions' => $parentOptions
                    ]);
                } else {
                    // Check if there are field errors from file validation
                    $additionalErrors = $this->getFieldErrors($productModel);
                    if (!empty($additionalErrors)) {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Please correct the errors in the form.',
                            'errors' => $additionalErrors
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => $productModel->msg
                        ]);
                    }
                }
            } else {
                // If no files were uploaded, add error for primary image
                if (!$hasFiles) {
                    $validationErrors['product_primary_image'] = 'Primary image is required.';
                }
                
                echo json_encode([
                    'success' => false,
                    'message' => 'Please correct the errors in the form.',
                    'errors' => $validationErrors
                ]);
            }
        } catch (Exception $e) {
            error_log('Error adding product: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while adding the product: ' . $e->getMessage(), 500);
        }
    }

    private function editProduct($data, $files, $model)
    {
        try {
            if (empty($data)) {
                $this->sendErrorResponse('Product data cannot be empty.', 400);
                return;
            }

            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }
            
            // First validate basic fields
            $validationErrors = $model->validateProductData((object)$data, false);
            
            // If no validation errors, attempt to edit product
            if (empty($validationErrors)) {
                $result = $model->editProduct($data->id, $data, $files);
                
                if ($result) {
                    $products = $model->getProducts();
                    $tbl_rows = $model->makeTable($products);
                    $enabledCategories = $model->getEnabledCategories();
                    $parentOptions = $model->makeParentOptions($enabledCategories);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => $model->msg,
                        'tbl_rows' => $tbl_rows,
                        'parentOptions' => $parentOptions
                    ]);
                } else {
                    // Check if there are field errors from file validation
                    $additionalErrors = $this->getFieldErrors($model);
                    if (!empty($additionalErrors)) {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Please correct the errors in the form.',
                            'errors' => $additionalErrors
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => $model->msg
                        ]);
                    }
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Please correct the errors in the form.',
                    'errors' => $validationErrors
                ]);
            }
        } catch (Exception $e) {
            error_log('Error editing product: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while editing the product: ' . $e->getMessage(), 500);
        }
    }

    private function deleteProduct($data, $model)
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            if ($model->deleteProduct($data->id)) {
                $this->makeTable($model);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $model->msg
                ]);
            }
        } catch (Exception $e) {
            error_log('Error deleting product: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while deleting the product.', 500);
        }
    }

    private function restoreProduct(object $data, $model): void
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            if ($model->restoreProduct($data->id)) {
                $this->makeTable($model);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $model->msg
                ]);
            }
        } catch (Exception $e) {
            error_log('Error restoring product: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while restoring the product.', 500);
        }
    }

    private function deletePermanentProduct(object $data, $model): void
    {
        try {
            if (!$this->isValidId($data->id)) {
                $this->sendErrorResponse('Invalid product ID. Please provide a valid product ID.', 400);
                return;
            }

            if ($model->deletePermanentProduct($data->id)) {
                $this->makeTable($model);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => $model->msg
                ]);
            }
        } catch (Exception $e) {
            error_log('Error permanently deleting product: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while permanently deleting the product.', 500);
        }
    }

    private function getProducts($model)
    {
        try {
            $products = $model->getProducts();
            $tbl_rows = $model->makeTable($products);
            $enabledCategories = $model->getEnabledCategories();
            $parentOptions = $model->makeParentOptions($enabledCategories);

            echo json_encode([
                'success' => true,
                'message' => $model->msg,
                'tbl_rows' => $tbl_rows,
                'parentOptions' => $parentOptions
            ]);
        } catch (Exception $e) {
            error_log('Error getting products: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving products.', 500);
        }
    }

    private function getDeletedProducts($model): void
    {
        try {
            $products = $model->getDeletedProducts();
            
            if ($products === false) {
                echo json_encode([
                    'success' => false,
                    'message' => $model->msg ?: 'No deleted products found.'
                ]);
                return;
            }
            
            $tbl_rows = $model->makeTable($products);
            echo json_encode([
                'success' => true,
                'message' => $model->msg ?: 'Retrieved deleted products.',
                'tbl_rows' => $tbl_rows
            ]);
        } catch (Exception $e) {
            error_log('Error getting deleted products: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving deleted products.', 500);
        }
    }

    private function checkTrash($model): void
    {
        try {
            $hasDeletedProducts = $model->checkTrash();

            if ($hasDeletedProducts) {
                // Trash has deleted products, get them
                $products = $model->getDeletedProducts();
                $tbl_rows = $model->makeTable($products);

                echo json_encode([
                    'success' => true,
                    'message' => $model->msg,
                    'has_deleted_products' => true,
                    'tbl_rows' => $tbl_rows
                ]);
            } else {
                // Trash is empty
                echo json_encode([
                    'success' => true,
                    'message' => $model->msg,
                    'has_deleted_products' => false,
                    'tbl_rows' => '<tr><td colspan="9" class="text-center">Trash is empty</td></tr>'
                ]);
            }
        } catch (Exception $e) {
            error_log('Error checking trash: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while checking trash.', 500);
        }
    }

    private function makeTable($model): void
    {
        try {
            $products = $model->getProducts();
            $tbl_rows = $model->makeTable($products);

            $enabledCategories = $model->getEnabledCategories();
            $parentOptions = $model->makeParentOptions($enabledCategories);

            echo json_encode([
                'success' => true,
                'message' => $model->msg,
                'tbl_rows' => $tbl_rows,
                'parentOptions' => $parentOptions
            ]);
        } catch (Exception $e) {
            error_log('Error making table: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while generating the product table.', 500);
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

    /**
     * Extract field errors from model state
     * 
     * This accesses a transient property that might be set on the model
     * during image processing
     */
    private function getFieldErrors($model): array
    {
        if (isset($model->fieldErrors) && is_array($model->fieldErrors)) {
            return $model->fieldErrors;
        }
        return [];
    }

    /**
     * Handle product search with optional filters
     *
     * @param object $data The request data containing search term and filters
     * @param mixed $model ProductModel instance
     * @return void
     */
    private function searchProducts(object $data, $model): void
    {
        try {
            // Allow empty search for filtering by other criteria only
            $search = isset($data->search) ? $data->search : '';
            
            $status = isset($data->status) && in_array($data->status, ['in_stock', 'almost_out_of_stock', 'out_of_stock', '']) ? $data->status : null;
            
            // Process price range filters
            $minPrice = isset($data->minPrice) && is_numeric($data->minPrice) ? (float)$data->minPrice : null;
            $maxPrice = isset($data->maxPrice) && is_numeric($data->maxPrice) ? (float)$data->maxPrice : null;
            
            if ($status === '') {
                $status = null;
            }

            $products = $model->searchProducts($search, $status, $minPrice, $maxPrice);
            
            if (!empty($products)) {
                $tbl_rows = $model->makeTable($products);
            } else {
                // Return a "no results" message for empty results
                $tbl_rows = '<tr><td colspan="9" class="text-center">No products found matching your search criteria</td></tr>';
            }
            
            echo json_encode([
                'success' => true,
                'products' => $products,
                'tbl_rows' => $tbl_rows,
                'empty_results' => empty($products)
            ]);
        } catch (Exception $e) {
            error_log('Search products error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while searching products.', 500);
        }
    }

    /**
     * Get product statistics
     *
     * @param mixed $model ProductModel instance
     * @return void
     */
    private function getProductStats($model): void
    {
        try {
            $stats = $model->getProductStats();
            
            if ($stats === false) {
                echo json_encode([
                    'success' => false,
                    'message' => $model->msg ?: 'Failed to retrieve product statistics.'
                ]);
                return;
            }
            
            echo json_encode([
                'success' => true,
                'statistics' => $stats
            ]);
        } catch (Exception $e) {
            error_log('Get product stats error: ' . $e->getMessage());
            $this->sendErrorResponse('An error occurred while retrieving product statistics.', 500);
        }
    }
}
