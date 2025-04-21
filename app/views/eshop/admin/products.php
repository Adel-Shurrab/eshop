<?php
renderHeader($data, 'admin');
renderSidebar($data, 'admin');
?>

<style type="text/css">
    .add-btn {
        margin-left: 10px;
        padding: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .modal-content {
        background-color: #e9e9e9;
    }

    .modal-header,
    .modal-footer {
        border: none;
    }

    .product_label {
        font-weight: bold;
        color: #686868c2;
    }

    .table-responsive {
        overflow-x: auto;
        min-height: 0.0001%;
        width: 100%;
    }

    @media (max-width: 767px) {

        .table td,
        .table th {
            white-space: nowrap;
            padding: 8px;
        }

        .btn-action {
            margin-bottom: 3px;
        }
    }

    .carousel-inner img {
        max-height: 130px;
        object-fit: cover;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    }

    .carousel-inner {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 12px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
    }

    .modal-body {
        max-height: 500px;
        overflow-y: auto;
    }

    .form-group.has-error .form-control {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .form-group .is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #dc3545;
    }
    
    .help-block {
        display: block;
        margin-top: 5px;
        margin-bottom: 10px;
        color: #dc3545;
        font-size: 85%;
    }

    /* Stats Cards */
    .stats-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 1px 10px rgba(0,0,0,0.05);
        padding: 20px 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        transition: transform 0.2s;
        margin-bottom: 20px;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .stats-card .icon {
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    .stats-card .number {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stats-card .title {
        color: #777;
        font-size: 14px;
    }
    
    .stats-filtered-indicator {
        font-size: 11px;
        color: #5bc0de;
        display: none;
        margin-top: 5px;
        font-style: italic;
    }

    /* Search and filter styles */
    .panel-heading {
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .input-group .input-group-addon {
        background-color: #f8f9fa;
        border-color: #ddd;
        color: #666;
    }

    .input-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .input-group select.form-control {
        padding-right: 25px;
    }

    #clearFiltersBtn {
        transition: all 0.3s ease;
    }

    #clearFiltersBtn:hover {
        background-color: #e9ecef;
    }

    .filter-active {
        background-color: #e3f2fd !important;
        border-color: #90caf9 !important;
    }

    #searchInput::placeholder {
        color: #999;
        font-style: italic;
    }

    .gap-2 > * {
        margin-right: 8px;
        margin-bottom: 8px;
    }
</style>

<!-- MAIN CONTENT -->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cube"></i> Product Management Dashboard</h3>
        
        <!-- Statistics Row -->
        <div class="row mt">
            <div class="col-lg-1"></div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-cubes text-success"></i>
                    </div>
                    <div class="number" id="total-products">--</div>
                    <div class="title">Total Products</div>
                    <div class="stats-filtered-indicator" id="stats-filtered-message">Showing filtered results</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-check-circle text-primary"></i>
                    </div>
                    <div class="number" id="in-stock-products">--</div>
                    <div class="title">In Stock (>5)</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-exclamation-triangle text-warning"></i>
                    </div>
                    <div class="number" id="almost-out-products">--</div>
                    <div class="title">Almost Out (1-5)</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-times-circle text-danger"></i>
                    </div>
                    <div class="number" id="out-of-stock-products">--</div>
                    <div class="title">Out of Stock (0)</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-trash-o text-muted"></i>
                    </div>
                    <div class="number" id="trash-products">--</div>
                    <div class="title">Deleted Products</div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
        
        <div class="row mt">
            <div class="col-lg-12">
                <div class="row mt">
                    <div class="col-md-12">
                        <div class="content-panel">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="mb-3" id="table_title">
                                            <i class="fa fa-list"></i> Active Products
                                        </h4>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-xs add-btn" onclick="showAddNew()">
                                                <i class="fa fa-plus-circle"></i> New Item
                                            </button>
                                            <button class="btn btn-secondary btn-xs add-btn" onclick="refreshProducts()">
                                                <i class="fa fa-refresh"></i> Refresh List
                                            </button>
                                            <button class="btn btn-secondary btn-xs add-btn" id="deleted_btn" onclick="checkTrash()">
                                                <i class="fa fa-trash-o"></i> Trash
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control" id="searchInput" placeholder="Search by ID, name...">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" onclick="clearSearch()" id="clearSearchBtn" style="display: none;">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2" style="display: flex; flex-wrap: wrap;">
                                            <div class="input-group" style="width: auto;">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-cubes"></i>
                                                </span>
                                                <select class="form-control" id="stockFilter" style="width: 170px;">
                                                    <option value="">All Stock</option>
                                                    <option value="in_stock">In Stock (>5)</option>
                                                    <option value="almost_out_of_stock">Almost Out (1-5)</option>
                                                    <option value="out_of_stock">Out of Stock (0)</option>
                                                </select>
                                            </div>
                                            <div class="input-group" style="width: auto;">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </span>
                                                <input type="number" min="0" step="0.01" class="form-control" id="minPriceFilter" placeholder="Min Price" style="width: 110px;">
                                            </div>
                                            <div class="input-group" style="width: auto;">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-dollar"></i>
                                                </span>
                                                <input type="number" min="0" step="0.01" class="form-control" id="maxPriceFilter" placeholder="Max Price" style="width: 110px;">
                                            </div>
                                            <button class="btn btn-default" type="button" onclick="clearFilters()" id="clearFiltersBtn" style="display: none;">
                                                <i class="fa fa-filter"></i> Clear Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add New Product Modal -->
                            <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="addProductModalLabel">
                                                <i class="fa fa-plus-circle"></i> Create New Product Entry
                                            </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addProductForm" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="product_label">
                                                        <i class="fa fa-tag"></i> Item Name:
                                                    </label>
                                                    <textarea name="description" id="description" class="form-control" style="resize: none" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="product_label">
                                                        <i class="fa fa-folder-open"></i> Product Category:
                                                    </label>
                                                    <select id="cats" name="category" class="form-control" required>
                                                        <?= $data['parentOptions'] ?>
                                                    </select>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-dollar"></i> Unit Price:
                                                        </label>
                                                        <input type="number" id="price" name="price" class="form-control" min="0.01" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-cubes"></i> Stock Quantity:
                                                        </label>
                                                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" max="1000" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-picture-o"></i> Primary Image:
                                                        </label>
                                                        <input type="file" id="product_primary_image" name="product_primary_image" class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-image"></i> Additional Image 1:
                                                        </label>
                                                        <input type="file" id="img2" name="img2" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-image"></i> Additional Image 2:
                                                        </label>
                                                        <input type="file" id="img3" name="img3" class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-image"></i> Additional Image 3:
                                                        </label>
                                                        <input type="file" id="img4" name="img4" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                <i class="fa fa-times"></i> Cancel
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="collectData()">
                                                <i class="fa fa-save"></i> Save Entry
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Product Modal -->
                            <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="editProductModalLabel">
                                                <i class="fa fa-edit"></i> Modify Product Details
                                            </h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editProductForm" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="product_label">
                                                        <i class="fa fa-tag"></i> Item Name:
                                                    </label>
                                                    <textarea name="e_description" id="e_description" class="form-control" style="resize: none" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label class="product_label">
                                                        <i class="fa fa-folder-open"></i> Product Category:
                                                    </label>
                                                    <select id="e_cats" name="e_category" class="form-control" required>
                                                        <?= $data['parentOptions'] ?>
                                                    </select>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-dollar"></i> Unit Price:
                                                        </label>
                                                        <input type="number" id="e_price" name="e_price" class="form-control" min="0.01" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-cubes"></i> Stock Quantity:
                                                        </label>
                                                        <input type="number" id="e_quantity" name="e_quantity" class="form-control" min="1" max="1000" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-picture-o"></i> Primary Image:
                                                        </label>
                                                        <input type="file" id="e_product_primary_image" name="e_img1" class="form-control" accept="image/*" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-image"></i> Additional Image 1:
                                                        </label>
                                                        <input type="file" id="e_img2" name="e_img2" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-image"></i> Additional Image 2:
                                                        </label>
                                                        <input type="file" id="e_img3" name="e_img3" class="form-control" accept="image/*">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="product_label">
                                                            <i class="fa fa-image"></i> Additional Image 3:
                                                        </label>
                                                        <input type="file" id="e_img4" name="e_img4" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="productImagesCarousel" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner" id="carouselInner">
                                                    <!-- Carousel items will be added dynamically -->
                                                </div>
                                                <a class="carousel-control-prev" href="#productImagesCarousel" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#productImagesCarousel" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                <i class="fa fa-times"></i> Discard Changes
                                            </button>
                                            <button type="button" class="btn btn-primary" onclick="editProduct()">
                                                <i class="fa fa-floppy-o"></i> Update Record
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add this right after the edit product modal -->
                            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmationModalLabel"><i class="fa fa-question-circle"></i> Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p id="confirmationMessage"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirmAction"><i class="fa fa-check"></i> Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add styling for the confirmation modal -->
                            <style>
                                #confirmationModal .modal-content {
                                    border-radius: 5px;
                                    box-shadow: 0 5px 15px rgba(0,0,0,.5);
                                }
                                
                                #confirmationModal .modal-header {
                                    background-color: #f8f9fa;
                                    border-bottom: 1px solid #e9ecef;
                                    border-top-left-radius: 5px;
                                    border-top-right-radius: 5px;
                                }
                                
                                #confirmationModal .modal-footer {
                                    border-top: 1px solid #e9ecef;
                                    background-color: #f8f9fa;
                                    border-bottom-left-radius: 5px;
                                    border-bottom-right-radius: 5px;
                                }
                                
                                #confirmationMessage {
                                    font-size: 16px;
                                    margin-bottom: 0;
                                    padding: 10px 0;
                                }
                            </style>

                            <div class="table-responsive">
                                <table class="table table-striped table-advance table-hover mt-3">
                                    <thead>
                                        <tr>
                                            <th><i class="fa fa-id-card"></i> Product ID</th>
                                            <th><i class="fa fa-cube"></i> Item Name</th>
                                            <th><i class="fa fa-folder"></i> Category</th>
                                            <th><i class="fa fa-money"></i> Price</th>
                                            <th><i class="fa fa-layer-group"></i> Stock</th>
                                            <th><i class="fa fa-photo"></i> Media</th>
                                            <th><i class="fa fa-clock-o"></i> Last Updated</th>
                                            <th><i class="fa fa-calendar"></i> Deleted At</th>
                                            <th><i class="fa fa-cogs"></i> Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_body">
                                        <?php if ($data['tbl_rows'] === ''): ?>
                                            <tr>
                                                <td colspan="9" class="text-center">No products found</td>
                                            </tr>
                                        <?php else : ?>
                                            <?= $data['tbl_rows'] ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <?= $data['pagination'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<script type="text/javascript">
    // For storing callback and data for confirmation
    let confirmationCallback = null;
    let confirmationData = null;
    let isTrashView = false; // To track if we're in trash view

    // Show custom confirmation dialog
    function showConfirmation(message, callback, data = null, confirmBtnClass = 'btn-danger', confirmBtnText = 'Confirm', confirmIconClass = 'fa-check') {
        document.getElementById('confirmationMessage').textContent = message;
        
        const confirmButton = document.getElementById('confirmAction');
        confirmButton.className = `btn ${confirmBtnClass}`;
        confirmButton.innerHTML = `<i class="fa ${confirmIconClass}"></i> ${confirmBtnText}`;
        
        // Set appropriate title icon based on the action type
        let titleIconClass = 'fa-question-circle';
        if (confirmBtnClass.includes('danger')) {
            titleIconClass = 'fa-exclamation-triangle';
        } else if (confirmBtnClass.includes('success')) {
            titleIconClass = 'fa-check-circle';
        } else if (confirmBtnClass.includes('warning')) {
            titleIconClass = 'fa-exclamation-circle';
        }
        
        document.querySelector('#confirmationModalLabel i').className = `fa ${titleIconClass}`;
        
        confirmationCallback = callback;
        confirmationData = data;
        
        $('#confirmationModal').modal('show');
    }

    // Set up confirmation action button
    document.getElementById('confirmAction').addEventListener('click', function() {
        $('#confirmationModal').modal('hide');
        if (typeof confirmationCallback === 'function') {
            confirmationCallback(confirmationData);
        }
    });

    // Display notification messages
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
    
    // Clear all error messages and validation styles
    function clearErrors(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        // Clear validation classes from inputs
        const inputs = form.querySelectorAll('.is-invalid');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            // Also remove has-error from parent form-group
            const formGroup = input.closest('.form-group');
            if (formGroup) {
                formGroup.classList.remove('has-error');
            }
        });
        
        // Also clear any form-groups with has-error
        const errorGroups = form.querySelectorAll('.form-group.has-error');
        errorGroups.forEach(group => {
            group.classList.remove('has-error');
        });
        
        // Remove error messages
        const errorMessages = form.querySelectorAll('.invalid-feedback, .help-block');
        errorMessages.forEach(el => {
            el.remove();
        });
    }
    
    // Display validation errors
    function displayErrors(errors, formId) {
        const form = document.getElementById(formId);
        if (!form || !errors) return;
        
        // Clear previous errors
        clearErrors(formId);
        
        // Process each error
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"], #${field}`);
            if (input) {
                // Add invalid class to the input
                input.classList.add('is-invalid');
                
                // Add has-error class to parent form-group
                const formGroup = input.closest('.form-group');
                if (formGroup) {
                    formGroup.classList.add('has-error');
                }
                
                // Create error message
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = errors[field];
                
                // Insert after input
                input.parentNode.insertBefore(feedback, input.nextSibling);
            }
        });
    }

    function showAddNew() {
        clearErrors('addProductForm');
        $('#addProductModal').modal('show').on('shown.bs.modal', function() {
            $('#description').focus();
        }).on('hidden.bs.modal', function() {
            $('#description').val('');
            $('#cats').prop('selectedIndex', 0);
            $('#price').val('');
            $('#quantity').val('');
            $('#product_primary_image').val('');
            $('#img2').val('');
            $('#img3').val('');
            $('#img4').val('');
        });
    }

    function showEdit(id, description, category, price, quantity, image, image2, image3, image4) {
        clearErrors('editProductForm');
        var descriptionInput = $('#e_description');
        var categoryInput = $('#e_cats');
        var priceInput = $('#e_price');
        var quantityInput = $('#e_quantity');
        var carouselInner = $('#carouselInner');

        if (id && description && category && price && quantity) {
            descriptionInput.val(description);
            categoryInput.val(category);
            priceInput.val(price);
            quantityInput.val(quantity);

            // Clear existing carousel items
            carouselInner.empty();

            // Add primary image
            if (image) {
                carouselInner.append(`
                <div class="carousel-item active">
                    <img src="<?= UPLOADS_URL ?>${image}" class="d-block w-100" alt="Primary Image">
                </div>
            `);
            }

            // Add additional images
            if (image2) {
                carouselInner.append(`
                <div class="carousel-item">
                    <img src="<?= UPLOADS_URL ?>${image2}" class="d-block w-100" alt="Additional Image 1">
                </div>
            `);
            }
            if (image3) {
                carouselInner.append(`
                <div class="carousel-item">
                    <img src="<?= UPLOADS_URL ?>${image3}" class="d-block w-100" alt="Additional Image 2">
                </div>
            `);
            }
            if (image4) {
                carouselInner.append(`
                <div class="carousel-item">
                    <img src="<?= UPLOADS_URL ?>${image4}" class="d-block w-100" alt="Additional Image 3">
                </div>
            `);
            }

            descriptionInput.attr('data-id', id);
        }

        $('#editProductModal').modal('show').on('shown.bs.modal', function() {
            $('#e_description').focus();
        }).on('hidden.bs.modal', function() {
            $('#e_description').val('');
            $('#e_cats').prop('selectedIndex', 0);
            $('#e_price').val('');
            $('#e_quantity').val('');
            $('#e_product_primary_image').val('');
            $('#e_img2').val('');
            $('#e_img3').val('');
            $('#e_img4').val('');
            descriptionInput.removeAttr('data-id');
        });
    }

    function sendData(data) {
        var ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function() {
            if (ajax.readyState == 4) {
                if (ajax.status == 200) {
                    handleResult(ajax.responseText, data.get('dataType'));
                } else {
                    console.error('An error occurred while processing the request:', ajax.statusText);
                    showNotification('An error occurred while processing the request.', 'danger');
                }
            }
        });

        ajax.open("POST", "<?= BASE_URL ?>ajaxProduct", true);
        ajax.send(data);
    }

    function handleResult(result, dataType) {
        try {
            const obj = JSON.parse(result);
            
            // Parse validation errors if they exist
            if (obj.errors) {
                const formId = dataType === 'add_product' ? 'addProductForm' : 'editProductForm';
                displayErrors(obj.errors, formId);
            }
            
            // Show appropriate message based on success/failure
            if (obj.message) {
                if (obj.success) {
                    // Success message
                    showNotification(obj.message, 'success');
                    
                    // Close modals if needed
                    if (dataType === 'add_product') {
                        $('#addProductModal').modal('hide');
                    } else if (dataType === 'edit_product') {
                        $('#editProductModal').modal('hide');
                    }
                } else {
                    // Error message
                    showNotification(obj.message, 'danger');
                    return; // Don't proceed with other UI updates
                }
            }
            
            // Update the table if rows were provided
            if (obj.tbl_rows) {
                document.querySelector("#table_body").innerHTML = obj.tbl_rows;
            }

            // Handle search results statistics
            if (dataType === 'search_products') {
                // Always update statistics for search results
                if (obj.products && Array.isArray(obj.products)) {
                    if (obj.products.length > 0) {
                        // Calculate counts for filtered products
                        let inStockCount = 0;
                        let outOfStockCount = 0;
                        let almostOutCount = 0;
                        
                        obj.products.forEach(product => {
                            const quantity = parseInt(product.quantity);
                            if (quantity > 5) {
                                inStockCount++;
                            } else if (quantity > 0) {
                                almostOutCount++;
                            } else {
                                outOfStockCount++;
                            }
                        });
                        
                        // For filtered results, maintain trash count
                        const currentTrashCount = document.getElementById('trash-products').textContent;
                        
                        updateStatistics(
                            obj.products.length,
                            inStockCount,
                            outOfStockCount,
                            currentTrashCount,
                            true, // Indicate these are filtered results
                            almostOutCount // Pass the almost out count
                        );
                    } else {
                        // Zero counts for empty results
                        updateStatistics(
                            0,
                            0,
                            0,
                            document.getElementById('trash-products').textContent,
                            true, // Indicate these are filtered results
                            0  // Almost out count
                        );
                    }
                }
                return; // Don't process further statistics updates
            }

            // Update statistics if stats were provided
            if (dataType === 'get_product_stats' && obj.statistics) {
                updateStatistics(
                    obj.statistics.total || 0,
                    obj.statistics.in_stock || 0,
                    obj.statistics.out_of_stock || 0,
                    obj.statistics.trash || 0,
                    false // Not filtered
                );
            }
            
            // After any data-changing operation, refresh stats
            if (['add_product', 'edit_product', 'delete_product', 'delete_permanent_product', 'restore_product'].includes(dataType)) {
                getProductStats();
            }

            // Update categories if parent options were provided
            if (obj.parentOptions) {
                document.querySelector("#cats").innerHTML = obj.parentOptions;
                document.querySelector("#e_cats").innerHTML = obj.parentOptions;
            }
            
            // Handle trash-specific response
            if (dataType === 'check_trash' && !obj.has_deleted_products) {
                showNotification('Trash is empty', 'info');
            }
        } catch (e) {
            console.error('Error parsing JSON response:', e);
            showNotification('An error occurred while processing the server response.', 'danger');
        }
    }

    function collectData() {
        var descriptionInput = document.querySelector("#description").value.trim();
        var categoryInput = document.querySelector("#cats").value.trim();
        var priceInput = document.querySelector("#price").value.trim();
        var quantityInput = document.querySelector("#quantity").value.trim();
        var img1Input = document.querySelector("#product_primary_image").files[0];
        var img2Input = document.querySelector("#img2").files[0];
        var img3Input = document.querySelector("#img3").files[0];
        var img4Input = document.querySelector("#img4").files[0];

        // Clear previous errors
        clearErrors('addProductForm');
        
        // Basic client-side validation
        let hasErrors = false;
        let errors = {};
        
        if (!descriptionInput) {
            errors.description = 'Product description cannot be empty.';
            hasErrors = true;
        }
        
        if (!categoryInput) {
            errors.cats = 'Product category cannot be empty.';
            hasErrors = true;
        }
        
        if (!priceInput || isNaN(parseFloat(priceInput)) || parseFloat(priceInput) <= 0) {
            errors.price = 'Product price must be a positive number.';
            hasErrors = true;
        }
        
        if (!quantityInput || isNaN(parseInt(quantityInput)) || parseInt(quantityInput) <= 0) {
            errors.quantity = 'Product quantity must be a positive integer.';
            hasErrors = true;
        }
        
        if (!img1Input) {
            errors.product_primary_image = 'Primary image cannot be empty.';
            hasErrors = true;
        }
        
        // Show errors if any
        if (hasErrors) {
            displayErrors(errors, 'addProductForm');
            return;
        }

        // Create FormData object
        var data = new FormData();
        data.append('description', descriptionInput);
        data.append('category', categoryInput);
        data.append('price', priceInput);
        data.append('quantity', quantityInput);
        data.append('product_primary_image', img1Input);

        if (img2Input) {
            data.append('img2', img2Input);
        }

        if (img3Input) {
            data.append('img3', img3Input);
        }

        if (img4Input) {
            data.append('img4', img4Input);
        }

        data.append('dataType', 'add_product');

        // Send data
        sendData(data);
    }

    function editProduct() {
        // Clear previous errors
        clearErrors('editProductForm');
        
        var formData = new FormData();
        var descriptionInput = document.querySelector("textarea[name='e_description']").value.trim();
        var categoryInput = document.querySelector("select[name='e_category']").value.trim();
        var priceInput = document.querySelector("#e_price").value.trim();
        var quantityInput = document.querySelector("#e_quantity").value.trim();
        var img1Input = document.querySelector("#e_product_primary_image").files[0];
        var img2Input = document.querySelector("#e_img2").files[0];
        var img3Input = document.querySelector("#e_img3").files[0];
        var img4Input = document.querySelector("#e_img4").files[0];
        var id = document.querySelector("#e_description").getAttribute("data-id");

        // Basic client-side validation
        let hasErrors = false;
        let errors = {};
        
        if (!descriptionInput) {
            errors.e_description = 'Product description cannot be empty.';
            hasErrors = true;
        }
        
        if (!categoryInput) {
            errors.e_cats = 'Product category cannot be empty.';
            hasErrors = true;
        }
        
        if (!priceInput || isNaN(parseFloat(priceInput)) || parseFloat(priceInput) <= 0) {
            errors.e_price = 'Product price must be a positive number.';
            hasErrors = true;
        }
        
        if (!quantityInput || isNaN(parseInt(quantityInput)) || parseInt(quantityInput) <= 0) {
            errors.e_quantity = 'Product quantity must be a positive integer.';
            hasErrors = true;
        }
        
        // Show errors if any
        if (hasErrors) {
            displayErrors(errors, 'editProductForm');
            return;
        }

        formData.append('id', id);
        formData.append('description', descriptionInput);
        formData.append('category', categoryInput);
        formData.append('price', priceInput);
        formData.append('quantity', quantityInput);
        
        if (img1Input) {
            formData.append('product_primary_image', img1Input);
        }

        if (img2Input) {
            formData.append('img2', img2Input);
        }

        if (img3Input) {
            formData.append('img3', img3Input);
        }

        if (img4Input) {
            formData.append('img4', img4Input);
        }

        formData.append('dataType', 'edit_product');

        sendData(formData);
    }

    function deleteProduct(id) {
        showConfirmation(
            'This will hide the product. You can restore it later from the Trash.',
            function(productId) {
                var formData = new FormData();
                formData.append('id', productId);
                formData.append('dataType', 'delete_product');
                sendData(formData);
            },
            id,
            'btn-warning',
            'Move to Trash',
            'fa-trash'
        );
    }

    function deletePermanentProduct(id) {
        showConfirmation(
            'Are you sure you want to permanently delete this product? This action cannot be undone.',
            function(productId) {
                var formData = new FormData();
                formData.append('id', productId);
                formData.append('dataType', 'delete_permanent_product');
                sendData(formData);
            },
            id,
            'btn-danger',
            'Delete Permanently',
            'fa-trash'
        );
    }

    function refreshProducts() {
        // Hide the filtered indicator
        document.getElementById('stats-filtered-message').style.display = 'none';
        isTrashView = false;
        
        // Update the table title and button
        document.getElementById('table_title').innerHTML = '<i class="fa fa-list"></i> Active Products';
        const trashBtn = document.getElementById('deleted_btn');
        trashBtn.classList.remove('btn-warning');
        trashBtn.classList.add('btn-secondary');
        trashBtn.innerHTML = '<i class="fa fa-trash-o"></i> Trash';
        
        var formData = new FormData();
        formData.append('dataType', 'get_products');
        sendData(formData);
    }

    function refreshDeletedProducts() {
        var formData = new FormData();
        formData.append('dataType', 'get_deleted_products');
        sendData(formData);
    }

    function restoreProduct(id) {
        showConfirmation(
            'This will restore the product to the active list.',
            function(productId) {
                var formData = new FormData();
                formData.append('id', productId);
                formData.append('dataType', 'restore_product');
                sendData(formData);
            },
            id,
            'btn-success',
            'Restore',
            'fa-undo'
        );
    }

    function checkTrash() {
        // Toggle trash view state
        isTrashView = !isTrashView;
        
        // Update UI based on trash view state
        const trashBtn = document.getElementById('deleted_btn');
        const tableTitle = document.getElementById('table_title');
        
        if (isTrashView) {
            // Moving to trash view
            trashBtn.classList.remove('btn-secondary');
            trashBtn.classList.add('btn-warning');
            trashBtn.innerHTML = '<i class="fa fa-list"></i> View Active Products';
            tableTitle.innerHTML = '<i class="fa fa-trash-o"></i> Deleted Products';
            
            // Check trash contents
            var formData = new FormData();
            formData.append('dataType', 'check_trash');
            sendData(formData);
        } else {
            // Moving back to active products view
            trashBtn.classList.remove('btn-warning');
            trashBtn.classList.add('btn-secondary');
            trashBtn.innerHTML = '<i class="fa fa-trash-o"></i> Trash';
            tableTitle.innerHTML = '<i class="fa fa-list"></i> Active Products';
            
            // Refresh products list
            refreshProducts();
        }
    }
    
    // Search products function
    function searchProducts() {
        const searchInput = document.getElementById('searchInput');
        const stockFilter = document.getElementById('stockFilter');
        const minPriceFilter = document.getElementById('minPriceFilter');
        const maxPriceFilter = document.getElementById('maxPriceFilter');
        
        // Create form data
        const formData = new FormData();
        formData.append('search', searchInput.value.trim());
        formData.append('status', stockFilter.value);
        
        // Add price range filters if they have values
        if (minPriceFilter.value) {
            formData.append('minPrice', minPriceFilter.value);
        }
        
        if (maxPriceFilter.value) {
            formData.append('maxPrice', maxPriceFilter.value);
        }
        
        formData.append('dataType', 'search_products');
        
        // Show clear button if search has text
        if (searchInput.value.trim()) {
            document.getElementById('clearSearchBtn').style.display = 'block';
        }
        
        // Highlight active filters
        highlightActiveFilters();
        
        // Show clear filters button if any filters are active
        if (isAnyFilterActive()) {
            document.getElementById('clearFiltersBtn').style.display = 'inline-block';
        }
        
        // Send search request
        sendData(formData);
    }
    
    // Check if any filter is active
    function isAnyFilterActive() {
        return document.getElementById('stockFilter').value || 
               document.getElementById('minPriceFilter').value ||
               document.getElementById('maxPriceFilter').value;
    }
    
    // Highlight active filters
    function highlightActiveFilters() {
        const filters = [
            document.getElementById('stockFilter'),
            document.getElementById('minPriceFilter'),
            document.getElementById('maxPriceFilter')
        ];
        
        filters.forEach(filter => {
            if (filter.value) {
                filter.classList.add('filter-active');
            } else {
                filter.classList.remove('filter-active');
            }
        });
    }
    
    // Clear search function
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('clearSearchBtn').style.display = 'none';
        
        // Search with just the filters
        searchProducts();
    }
    
    // Clear filters function
    function clearFilters() {
        // Reset filter selects
        document.getElementById('stockFilter').value = '';
        document.getElementById('minPriceFilter').value = '';
        document.getElementById('maxPriceFilter').value = '';
        
        // Remove active class
        document.querySelectorAll('#stockFilter, #minPriceFilter, #maxPriceFilter').forEach(field => {
            field.classList.remove('filter-active');
        });
        
        // Hide clear filters button
        document.getElementById('clearFiltersBtn').style.display = 'none';
        
        // Search with just the search text, if any
        searchProducts();
    }
    
    // Update statistics counters
    function updateStatistics(totalCount = 0, inStockCount = 0, outOfStockCount = 0, trashCount = 0, isFiltered = false, almostOutCount = null) {
        // Calculate almost out of stock if not provided
        const almostOutOfStockCount = almostOutCount !== null ? almostOutCount : 
            Math.max(0, totalCount - inStockCount - outOfStockCount);
        
        document.getElementById('total-products').textContent = totalCount;
        document.getElementById('in-stock-products').textContent = inStockCount;
        document.getElementById('almost-out-products').textContent = almostOutOfStockCount;
        document.getElementById('out-of-stock-products').textContent = outOfStockCount;
        document.getElementById('trash-products').textContent = trashCount;
        
        // Show/hide the filtered indicator
        const filteredIndicator = document.getElementById('stats-filtered-message');
        if (filteredIndicator) {
            filteredIndicator.style.display = isFiltered ? 'block' : 'none';
        }
    }
    
    // Get product statistics
    function getProductStats() {
        const formData = new FormData();
        formData.append('dataType', 'get_product_stats');
        sendData(formData);
    }
    
    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for search input (search as you type with debounce)
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            // Use input event for real-time searching with debouncing
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                // Show/hide clear button based on input value
                document.getElementById('clearSearchBtn').style.display = 
                    searchInput.value.trim() ? 'block' : 'none';
                
                // Debounce the search with a 300ms delay
                searchTimeout = setTimeout(function() {
                    searchProducts();
                }, 300);
            });
        }
        
        // Add event listeners for all filters to search in real-time
        const filters = [
            document.getElementById('stockFilter'),
            document.getElementById('minPriceFilter'),
            document.getElementById('maxPriceFilter')
        ];
        
        filters.forEach(filter => {
            if (filter.tagName === 'SELECT') {
                // For select elements
                filter.addEventListener('change', searchProducts);
            } else {
                // For input elements (price filters) with debounce
                let timeout;
                filter.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(searchProducts, 300);
                });
            }
        });
        
        // Load initial statistics
        getProductStats();
    });
</script>

<?php
renderFooter($data, 'admin');
?>