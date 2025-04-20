<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class CategoryModel
{
    public $msg = '';
    private const ERROR_QUERY_FAILURE = 'Something went wrong. Please try again later.';
    private const ERROR_INVALID_VALUE = 'Invalid value!';
    private const ERROR_DUPLICATE_CATEGORY = 'Category already exists!';
    private const ERROR_NO_CATEGORY = 'No category provided.';

    public function createCategory($data)
    {
        $db = Database::getInstance();

        // Validate and sanitize input
        if (empty($data->category)) {
            $this->msg = self::ERROR_NO_CATEGORY;
            setSessionMessage('danger', $this->msg);
            return false;
        }

        if (!empty($data->categoryParent)) {
            $catParent = $this->getOneCategory($data->categoryParent);
            if (empty($catParent)) {
                $this->msg = 'Selected parent category does not exist.';
                setSessionMessage('danger', $this->msg);
                return false;
            }
        }

        $category = $this->sanitizeInput($data->category);
        $category = htmlspecialchars_decode($category, ENT_QUOTES);

        if (!$this->isValidCategory($category)) {
            $this->msg = self::ERROR_INVALID_VALUE;
            setSessionMessage('danger', $this->msg);
            return false;
        }

        if (!$this->isUniqueCategory($category)) {
            $this->msg = self::ERROR_DUPLICATE_CATEGORY;
            setSessionMessage('danger', $this->msg);
            return false;
        }

        // Insert into database
        $saveQuery = "INSERT INTO categories (category, parent) VALUES (:category, :categoryParent)";
        try {
            $arr = ['category' => $category, 'categoryParent' => $data->categoryParent];
            $result = $db->write($saveQuery, $arr);

            if ($result) {
                $this->msg = 'Added successfully!';
                setSessionMessage('success', $this->msg);
                return true;
            } else {
                throw new Exception("Database write operation failed.");
            }
        } catch (Exception $e) {
            error_log("General error: " . $e->getMessage());
            $this->msg = self::ERROR_QUERY_FAILURE;
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }

    public function editCategory($id, $data)
    {
        $db = Database::getInstance();

        // Validate and sanitize input
        if (empty($data->category)) {
            $this->msg = 'Category name cannot be empty.';
            setSessionMessage('danger', $this->msg);
            return false;
        }

        $newName = $this->sanitizeInput($data->category);

        // Fetch the current category details
        $currentCategory = $this->getOneCategory($id);
        if (empty($currentCategory)) {
            $this->msg = 'Category does not exist.';
            setSessionMessage('danger', $this->msg);
            return false;
        }

        // Check if the category is trying to be its own parent
        if ($data->categoryParent == $id) {
            $this->msg = 'Category cannot be its own parent.';
            setSessionMessage('danger', $this->msg);
            return false;
        }

        // Validate new category name if it has changed
        if ($newName !== $currentCategory['category']) {
            if (!$this->isValidCategory($newName)) {
                $this->msg = 'Invalid category name.';
                setSessionMessage('danger', $this->msg);
                return false;
            }

            if (!$this->isUniqueCategory($newName)) {
                $this->msg = 'Category already exists.';
                setSessionMessage('danger', $this->msg);
                return false;
            }
        }

        // Validate parent category if provided
        if (!empty($data->categoryParent)) {
            $catParent = $this->getOneCategory($data->categoryParent);
            if (empty($catParent)) {
                $this->msg = 'Selected parent category does not exist.';
                setSessionMessage('danger', $this->msg);
                return false;
            }
        }

        // Begin transaction
        $db->beginTransaction();

        try {
            // Update category in database
            $updateQuery = "UPDATE categories SET category = :category, parent = :parent WHERE id = :id";
            $result = $db->write($updateQuery, [
                'category' => $newName,
                'parent' => $data->categoryParent,
                'id' => (int)$id
            ]);

            if ($result) {
                // Commit transaction
                $db->commit();

                $this->msg = 'Category updated successfully!';
                setSessionMessage('success', $this->msg);
                return true;
            } else {
                throw new Exception("Database update operation failed.");
            }
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();

            error_log("General error: " . $e->getMessage());
            $this->msg = 'Something went wrong. Please try again later.';
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }

    public function deleteCategory($id)
    {
        $db = Database::getInstance();
        $deleteCategoryQuery = "UPDATE categories SET is_deleted = 1, disabled = 0, deleted_at = NOW() WHERE id = :id";
        $deleteSubCategoryQuery = "UPDATE categories SET is_deleted = 1, disabled = 0, deleted_at = NOW() WHERE parent = :id";
        $deleteProductQuery = "UPDATE products SET is_deleted = 1, deleted_at = NOW() WHERE category_id = :category_id";

        // Begin transaction
        $db->beginTransaction();

        try {
            $resultCat = $db->write($deleteCategoryQuery, ['id' => (int)$id]);
            $resultSubCat = $db->write($deleteSubCategoryQuery, ['id' => (int)$id]);
            $resultProduct = $db->write($deleteProductQuery, ['category_id' => (int)$id]);

            if (!$resultCat || !$resultSubCat || !$resultProduct) {
                throw new Exception("Database delete operation failed.");
            }

            // Commit transaction
            $db->commit();

            $this->msg = 'Deleted successfully!';
            setSessionMessage('success', $this->msg);
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();

            error_log("General error: " . $e->getMessage());
            $this->msg = self::ERROR_QUERY_FAILURE;
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }

    public function restoreCategory($id)
    {
        $db = Database::getInstance();
        $category_id = (int)$id;

        // Check if the category exists
        $category = $this->getOneCategory($category_id);
        if (empty($category)) {
            $this->msg = 'Category does not exist.';
            setSessionMessage('danger', $this->msg);
            return false;
        }

        // Check if the parent category is deleted
        if ($category['parent'] != 0) {
            $parentCategory = $this->getOneCategory($category['parent']);
            if ($parentCategory && $parentCategory['is_deleted'] == 1) {
                $this->msg = "Parent category [{$parentCategory['category']}] is deleted. Please restore the parent category first.";
                setSessionMessage('danger', $this->msg);
                return false;
            }
        }

        // Begin transaction
        $db->beginTransaction();

        try {
            // Restore the category
            $restoreCat = "UPDATE categories SET is_deleted = 0, deleted_at = null WHERE id = :id";
            $db->write($restoreCat, ['id' => $category_id]);

            // Restore subcategories
            $restoreSubCat = "UPDATE categories SET is_deleted = 0, deleted_at = null WHERE parent = :id";
            $db->write($restoreSubCat, ['id' => $category_id]);

            // Restore associated products
            $restoreProducts = "UPDATE products SET is_deleted = 0, deleted_at = null WHERE category_id = :id";
            $db->write($restoreProducts, ['id' => $category_id]);

            // Commit transaction
            $db->commit();

            $this->msg = 'Category, its subcategories, and all associated products restored successfully!';
            setSessionMessage('success', $this->msg);
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $db->rollBack();

            error_log("General error: " . $e->getMessage());
            $this->msg = self::ERROR_QUERY_FAILURE;
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }

    public function deletePermanentCategory($id)
    {
        $db = Database::getInstance();
        $category_id = (int)$id;
        $deleteCat = "DELETE FROM categories WHERE id = :id";
        $deleteSubCat = "DELETE FROM categories WHERE parent = :id";
        $deleteProducts = "DELETE FROM products WHERE category_id = :id";

        try {
            $db->write($deleteCat, ['id' => $category_id]);
            $db->write($deleteSubCat, ['id' => $category_id]);
            $db->write($deleteProducts, ['id' => $category_id]);
            $this->msg = 'Category and its products permanently deleted!';
            setSessionMessage('success', $this->msg);
            return true;
        } catch (Exception $e) {
            error_log("General error: " . $e->getMessage());
            $this->msg = self::ERROR_QUERY_FAILURE;
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }

    public function getDeletedCategories()
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM categories WHERE is_deleted = 1";
        return $db->read($query);
    }

    public function checkTrash()
    {
        $db = Database::getInstance();
        $query = "SELECT COUNT(*) AS count FROM categories WHERE is_deleted = 1";
        $result = $db->read($query);
        if ($result && $result[0]['count'] == 0) {
            $this->msg = 'Trash is empty';
            setSessionMessage('info', $this->msg);
            return false;
        } else {
            return true;
        }
    }

    public function searchCategories(string $search, ?string $status = null)
    {
        try {
            $db = Database::getInstance();
            $search = '%' . trim($search) . '%';
            $params = ['search' => $search];
            
            // First, get all categories that match the search criteria
            $query = "SELECT * FROM categories WHERE is_deleted = 0 AND (
                id LIKE :search OR
                category LIKE :search
            )";
            
            if ($status !== null && ($status === '0' || $status === '1')) {
                $query .= " AND disabled = :status";
                $params['status'] = $status === '1' ? 1 : 0;
            }
            
            $query .= " ORDER BY id DESC";
            
            $matchedCategories = $db->read($query, $params);
            
            if ($matchedCategories === false) {
                $this->msg = 'Failed to search categories.';
                setSessionMessage('danger', $this->msg);
                return false;
            }
            
            // If no results found and we're searching by status only, get all categories and filter by status
            if (empty($matchedCategories) && !empty($status) && empty(trim($search)) === '%') {
                $allCategoriesQuery = "SELECT * FROM categories WHERE is_deleted = 0";
                $allCategories = $db->read($allCategoriesQuery);
                
                // Filter by status
                $statusValue = $status === '1' ? 1 : 0;
                $statusFilteredCategories = array_filter($allCategories, function($category) use ($statusValue) {
                    return $category['disabled'] == $statusValue;
                });
                
                if (!empty($statusFilteredCategories)) {
                    $matchedCategories = array_values($statusFilteredCategories);
                }
            }
            
            // If still no results found, return empty array
            if (empty($matchedCategories)) {
                return $matchedCategories;
            }
            
            // Get all categories (we need these to build the hierarchical structure)
            $allCategories = $this->getCategories();
            if ($allCategories === false) {
                return $matchedCategories; // Fall back to just the matched categories
            }
            
            // Collect matched category IDs and their parent IDs
            $matchedIds = array_column($matchedCategories, 'id');
            $parentIds = [];
            
            // For each matched category, we need to find its parent chain
            foreach ($matchedCategories as $category) {
                $this->collectParentChain($category['id'], $allCategories, $parentIds);
            }
            
            // For each matched category, we also want to include its children
            foreach ($matchedCategories as $category) {
                $this->collectChildIds($category['id'], $allCategories, $matchedIds);
            }
            
            // Combine matched IDs with parent IDs (remove duplicates)
            $allRelevantIds = array_unique(array_merge($matchedIds, $parentIds));
            
            // Filter all categories to only include relevant ones
            $filteredCategories = array_filter($allCategories, function($category) use ($allRelevantIds) {
                return in_array($category['id'], $allRelevantIds);
            });
            
            // If only filtering by status, we need a different approach
            if (empty(trim($search)) === '%' && $status !== null) {
                // Get all categories matching the status
                $statusFilteredCategories = array_filter($allCategories, function($category) use ($status) {
                    return $category['disabled'] == ($status === '1' ? 1 : 0);
                });
                
                // Get IDs of status-matched categories
                $statusMatchedIds = array_column($statusFilteredCategories, 'id');
                
                // Mark the categories that should be highlighted as matching the status filter
                $result = array_map(function($category) use ($statusMatchedIds) {
                    $category['matches_filter'] = in_array($category['id'], $statusMatchedIds);
                    return $category;
                }, $filteredCategories);
                
                // Add a status marker to the categories array for the frontend
                $result = array_values($result);
                return $result;
            }
            
            // Re-index the array to preserve numeric indices
            return array_values($filteredCategories);
            
        } catch (Exception $e) {
            error_log("Error searching categories: " . $e->getMessage());
            $this->msg = 'An error occurred while searching categories.';
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }
    
    /**
     * Collect parent IDs for a given category to show the complete hierarchy
     */
    private function collectParentChain($categoryId, $allCategories, &$parentIds)
    {
        foreach ($allCategories as $category) {
            if ($category['id'] == $categoryId && $category['parent'] != 0) {
                $parentIds[] = $category['parent'];
                // Recursively add parents of parents
                $this->collectParentChain($category['parent'], $allCategories, $parentIds);
                break;
            }
        }
    }
    
    /**
     * Collect child IDs for a given category to include in search results
     */
    private function collectChildIds($parentId, $allCategories, &$childIds)
    {
        foreach ($allCategories as $category) {
            if ($category['parent'] == $parentId) {
                $childIds[] = $category['id'];
                // Recursively add children of children
                $this->collectChildIds($category['id'], $allCategories, $childIds);
            }
        }
    }

    public function changeCategoryStatus($data)
    {
        $db = Database::getInstance();
        $query = "SELECT disabled FROM categories WHERE id = :id";
        $result = $db->read($query, ['id' => $data->id]);

        if ($result) {
            $disabled = $result[0]['disabled'] == 0 ? 1 : 0;
            $updateQuery = "UPDATE categories SET disabled = :disabled WHERE id = :id";
            $result = $db->write($updateQuery, ['disabled' => $disabled, 'id' => (int)$data->id]);

            if ($result) {
                if ($disabled == 0) {
                    // If the category is being disabled, disable its subcategories as well
                    $this->disableSubCategories($data->id);
                }
                $this->msg = 'Category status updated successfully.';
                setSessionMessage('success', $this->msg);
                return true;
            } else {
                $this->msg = self::ERROR_QUERY_FAILURE;
                setSessionMessage('danger', $this->msg);
                return false;
            }
        } else {
            $this->msg = self::ERROR_QUERY_FAILURE;
            setSessionMessage('danger', $this->msg);
            return false;
        }
    }

    private function disableSubCategories($parentId)
    {
        $db = Database::getInstance();
        $updateQuery = "UPDATE categories SET disabled = 0 WHERE parent = :parentId";
        $db->write($updateQuery, ['parentId' => (int)$parentId]);
    }

    public function getCategories()
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM categories WHERE is_deleted = 0";
        return $db->read($query);
    }

    public function getEnabledCategories()
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM categories WHERE is_deleted = 0 AND disabled = 1";
        return $db->read($query);
    }

    public function getOneCategory($id): array
    {
        $id = (int)$id;
        $db = Database::getInstance();
        $query = "SELECT * FROM categories WHERE id = :id";
        $result = $db->read($query, ['id' => $id]);

        if ($result === false) {
            error_log("Failed to fetch category for product ID: $id");
        }

        return $result ? $result[0] : [];
    }

    public function getCategoryId(string $category): ?int
    {
        $db = Database::getInstance();
        $query = "SELECT id FROM categories WHERE category = :category LIMIT 1";
        $result = $db->read($query, ['category' => $category]);
        return $result ? (int)$result[0]['id'] : null;
    }

    private function isValidCategory($category)
    {
        return preg_match("/^(?=.*[a-zA-Z])[a-zA-Z0-9\s&'_-]+$/", $category);
    }

    private function isUniqueCategory($category)
    {
        $db = Database::getInstance();
        $checkQuery = "SELECT COUNT(*) AS count FROM categories WHERE category = :category";
        $result = $db->read($checkQuery, ['category' => $category]);
        return $result && $result[0]['count'] == 0;
    }

    private function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public function makeTable($categories)
    {
        $html = '';
        $processedCategories = [];

        // Filter to get top-level categories (parent = 0) and all deleted categories first
        $topLevelOrDeleted = array_filter($categories, function($category) {
            return $category['parent'] == 0 || $category['is_deleted'] == 1;
        });
        
        // Sort by status if we have status filter markers (matches_filter property set)
        $hasStatusFilter = !empty($categories) && isset($categories[0]['matches_filter']);
        
        // If status filter is active, ensure we show the matching categories first
        if ($hasStatusFilter) {
            // First process categories that match the filter
            foreach ($topLevelOrDeleted as $category) {
                if (isset($category['matches_filter']) && $category['matches_filter'] && !in_array($category['id'], $processedCategories)) {
                    $html .= $this->buildCategoryRow($category, 0, true);
                    $processedCategories[] = $category['id'];
                    $html .= $this->buildSubCategoryRows($category['id'], $categories, 1, $processedCategories, true);
                }
            }
            
            // Then process all other categories to preserve hierarchy
            foreach ($topLevelOrDeleted as $category) {
                if (!in_array($category['id'], $processedCategories)) {
                    $html .= $this->buildCategoryRow($category, 0, false);
                    $processedCategories[] = $category['id'];
                    $html .= $this->buildSubCategoryRows($category['id'], $categories, 1, $processedCategories, false);
                }
            }
        } else {
            // Regular processing
            foreach ($topLevelOrDeleted as $category) {
                if (!in_array($category['id'], $processedCategories)) {
                    $html .= $this->buildCategoryRow($category);
                    $processedCategories[] = $category['id'];
                    $html .= $this->buildSubCategoryRows($category['id'], $categories, 1, $processedCategories);
                }
            }
        }
        
        return $html;
    }

    private function buildCategoryRow($category, $indentLevel = 0, $isFilterMatch = null)
    {
        $statusClass = $category['disabled'] == 0 ? 'label-warning' : 'label-success';
        $statusText = $category['disabled'] == 0 ? 'Disabled' : 'Enabled';
        $categoryParentName = $category['parent'] == 0 ? 'None' : $this->getOneCategory($category['parent'])['category'];
        $deletedAt = $category['deleted_at'] ? date('d/m/Y H:i', strtotime($category['deleted_at'])) : 'N/A';

        $indent = str_repeat('&nbsp;', $indentLevel * 5);
        
        // Add highlighting for rows that match the filter
        $rowClass = '';
        if ($isFilterMatch === true) {
            $rowClass = ' class="info"'; // Bootstrap info background for matched rows
        } elseif (isset($category['matches_filter'])) {
            $rowClass = $category['matches_filter'] ? ' class="info"' : '';
        }

        $html = "<tr$rowClass>";
        $html .= '<td>' . $indent . '<span class="category-badge" style="background-color: ' . ($category['disabled'] == 1 ? '#5cb85c' : '#d9534f') . ';"></span> ';
        $html .= '<strong>' . htmlspecialchars($category['category'], ENT_QUOTES, 'UTF-8') . '</strong></td>';
        $html .= '<td>' . htmlspecialchars($categoryParentName, ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= "<td><span class='label $statusClass label-mini'>$statusText</span></td>";
        $html .= '<td>' . htmlspecialchars($deletedAt, ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td class="text-center action-btns">';
        
        // Direct action buttons (no dropdown)
        if ($category['is_deleted'] == 1) {
            // Restore button
            $html .= '<button type="button" onclick="restoreCategory(' . $category['id'] . ')" class="btn btn-xs btn-info" title="Restore">';
            $html .= '<i class="fa fa-undo"></i></button> ';
            
            // Delete permanently button
            $html .= '<button type="button" onclick="deletePermanentCategory(' . $category['id'] . ')" class="btn btn-xs btn-danger" title="Delete Permanently">';
            $html .= '<i class="fa fa-trash"></i></button>';
        } else {
            // Toggle status button (Enable/Disable)
            if ($category['disabled'] == 0) {
                $html .= '<button type="button" onclick="changeCategoryStatus(' . $category['id'] . ')" class="btn btn-xs btn-success" title="Enable">';
                $html .= '<i class="fa fa-check-circle"></i></button> ';
            } else {
                $html .= '<button type="button" onclick="changeCategoryStatus(' . $category['id'] . ')" class="btn btn-xs btn-warning" title="Disable">';
                $html .= '<i class="fa fa-ban"></i></button> ';
            }
            
            // Edit button
            $html .= '<button type="button" onclick="showEdit(' . $category['id'] . ', \'' . htmlspecialchars($category['category'], ENT_QUOTES, 'UTF-8') . '\',\'' . $category['parent'] . '\')" class="btn btn-xs btn-primary" title="Edit">';
            $html .= '<i class="fa fa-pencil"></i></button> ';
            
            // Move to trash button
            $html .= '<button type="button" onclick="deleteCategory(' . $category['id'] . ')" class="btn btn-xs btn-danger" title="Move to Trash">';
            $html .= '<i class="fa fa-trash-o"></i></button>';
        }
        
        $html .= '</td>';
        $html .= '</tr>';

        return $html;
    }

    private function buildSubCategoryRows($parentId, $categories, $indentLevel, &$processedCategories, $isFilterMatch = null)
    {
        $html = '';
        
        // Get all direct subcategories of this parent
        $subcategories = array_filter($categories, function($category) use ($parentId) {
            return $category['parent'] == $parentId;
        });
        
        foreach ($subcategories as $category) {
            if (!in_array($category['id'], $processedCategories)) {
                // Determine if this row matches the filter
                $rowMatchesFilter = $isFilterMatch;
                if ($rowMatchesFilter === null && isset($category['matches_filter'])) {
                    $rowMatchesFilter = $category['matches_filter'];
                }
                
                $html .= $this->buildCategoryRow($category, $indentLevel, $rowMatchesFilter);
                $processedCategories[] = $category['id'];
                $html .= $this->buildSubCategoryRows($category['id'], $categories, $indentLevel + 1, $processedCategories, $rowMatchesFilter);
            }
        }
        return $html;
    }

    public function makeParentOptions($categories, $selectedId = null)
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

    private function buildParentOption($category, $selectedId, $indentLevel = 0)
    {
        $indent = str_repeat('&nbsp;', $indentLevel * 5);
        $selected = $category['id'] == $selectedId ? 'selected' : '';
        return '<option value="' . htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . $indent . htmlspecialchars($category['category'], ENT_QUOTES, 'UTF-8') . '</option>';
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

    /**
     * Special table generator specifically for status filtering
     * This ensures that only subcategories matching the status filter are shown
     */
    public function makeStatusFilteredTable($categories, $status)
    {
        $html = '';
        $processedCategories = [];
        $statusValue = $status === '1' ? 1 : 0;
        
        // Get all categories to find matching subcategories
        $allCategories = $this->getCategories();
        
        // First, process all top-level categories
        foreach ($categories as $category) {
            if ($category['parent'] == 0 && !in_array($category['id'], $processedCategories)) {
                $html .= $this->buildCategoryRow($category, 0, true);
                $processedCategories[] = $category['id'];
                
                // Then process all subcategories that match the status filter
                $html .= $this->buildStatusFilteredSubcategories(
                    $category['id'], 
                    $allCategories, 
                    1, 
                    $processedCategories, 
                    $statusValue
                );
            }
        }
        
        return $html;
    }
    
    /**
     * Build subcategory rows that match the status filter
     */
    private function buildStatusFilteredSubcategories($parentId, $categories, $indentLevel, &$processedCategories, $statusValue)
    {
        $html = '';
        
        // Find all direct children of this parent
        foreach ($categories as $category) {
            if ($category['parent'] == $parentId && !in_array($category['id'], $processedCategories)) {
                // Only include subcategories that match the status filter
                if ($category['disabled'] == $statusValue) {
                    $html .= $this->buildCategoryRow($category, $indentLevel, true);
                    $processedCategories[] = $category['id'];
                    
                    // Recursively process children of this subcategory
                    $html .= $this->buildStatusFilteredSubcategories(
                        $category['id'], 
                        $categories, 
                        $indentLevel + 1, 
                        $processedCategories,
                        $statusValue
                    );
                }
            }
        }
        
        return $html;
    }
}
