<?php
// filepath: /c:/xampp/htdocs/eshop/app/views/eshop/admin/categories.php
renderHeader($data, 'admin');
renderSidebar($data, 'admin');
?>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>

<style type="text/css">
    .add-btn {
        margin-left: 10px;
        padding: 6px 15px;
        font-size: 13px;
        font-weight: 600;
    }

    .modal-content {
        background-color: #f5f5f5;
        border-radius: 6px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header,
    .modal-footer {
        border: none;
        padding: 15px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .cat_label {
        font-weight: bold;
        color: #555;
        margin-bottom: 8px;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .stats-card {
        background: #fff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        transform: translateY(-2px);
    }

    .stats-card .icon {
        font-size: 32px;
        float: left;
        margin-right:
            15px;
        color: #5cb85c;
    }

    .stats-card .number {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stats-card .title {
        font-size: 14px;
        color: #777;
        text-transform: uppercase;
    }

    .filter-tabs {
        margin-bottom: 20px;
    }

    .filter-tabs .nav-tabs {
        border-bottom: none;
    }

    .filter-tabs .nav-tabs>li>a {
        border-radius: 0;
        margin-right: 0;
        color: #555;
        border: 1px solid #ddd;
        border-right: none;
    }

    .filter-tabs .nav-tabs>li:last-child>a {
        border-right: 1px solid #ddd;
    }

    .filter-tabs .nav-tabs>li.active>a,
    .filter-tabs .nav-tabs>li.active>a:focus,
    .filter-tabs .nav-tabs>li.active>a:hover {
        color: #fff;
        background-color: #4ECDC4;
        border: 1px solid #4ECDC4;
    }

    .content-panel {
        padding: 15px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.1);
    }

    .table th {
        background-color: #f9f9f9;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
    }

    .action-buttons .btn {
        margin-right: 3px;
    }

    .search-input-group {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .category-badge {
        display: inline-block;
        padding: 3px 7px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color: #777;
        border-radius: 10px;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    /* Responsive table styles */
    .table-responsive {
        min-height: .01%;
        overflow-x: auto;
    }

    @media screen and (max-width: 767px) {
        .table-responsive {
            width: 100%;
            margin-bottom: 15px;
            overflow-y: hidden;
            -ms-overflow-style: -ms-autohiding-scrollbar;
            border: 1px solid #ddd;
        }

        .table-responsive>.table {
            margin-bottom: 0;
        }

        .table-responsive>.table>thead>tr>th,
        .table-responsive>.table>tbody>tr>th,
        .table-responsive>.table>tfoot>tr>th,
        .table-responsive>.table>thead>tr>td,
        .table-responsive>.table>tbody>tr>td,
        .table-responsive>.table>tfoot>tr>td {
            white-space: nowrap;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header div {
            margin-top: 10px;
            align-self: flex-start;
        }

        .stats-card {
            margin-bottom: 15px;
        }

        .filter-tabs .nav-tabs>li>a {
            padding: 8px 10px;
            font-size: 13px;
        }
    }

    /* Fix for mobile margins */
    .mb-sm-3 {
        margin-bottom: 15px;
    }

    @media (min-width: 768px) {
        .mb-sm-3 {
            margin-bottom: 0;
        }
    }

    /* Highlighted rows for filtered content */
    .table tr.info {
        background-color: rgba(217, 237, 247, 0.6) !important;
    }

    .table-striped>tbody>tr.info:nth-of-type(odd) {
        background-color: rgba(217, 237, 247, 0.4) !important;
    }

    .filter-badge {
        background-color: #5bc0de;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 5px;
    }

    .filter-active {
        font-weight: bold;
        color: #31b0d5;
    }

    /* Styling for action buttons */
    .action-btns {
        white-space: nowrap;
    }

    .action-btns .btn {
        margin-right: 3px;
        padding: 4px 8px;
    }

    .action-btns .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.2s ease;
    }

    .action-btns .btn-success {
        background-color: #5cb85c;
    }

    .action-btns .btn-warning {
        background-color: #f0ad4e;
        color: white;
    }

    .action-btns .btn-primary {
        background-color: #5bc0de;
    }

    .action-btns .btn-danger {
        background-color: #d9534f;
    }

    .action-btns .btn-info {
        background-color: #31b0d5;
    }
</style>

<!-- MAIN CONTENT -->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="page-header">
            <h3><i class="fa fa-folder-open"></i> Manage Categories</h3>
            <div>
                <button class="btn btn-primary btn-sm" onclick="showAddNew()">
                    <i class="fa fa-plus-circle"></i> New Category
                </button>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row mt">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-folder-open text-success"></i>
                    </div>
                    <div class="number" id="total-categories">--</div>
                    <div class="title">Total Categories</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-check-circle text-info"></i>
                    </div>
                    <div class="number" id="active-categories">--</div>
                    <div class="title">Active Categories</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-ban text-warning"></i>
                    </div>
                    <div class="number" id="disabled-categories">--</div>
                    <div class="title">Disabled Categories</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-trash-o text-danger"></i>
                    </div>
                    <div class="number" id="trash-categories">--</div>
                    <div class="title">Deleted Categories</div>
                </div>
            </div>
        </div>

        <div class="row mt">
            <div class="col-lg-12">
                <div class="filter-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#" id="normal_btn" onclick="refreshCategories()">
                                <i class="fa fa-folder-open"></i> All Categories
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#" id="deleted_btn" onclick="toggleTrashView()">
                                <i class="fa fa-trash-o"></i> Trash
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="content-panel">
                    <!-- Search Form -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-2">
                                        <div class="input-group search-input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-search"></i>
                                            </span>
                                            <input type="text" class="form-control" id="searchInput" placeholder="Search categories...">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" onclick="clearSearch()" id="clearSearchBtn" style="display: none;">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                        <div class="input-group" style="width: auto; max-width: 100%;">
                                            <span class="input-group-addon">
                                                <i class="fa fa-toggle-on"></i>
                                            </span>
                                            <select class="form-control" id="statusFilter" style="width: 130px;">
                                                <option value="">All Status</option>
                                                <option value="1">Enabled</option>
                                                <option value="0">Disabled</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-default" type="button" onclick="clearFilters()" id="clearFiltersBtn" style="display: none;">
                                            <i class="fa fa-filter"></i> Clear Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Search Form -->

                    <div class="table-responsive">
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-tags"></i> Category Name</th>
                                    <th><i class="fa fa-sitemap"></i> Parent Category</th>
                                    <th><i class="fa fa-power-off"></i> Status</th>
                                    <th><i class="fa fa-calendar"></i> Deleted At</th>
                                    <th class="text-center"><i class="fa fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_body">
                                <?php if ($data['tbl_rows'] === ''): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No categories found</td>
                                    </tr>
                                <?php else : ?>
                                    <?= $data['tbl_rows'] ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /content-panel -->
            </div>
            <!-- /col-md-12 -->
        </div>
        <!-- /row -->

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa fa-times"></i></span>
                        </button>
                        <h4 class="modal-title" id="confirmActionModalLabel"><i class="fa fa-exclamation-triangle"></i> Confirm Action</h4>
                    </div>
                    <div class="modal-body">
                        <p id="confirmActionMessage"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
                        <button type="button" id="confirmActionBtn" class="btn btn-danger"><i class="fa fa-check-circle"></i> Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Confirmation Modal -->
    </section> <!-- /wrapper -->
</section><!-- /MAIN CONTENT -->

<!-- Add New Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
                <h4 class="modal-title" id="addCategoryModalLabel"><i class="fa fa-plus-square"></i> Add New Category</h4>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="cat" class="cat_label"><i class="fa fa-tag"></i> Category Name:</label>
                        <input type="text" name="category" class="form-control" id="cat" placeholder="Enter category name" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="cats" class="cat_label"><i class="fa fa-tag"></i> Parent Category (optional):</label>
                        <select name="cats" id="cats" class="form-control">
                            <?= $data['parentOptions'] ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
                <button type="button" class="btn btn-primary" onclick="collectData()"><i class="fa fa-floppy-o"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Add New Category Modal -->

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
                <h4 class="modal-title" id="editCategoryModalLabel"><i class="fa fa-pencil-square"></i> Edit Category</h4>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <div class="form-group">
                        <label for="e_cat" class="cat_label"><i class="fa fa-tag"></i> Category Name:</label>
                        <input type="text" name="category" class="form-control" id="e_cat" placeholder="Edit category name" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="e_cats" class="cat_label"><i class="fa fa-tag"></i> Parent Category:</label>
                        <select name="e_cats" id="e_cats" class="form-control">
                            <?= $data['parentOptions'] ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Discard</button>
                <button type="button" class="btn btn-primary" onclick="editCategory()"><i class="fa fa-check-circle"></i> Update</button>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Category Modal -->

<script type="text/javascript">
    function updateStatistics(totalCount = 0, activeCount = 0, disabledCount = 0, trashCount = 0) {
        document.getElementById('total-categories').textContent = totalCount;
        document.getElementById('active-categories').textContent = activeCount;
        document.getElementById('disabled-categories').textContent = disabledCount;
        document.getElementById('trash-categories').textContent = trashCount;
    }

    function countCategoriesStats(categories = [], deletedCategories = []) {
        let activeCount = 0;
        let disabledCount = 0;

        if (categories && categories.length) {
            categories.forEach(category => {
                if (category.disabled == 1) {
                    activeCount++;
                } else {
                    disabledCount++;
                }
            });
        }

        const trashCount = deletedCategories ? deletedCategories.length : 0;
        const totalCount = activeCount + disabledCount;

        updateStatistics(totalCount, activeCount, disabledCount, trashCount);
    }

    document.addEventListener('DOMContentLoaded', function() {
        sendData({
            dataType: 'get_category_stats'
        });
    });

    function showAddNew() {
        $('#addCategoryModal').modal('show').on('shown.bs.modal', function() {
            $('#cat').focus();
        }).on('hidden.bs.modal', function() {
            $('#cat').val('');
            $('#cats').prop('selectedIndex', 0);
        });
    }

    function showEdit(id, category, parent) {
        const categoryInput = $('#e_cat');
        if (id && category) {
            categoryInput.val(category);
            categoryInput.attr('data-id', id);
        }
        if (parent != 0) {
            $('#e_cats').val(parent);
        } else {
            $('#e_cats').prop('selectedIndex', 0);
        }
        $('#editCategoryModal').modal('show').on('shown.bs.modal', function() {
            $('#e_cat').focus();
        }).on('hidden.bs.modal', function() {
            $('#e_cat').val('');
            categoryInput.removeAttr('data-id');
        });
    }

    function sendData(data) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + 'ajaxCategory', true);
        
        let isFormData = data instanceof FormData;
        
        if (!isFormData) {
            xhr.setRequestHeader('Content-Type', 'application/json');
        }
        
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                const dataType = isFormData ? data.get('dataType') : data.dataType;
                handleResult(this.responseText, dataType);
            }
        };
        
        xhr.onerror = function() {
            showNotification('An error occurred while processing the request.', 'danger');
        };
        
        xhr.send(isFormData ? data : JSON.stringify(data));
    }

    function handleResult(result, dataType) {
        try {
            const obj = JSON.parse(result);
            
            if (dataType === 'get_category_stats') {
                if (obj.success) {
                    updateStatistics(
                        obj.statistics.total || 0,
                        obj.statistics.active || 0,
                        obj.statistics.disabled || 0,
                        obj.statistics.trash || 0
                    );
                }
                return;
            }
            
            if (dataType === 'add_category') {
                if (obj.success) {
                    $('#addCategoryModal').modal('hide');
                    showNotification(obj.message, 'success');
                    
                    if (obj.tbl_rows) {
                        document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                    }
                    
                    if (obj.parentOptions) {
                        const parentSelects = document.querySelectorAll("#cats, #e_cats");
                        parentSelects.forEach(select => {
                            select.innerHTML = obj.parentOptions;
                        });
                    }
                    
                    // Update statistics after adding a category
                    sendData({
                        dataType: 'get_category_stats'
                    });
                } else {
                    showNotification(obj.message, 'danger');
                }
            }
            
            else if (dataType === 'edit_category') {
                if (obj.success) {
                    $('#editCategoryModal').modal('hide');
                    showNotification(obj.message, 'success');
                    
                    if (obj.tbl_rows) {
                        document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                    }
                    
                    if (obj.parentOptions) {
                        const parentSelects = document.querySelectorAll("#cats, #e_cats");
                        parentSelects.forEach(select => {
                            select.innerHTML = obj.parentOptions;
                        });
                    }
                    
                    // Update statistics after editing a category
                    sendData({
                        dataType: 'get_category_stats'
                    });
                } else {
                    showNotification(obj.message, 'danger');
                }
            }
            
            else if (dataType === 'get_categories' || dataType === 'change_category_status' || dataType === 'delete_category') {
                if (obj.success) {
                    if (obj.tbl_rows) {
                        document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                    }

                    if (obj.parentOptions) {
                        const parentSelects = document.querySelectorAll("#cats, #e_cats");
                        parentSelects.forEach(select => {
                            select.innerHTML = obj.parentOptions;
                        });
                    }
                    
                    if (obj.message && dataType !== 'get_categories') {
                        showNotification(obj.message, 'success');
                    }
                    
                    // Update statistics after category change or deletion
                    sendData({
                        dataType: 'get_category_stats'
                    });
                } else {
                    showNotification(obj.message, 'danger');
                }
            }
            
            else if (dataType === 'check_trash') {
                if (obj.success) {
                    if (obj.has_deleted_categories) {
                        document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                        showNotification(obj.message, 'info');
                    } else {
                        document.querySelector("#table_body").innerHTML = '<tr><td colspan="5" class="text-center">Trash is empty</td></tr>';
                        showNotification('Trash is empty', 'info');
                    }
                    
                    // Update statistics after checking trash
                    sendData({
                        dataType: 'get_category_stats'
                    });
                } else {
                    showNotification(obj.message, 'danger');
                }
            }
            
            else if (dataType === 'get_deleted_categories') {
                if (obj.success) {
                    document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                    
                    // Update statistics after viewing deleted categories
                    sendData({
                        dataType: 'get_category_stats'
                    });
                } else {
                    showNotification(obj.message, 'danger');
                }
            }
            
            else if (dataType === 'restore_category' || dataType === 'delete_permanent_category') {
                if (obj.success) {
                    document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                    showNotification(obj.message, 'success');
                    
                    // Update statistics after restore or permanent delete
                    sendData({
                        dataType: 'get_category_stats'
                    });
                } else {
                    showNotification(obj.message, 'danger');
                }
            }
            
            else if (dataType === 'search_categories') {
                if (obj.success) {
                    document.querySelector("#table_body").innerHTML = obj.tbl_rows;
                } else {
                    showNotification(obj.message || 'No categories found matching your search.', 'info');
                }
            }
            
        } catch (e) {
            console.error('Error parsing JSON response:', e);
            showNotification('An error occurred while processing the response. Please try again.', 'danger');
        }
    }

    function collectData() {
        const categoryInput = document.querySelector("#cat").value.trim();
        const categoryParentInput = document.querySelector("#cats").value.trim();

        if (!categoryInput) {
            showNotification('Category name cannot be empty.', 'warning');
            return;
        }
        sendData({
            category: categoryInput,
            categoryParent: categoryParentInput,
            dataType: 'add_category'
        });
    }

    function editCategory() {
        const categoryInput = document.querySelector("#e_cat");
        const categoryParentInput = document.querySelector("#e_cats").value.trim();
        const id = categoryInput.getAttribute("data-id");

        if (categoryInput.value.trim() === '') {
            showNotification('Category name cannot be empty.', 'warning');
            return;
        }

        if (categoryParentInput === 'non') {
            categoryParentInput = 0;
        }

        sendData({
            category: categoryInput.value.trim(),
            categoryParent: categoryParentInput,
            id: id,
            dataType: 'edit_category'
        });
    }

    function showConfirmModal(message, callback, btnClass = 'btn-danger', btnIcon = 'fa-check-circle', btnText = 'Confirm') {
        document.getElementById('confirmActionMessage').innerHTML = message;
        const confirmBtn = document.getElementById('confirmActionBtn');

        // Reset button classes and set new ones
        confirmBtn.className = 'btn ' + btnClass;
        confirmBtn.innerHTML = `<i class="fa ${btnIcon}"></i> ${btnText}`;

        // Remove existing event listeners and add new one
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        newConfirmBtn.addEventListener('click', function() {
            callback();
            $('#confirmActionModal').modal('hide');
        });

        $('#confirmActionModal').modal('show');
    }

    function deleteCategory(id) {
        showConfirmModal(
            "This will move the category and its subcategories to the trash. You can restore it later from the Trash section.",
            function() {
                sendData({
                    id: id,
                    dataType: 'delete_category'
                });
            },
            'btn-warning',
            'fa-trash-o',
            'Move to Trash'
        );
    }

    function deletePermanentCategory(id) {
        showConfirmModal(
            "Are you sure you want to permanently delete this category, its subcategories, and all associated products? <strong>This action cannot be undone.</strong>",
            function() {
                sendData({
                    id: id,
                    dataType: 'delete_permanent_category'
                });
            },
            'btn-danger',
            'fa-trash',
            'Delete Permanently'
        );
    }

    function restoreCategory(id) {
        showConfirmModal(
            "This will restore the category, its subcategories, and all associated products.",
            function() {
                sendData({
                    id: id,
                    dataType: 'restore_category'
                });
            },
            'btn-success',
            'fa-undo',
            'Restore'
        );
    }

    function changeCategoryStatus(id) {
        sendData({
            id: id,
            dataType: 'change_category_status'
        });
    }

    let isTrashView = false;

    function refreshCategories() {
        isTrashView = false;
        updateViewControls();
        sendData({
            dataType: 'get_categories'
        });
    }

    function refreshDeletedCategories() {
        isTrashView = true;
        updateViewControls();
        sendData({
            dataType: 'get_deleted_categories'
        });
    }

    function toggleTrashView() {
        isTrashView = !isTrashView;
        updateViewControls();

        // Reset search fields
        const searchInput = document.querySelector("#searchInput");
        const statusFilter = document.querySelector("#statusFilter");
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';

        if (isTrashView) {
            sendData({
                dataType: 'check_trash'
            });
        } else {
            sendData({
                dataType: 'get_categories'
            });
        }
    }

    function updateViewControls() {
        const normalBtn = document.querySelector("#normal_btn").parentNode;
        const deletedBtn = document.querySelector("#deleted_btn").parentNode;

        if (isTrashView) {
            normalBtn.classList.remove('active');
            deletedBtn.classList.add('active');
        } else {
            normalBtn.classList.add('active');
            deletedBtn.classList.remove('active');
        }
    }

    function checkTrash() {
        isTrashView = true;
        updateViewControls();
        sendData({
            dataType: 'check_trash'
        });
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade in`;
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

        notification.innerHTML = `
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa ${getIconForType(type)} mr-2"></i> ${message}
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('in');
            notification.classList.add('out');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    function getIconForType(type) {
        switch (type) {
            case 'success':
                return 'fa-check-circle';
            case 'error':
            case 'danger':
                return 'fa-exclamation-circle';
            case 'warning':
                return 'fa-exclamation-triangle';
            default:
                return 'fa-info-circle';
        }
    }

    // Search functionality
    let searchTimeout = null;
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    function updateFilterButtonsVisibility() {
        const searchHasValue = searchInput.value.trim().length > 0;
        const filtersActive = statusFilter.value !== '';

        clearSearchBtn.style.display = searchHasValue ? 'inline-block' : 'none';
        clearFiltersBtn.style.display = filtersActive ? 'inline-block' : 'none';

        // Update filter status indicator on the status filter dropdown
        if (filtersActive) {
            statusFilter.classList.add('filter-active');

            // Add a badge to indicate which filter is active
            let statusText = statusFilter.options[statusFilter.selectedIndex].text;
            let filterIndicator = document.getElementById('filter-indicator');

            if (!filterIndicator) {
                filterIndicator = document.createElement('span');
                filterIndicator.id = 'filter-indicator';
                filterIndicator.className = 'filter-badge';
                statusFilter.parentNode.appendChild(filterIndicator);
            }

            filterIndicator.textContent = statusText;
        } else {
            statusFilter.classList.remove('filter-active');
            const filterIndicator = document.getElementById('filter-indicator');
            if (filterIndicator) {
                filterIndicator.remove();
            }
        }
    }

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        const status = statusFilter.value;

        updateFilterButtonsVisibility();

        if (searchTerm.length === 0 && status === '') {
            refreshCategories();
            return;
        }

        sendData({
            search: searchTerm,
            status: status,
            dataType: 'search_categories'
        });
    }

    function clearSearch() {
        searchInput.value = '';
        updateFilterButtonsVisibility();
        performSearch();
    }

    function clearFilters() {
        statusFilter.value = '';
        updateFilterButtonsVisibility();
        performSearch();
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 500);
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', performSearch);
    }

    // Initialize buttons visibility
    updateFilterButtonsVisibility();
</script>

<?php
renderFooter($data, 'admin');
?>