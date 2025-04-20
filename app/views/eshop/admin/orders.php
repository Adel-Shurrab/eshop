<?php
renderHeader($data, 'admin');
renderSidebar($data, 'admin');
?>

<style>
    .order-status {
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-pending {
        background-color: #f0ad4e;
        color: #fff;
    }

    .status-processing {
        background-color: #5bc0de;
        color: #fff;
    }

    .status-shipped,
    .status-delivered {
        background-color: #5cb85c;
        color: #fff;
    }

    .status-cancelled {
        background-color: #d9534f;
        color: #fff;
    }

    .payment-paid {
        color: #5cb85c;
    }

    .payment-unpaid {
        color: #f0ad4e;
    }

    .payment-failed {
        color: #d9534f;
    }

    .filter-active {
        border: 1px solid #5cb85c;
    }
</style>

<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-shopping-cart"></i> Manage Orders</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="content-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                        <input type="text" class="form-control" id="searchInput" placeholder="Search by Order ID, customer name, phone...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" onclick="clearSearch()" id="clearSearchBtn" style="display: none;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2" style="display: flex; flex-wrap: wrap;">
                                    <div class="input-group" style="width: auto;">
                                        <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                                        <select class="form-control" id="statusFilter" style="width: 130px;">
                                            <option value="">All Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="input-group" style="width: auto;">
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                        <select class="form-control" id="paymentFilter" style="width: 130px;">
                                            <option value="">All Payments</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-default" type="button" onclick="clearFilters()" id="clearFiltersBtn" style="display: none;">
                                        <i class="fa fa-filter"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Statistics -->
                    <div class="row order-stats" style="margin: 15px 5px;">
                        <div class="col-md-3">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Total Orders</h3>
                                </div>
                                <div class="panel-body" id="totalOrders">
                                    <h3><?= $data['total_orders'] ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-clock-o"></i> Pending</h3>
                                </div>
                                <div class="panel-body" id="pendingOrders">
                                    <h3><?= $data['pending_orders'] ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-check-circle"></i> Completed</h3>
                                </div>
                                <div class="panel-body" id="completedOrders">
                                    <h3><?= $data['completed_orders'] ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><i class="fa fa-ban"></i> Cancelled</h3>
                                </div>
                                <div class="panel-body" id="cancelledOrders">
                                    <h3><?= $data['cancelled_orders'] ?? 0 ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-hashtag"></i> Order ID</th>
                                    <th><i class="fa fa-calendar"></i> Date</th>
                                    <th><i class="fa fa-user"></i> Customer</th>
                                    <th><i class="fa fa-money"></i> Total</th>
                                    <th><i class="fa fa-truck"></i> Status</th>
                                    <th><i class="fa fa-credit-card"></i> Payment</th>
                                    <th><i class="fa fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <?php if (!empty($data['orders'])): ?>
                                    <?php foreach ($data['orders'] as $order): ?>
                                        <tr>
                                            <td>#<?= htmlspecialchars($order['id']) ?></td>
                                            <td><?= htmlspecialchars(date('M j, Y H:i', strtotime($order['date']))) ?></td>
                                            <td><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?></td>
                                            <td>$<?= number_format($order['total'], 2) ?></td>
                                            <td>
                                                <span class="order-status status-<?= htmlspecialchars($order['status']) ?>">
                                                    <?= ucfirst(htmlspecialchars($order['status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="payment-<?= htmlspecialchars($order['payment_status']) ?>">
                                                    <?= ucfirst(htmlspecialchars($order['payment_status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-xs view-order-details" data-id="<?= $order['id'] ?>">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-shopping-cart"></i> Order Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Order Information</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Order ID:</th>
                                <td id="order-id"></td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td id="order-date"></td>
                            </tr>
                            <tr>
                                <th>Customer:</th>
                                <td id="order-customer"></td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td id="order-total"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Status Information</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Order Status:</th>
                                <td>
                                    <select id="order-status" class="form-control">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Status:</th>
                                <td>
                                    <select id="payment-status" class="form-control">
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button id="update-status-btn" class="btn btn-primary btn-block">
                                        <i class="fa fa-refresh"></i> Update Status
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h4>Order Items</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="order-items">
                        </tbody>
                    </table>
                </div>

                <div id="customer-details">
                    <h4>Customer Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Contact Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name:</th>
                                    <td id="customer-name"></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td id="customer-email"></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td id="customer-phone"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Shipping Address</h5>
                            <p id="customer-address"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const paymentFilter = document.getElementById('paymentFilter');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');

        function updateFilterButtonsVisibility() {
            clearSearchBtn.style.display = searchInput.value.trim() ? 'inline-block' : 'none';
            clearFiltersBtn.style.display = (statusFilter.value || paymentFilter.value) ? 'inline-block' : 'none';
        }

        function performSearch() {
            const searchTerm = searchInput.value.trim();
            const status = statusFilter.value;
            const payment = paymentFilter.value;

            updateFilterButtonsVisibility();

            const formData = new FormData();
            formData.append('dataType', 'search_orders');
            formData.append('search', searchTerm);
            formData.append('status', status);
            formData.append('payment', payment);

            fetch('<?= BASE_URL ?>ajaxOrder', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('ordersTableBody').innerHTML = data.table_html;
                        attachViewOrderListeners();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Load and display order details
        function loadOrderDetails(orderId) {
            const formData = new FormData();
            formData.append('dataType', 'get_order_details');
            formData.append('id', orderId);

            fetch('<?= BASE_URL ?>ajaxOrder', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.order) {
                        const order = data.order;

                        // Fill order information
                        document.getElementById('order-id').textContent = '#' + order.id;
                        document.getElementById('order-date').textContent = new Date(order.date).toLocaleString();
                        document.getElementById('order-customer').textContent = order.user_name || 'Guest';
                        document.getElementById('order-total').textContent = '$' + parseFloat(order.total).toFixed(2);

                        // Set current status values
                        document.getElementById('order-status').value = order.status;
                        document.getElementById('payment-status').value = order.payment_status;

                        // Fill customer details
                        document.getElementById('customer-name').textContent = order.user_name || 'Guest';
                        document.getElementById('customer-email').textContent = order.email || 'N/A';
                        document.getElementById('customer-phone').textContent = order.phone || 'N/A';
                        document.getElementById('customer-address').textContent = [
                            order.address || '',
                            order.city || '',
                            order.state || '',
                            order.zip_code || '',
                            order.country || ''
                        ].filter(Boolean).join(', ') || 'N/A';

                        // Fill order items
                        const itemsContainer = document.getElementById('order-items');
                        itemsContainer.innerHTML = '';

                        if (order.items && order.items.length > 0) {
                            order.items.forEach(item => {
                                const row = document.createElement('tr');
                                const itemTotal = parseFloat(item.price) * parseInt(item.quantity);

                                row.innerHTML = `
                                <td>
                                    <a href="<?= BASE_URL ?>productDetails/${item.slag}" target="_blank">
                                        ${item.product_description || item.product_id}
                                    </a>
                                </td>
                                <td>${item.quantity}</td>
                                <td>$${parseFloat(item.price).toFixed(2)}</td>
                                <td>$${itemTotal.toFixed(2)}</td>
                            `;

                                itemsContainer.appendChild(row);
                            });
                        } else {
                            itemsContainer.innerHTML = '<tr><td colspan="4" class="text-center">No items found</td></tr>';
                        }

                        // Set update button handler
                        document.getElementById('update-status-btn').onclick = function() {
                            updateOrderStatus(order.id);
                        };

                        // Show the modal
                        $('#orderDetailsModal').modal('show');
                    } else {
                        alert('Failed to load order details');
                    }
                })
                .catch(error => {
                    console.error('Error loading order details:', error);
                    alert('An error occurred while loading order details');
                });
        }

        // Update order status
        function updateOrderStatus(orderId) {
            const orderStatus = document.getElementById('order-status').value;
            const paymentStatus = document.getElementById('payment-status').value;

            // First update order status
            const orderFormData = new FormData();
            orderFormData.append('dataType', 'change_order_status');
            orderFormData.append('id', orderId);
            orderFormData.append('status', orderStatus);

            fetch('<?= BASE_URL ?>ajaxOrder', {
                    method: 'POST',
                    body: orderFormData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Then update payment status
                        const paymentFormData = new FormData();
                        paymentFormData.append('dataType', 'change_payment_status');
                        paymentFormData.append('id', orderId);
                        paymentFormData.append('status', paymentStatus);

                        return fetch('<?= BASE_URL ?>ajaxOrder', {
                            method: 'POST',
                            body: paymentFormData
                        });
                    } else {
                        throw new Error('Failed to update order status');
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order status updated successfully');
                        // Refresh the orders list
                        performSearch();
                        // Close the modal
                        $('#orderDetailsModal').modal('hide');
                    } else {
                        throw new Error('Failed to update payment status');
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    alert('An error occurred while updating status');
                });
        }

        // Attach click listeners to view buttons
        function attachViewOrderListeners() {
            const viewButtons = document.querySelectorAll('.view-order-details');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-id');
                    loadOrderDetails(orderId);
                });
            });
        }

        // Use event delegation for dynamically added "View" buttons
        document.getElementById('ordersTableBody').addEventListener('click', function(e) {
            if (e.target.closest('.view-order-details')) {
                const button = e.target.closest('.view-order-details');
                const orderId = button.getAttribute('data-id');
                loadOrderDetails(orderId);
            }
        });

        // Initial attachment of event listeners
        attachViewOrderListeners();

        // Other existing event listeners
        searchInput.addEventListener('input', performSearch);
        statusFilter.addEventListener('change', performSearch);
        paymentFilter.addEventListener('change', performSearch);
        clearSearchBtn.addEventListener('click', () => {
            searchInput.value = '';
            performSearch();
        });
        clearFiltersBtn.addEventListener('click', () => {
            statusFilter.value = '';
            paymentFilter.value = '';
            performSearch();
        });
    });
</script>

<?php
renderFooter($data, 'admin');
?>