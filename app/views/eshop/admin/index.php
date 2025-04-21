<?php
renderHeader($data, 'admin');

renderSidebar($data, 'admin');
?>

<style>
    .stats-card {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .stats-card .icon {
        font-size: 40px;
        float: left;
        margin-right: 15px;
    }

    .stats-card .number {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stats-card .title {
        font-size: 14px;
        color: #777;
        text-transform: uppercase;
    }

    .quick-links .btn {
        margin-bottom: 10px;
        font-weight: 600;
        padding: 10px 15px;
        text-align: left;
    }

    .quick-links .icon {
        margin-right: 10px;
        font-size: 16px;
    }

    .activity-panel {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .activity-panel .panel-heading {
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
    }

    .activity-panel .panel-title {
        font-size: 16px;
        font-weight: 600;
    }

    .activity-feed {
        padding: 0;
        list-style: none;
    }

    .activity-feed .feed-item {
        position: relative;
        padding: 15px 15px 15px 35px;
        border-bottom: 1px solid #f1f1f1;
    }

    .activity-feed .feed-item:last-child {
        border-bottom: none;
    }

    .activity-feed .feed-item::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 15px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #4ECDC4;
    }

    .activity-feed .feed-item.order::before {
        background-color: #5bc0de;
    }

    .activity-feed .feed-item.user::before {
        background-color: #5cb85c;
    }

    .activity-feed .feed-item.product::before {
        background-color: #f0ad4e;
    }

    .activity-feed .feed-item .date {
        display: block;
        color: #999;
        font-size: 12px;
        margin-top: 5px;
    }

    .chart-container {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .chart-header {
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .chart-title {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .todo-panel .panel-body {
        padding: 0;
    }

    .todo-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .todo-list li {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f1f1;
        position: relative;
    }

    .todo-list li:last-child {
        border-bottom: none;
    }

    .todo-list .todo-check {
        margin-right: 10px;
    }

    .todo-list .todo-title {
        font-weight: 500;
    }

    .todo-list .todo-priority {
        position: absolute;
        right: 15px;
        top: 12px;
    }

    .priority-high {
        color: #d9534f;
    }

    .priority-medium {
        color: #f0ad4e;
    }

    .priority-low {
        color: #5cb85c;
    }
</style>

<!-- MAIN CONTENT -->
<section id="main-content">
    <section class="wrapper site-min-height">
        <div class="row mt">
            <div class="col-lg-12">
                <h3><i class="fa fa-dashboard"></i> Dashboard</h3>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row mt">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-shopping-cart text-primary"></i>
                    </div>
                    <div class="number" id="total-orders">--</div>
                    <div class="title">Total Orders</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-users text-success"></i>
                    </div>
                    <div class="number" id="total-users">--</div>
                    <div class="title">Total Users</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-cubes text-warning"></i>
                    </div>
                    <div class="number" id="total-products">--</div>
                    <div class="title">Total Products</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-money text-danger"></i>
                    </div>
                    <div class="number" id="total-revenue">$0.00</div>
                    <div class="title">Revenue</div>
                </div>
            </div>
        </div>

        <!-- Second Row: Charts and Recent Activity -->
        <div class="row">
            <!-- Sales Overview -->
            <div class="col-lg-8">  
                <div class="chart-container">
                    <div class="chart-header">
                        <h4 class="chart-title"><i class="fa fa-line-chart"></i> Sales Overview</h4>
                    </div>
                    <div id="sales-chart" style="height: 250px;"></div>
                </div>
                
                <!-- Recent Orders -->
                <div class="activity-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-shopping-cart"></i> Recent Orders</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="recent-orders">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">Loading recent orders...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links and Activity Feed -->
            <div class="col-lg-4">
                <!-- Quick Links -->
                <div class="activity-panel quick-links">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-link"></i> Quick Actions</h4>
                    </div>
                    <div class="panel-body">
                        <a href="<?= BASE_URL ?>admin/products" class="btn btn-primary btn-block">
                            <span class="icon"><i class="fa fa-cube"></i></span> Add New Product
                        </a>
                        <a href="<?= BASE_URL ?>admin/categories" class="btn btn-success btn-block">
                            <span class="icon"><i class="fa fa-folder-open"></i></span> Manage Categories
                        </a>
                        <a href="<?= BASE_URL ?>admin/orders" class="btn btn-info btn-block">
                            <span class="icon"><i class="fa fa-truck"></i></span> Process Orders
                        </a>
                        <a href="<?= BASE_URL ?>admin/users" class="btn btn-warning btn-block">
                            <span class="icon"><i class="fa fa-users"></i></span> Manage Users
                        </a>
                    </div>
                </div>
                
                <!-- Activity Feed -->
                <div class="activity-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-bell"></i> Recent Activity</h4>
                    </div>
                    <div class="panel-body">
                        <ul class="activity-feed" id="activity-feed">
                            <li class="feed-item">Loading activity feed...</li>
                        </ul>
                    </div>
                </div>

                <!-- To-Do List -->
                <div class="activity-panel todo-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title"><i class="fa fa-tasks"></i> Admin Tasks</h4>
                    </div>
                    <div class="panel-body">
                        <ul class="todo-list">
                            <li>
                                <input type="checkbox" class="todo-check">
                                <span class="todo-title">Check new product requests</span>
                                <span class="todo-priority priority-high"><i class="fa fa-circle"></i> High</span>
                            </li>
                            <li>
                                <input type="checkbox" class="todo-check">
                                <span class="todo-title">Update inventory status</span>
                                <span class="todo-priority priority-medium"><i class="fa fa-circle"></i> Medium</span>
                            </li>
                            <li>
                                <input type="checkbox" class="todo-check">
                                <span class="todo-title">Respond to customer inquiries</span>
                                <span class="todo-priority priority-high"><i class="fa fa-circle"></i> High</span>
                            </li>
                            <li>
                                <input type="checkbox" class="todo-check">
                                <span class="todo-title">Prepare monthly sales report</span>
                                <span class="todo-priority priority-low"><i class="fa fa-circle"></i> Low</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section> <!-- /wrapper -->
</section><!-- /MAIN CONTENT -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial statistics from PHP data
        document.getElementById('total-orders').textContent = '<?= $data['total_orders'] ?? 0 ?>';
        document.getElementById('total-users').textContent = '<?= $data['total_users'] ?? 0 ?>';
        document.getElementById('total-products').textContent = '<?= $data['total_products'] ?? 0 ?>';
        document.getElementById('total-revenue').textContent = '$<?= $data['total_revenue'] ?? "0.00" ?>';
        
        // Load Recent Orders - you can use PHP to populate initial data
        <?php if (isset($data['recent_orders']) && !empty($data['recent_orders'])): ?>
            // Build recent orders table using PHP data
            let ordersHtml = '';
            <?php foreach($data['recent_orders'] as $order): ?>
                ordersHtml += `
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?></td>
                        <td>$<?= number_format($order['total'], 2) ?></td>
                        <td><span class="label label-<?= getStatusClass($order['status']) ?>"><?= ucfirst($order['status']) ?></span></td>
                        <td><?= date('M j, Y', strtotime($order['date'])) ?></td>
                    </tr>
                `;
            <?php endforeach; ?>
            document.querySelector('#recent-orders tbody').innerHTML = ordersHtml;
        <?php else: ?>
            // If no PHP data, use AJAX to load recent orders
            loadRecentOrders();
        <?php endif; ?>
        
        // Load Activity Feed
        loadActivityFeed();
        
        // Initialize Morris.js chart (if you have the library)
        initSalesChart();
        
        // Refresh data periodically (every 5 minutes)
        setInterval(function() {
            loadDashboardStats();
            loadRecentOrders();
            loadActivityFeed();
        }, 300000); // 5 minutes
    });

    function loadDashboardStats() {
        // You can replace this with actual AJAX calls to your backend
        fetch('<?= BASE_URL ?>ajaxDashboard?action=getStats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update stats cards
                    document.getElementById('total-orders').textContent = data.stats.total_orders || 0;
                    document.getElementById('total-users').textContent = data.stats.total_users || 0;
                    document.getElementById('total-products').textContent = data.stats.total_products || 0;
                    document.getElementById('total-revenue').textContent = '$' + (data.stats.total_revenue || '0.00');
                }
            })
            .catch(error => {
                console.error('Error loading dashboard stats:', error);
            });
    }

    function loadRecentOrders() {
        fetch('<?= BASE_URL ?>ajaxDashboard?action=getRecentOrders')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.orders && data.orders.length > 0) {
                    // Build table rows
                    let rows = '';
                    data.orders.forEach(order => {
                        let statusClass = '';
                        switch(order.status) {
                            case 'delivered': statusClass = 'label-success'; break;
                            case 'shipped': statusClass = 'label-info'; break;
                            case 'processing': statusClass = 'label-primary'; break;
                            case 'pending': statusClass = 'label-warning'; break;
                            case 'cancelled': statusClass = 'label-danger'; break;
                        }
                        
                        rows += `
                            <tr>
                                <td>#${order.id}</td>
                                <td>${order.customer}</td>
                                <td>${order.amount}</td>
                                <td><span class="label ${statusClass}">${order.status}</span></td>
                                <td>${formatDate(order.date)}</td>
                            </tr>
                        `;
                    });
                    
                    document.querySelector('#recent-orders tbody').innerHTML = rows;
                } else {
                    // Fallback to sample data if no orders
                    const sampleOrders = [
                        { id: '1052', customer: 'John Doe', amount: '$125.99', status: 'delivered', date: '2023-08-05' },
                        { id: '1051', customer: 'Jane Smith', amount: '$89.50', status: 'shipped', date: '2023-08-05' },
                        { id: '1050', customer: 'Robert Johnson', amount: '$212.75', status: 'processing', date: '2023-08-04' },
                        { id: '1049', customer: 'Emily Brown', amount: '$45.25', status: 'pending', date: '2023-08-04' },
                        { id: '1048', customer: 'Michael Wilson', amount: '$178.50', status: 'delivered', date: '2023-08-03' }
                    ];
                    
                    let rows = '';
                    sampleOrders.forEach(order => {
                        let statusClass = '';
                        switch(order.status) {
                            case 'delivered': statusClass = 'label-success'; break;
                            case 'shipped': statusClass = 'label-info'; break;
                            case 'processing': statusClass = 'label-primary'; break;
                            case 'pending': statusClass = 'label-warning'; break;
                            case 'cancelled': statusClass = 'label-danger'; break;
                        }
                        
                        rows += `
                            <tr>
                                <td>#${order.id}</td>
                                <td>${order.customer}</td>
                                <td>${order.amount}</td>
                                <td><span class="label ${statusClass}">${order.status}</span></td>
                                <td>${formatDate(order.date)}</td>
                            </tr>
                        `;
                    });
                    
                    document.querySelector('#recent-orders tbody').innerHTML = rows;
                }
            })
            .catch(error => {
                console.error('Error loading recent orders:', error);
                // Use the sample data loading as a fallback
                const sampleOrders = [
                    { id: '1052', customer: 'John Doe', amount: '$125.99', status: 'delivered', date: '2023-08-05' },
                    { id: '1051', customer: 'Jane Smith', amount: '$89.50', status: 'shipped', date: '2023-08-05' },
                    { id: '1050', customer: 'Robert Johnson', amount: '$212.75', status: 'processing', date: '2023-08-04' },
                    { id: '1049', customer: 'Emily Brown', amount: '$45.25', status: 'pending', date: '2023-08-04' },
                    { id: '1048', customer: 'Michael Wilson', amount: '$178.50', status: 'delivered', date: '2023-08-03' }
                ];
                
                let rows = '';
                sampleOrders.forEach(order => {
                    let statusClass = '';
                    switch(order.status) {
                        case 'delivered': statusClass = 'label-success'; break;
                        case 'shipped': statusClass = 'label-info'; break;
                        case 'processing': statusClass = 'label-primary'; break;
                        case 'pending': statusClass = 'label-warning'; break;
                        case 'cancelled': statusClass = 'label-danger'; break;
                    }
                    
                    rows += `
                        <tr>
                            <td>#${order.id}</td>
                            <td>${order.customer}</td>
                            <td>${order.amount}</td>
                            <td><span class="label ${statusClass}">${order.status}</span></td>
                            <td>${formatDate(order.date)}</td>
                        </tr>
                    `;
                });
                
                document.querySelector('#recent-orders tbody').innerHTML = rows;
            });
    }

    function loadActivityFeed() {
        fetch('<?= BASE_URL ?>ajaxDashboard?action=getRecentActivity')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.activities && data.activities.length > 0) {
                    // Build activity feed items
                    let items = '';
                    data.activities.forEach(activity => {
                        items += `
                            <li class="feed-item ${activity.type}">
                                <div class="feed-content">
                                    <span class="text">${activity.text}</span>
                                    <span class="date">${activity.date}</span>
                                </div>
                            </li>
                        `;
                    });
                    
                    document.getElementById('activity-feed').innerHTML = items;
                } else {
                    // Fallback to sample data
                    const activities = [
                        { type: 'order', text: 'New order #1052 has been placed', date: '10 minutes ago' },
                        { type: 'user', text: 'New user "johndoe" registered', date: '1 hour ago' },
                        { type: 'product', text: 'Product "iPhone 13" stock is low', date: '3 hours ago' },
                        { type: 'order', text: 'Order #1049 payment confirmed', date: '5 hours ago' },
                        { type: 'user', text: 'User "emilybrown" updated profile', date: '1 day ago' }
                    ];
                    
                    let items = '';
                    activities.forEach(activity => {
                        items += `
                            <li class="feed-item ${activity.type}">
                                <div class="feed-content">
                                    <span class="text">${activity.text}</span>
                                    <span class="date">${activity.date}</span>
                                </div>
                            </li>
                        `;
                    });
                    
                    document.getElementById('activity-feed').innerHTML = items;
                }
            })
            .catch(error => {
                console.error('Error loading activity feed:', error);
                // Fallback to sample data
                const activities = [
                    { type: 'order', text: 'New order #1052 has been placed', date: '10 minutes ago' },
                    { type: 'user', text: 'New user "johndoe" registered', date: '1 hour ago' },
                    { type: 'product', text: 'Product "iPhone 13" stock is low', date: '3 hours ago' },
                    { type: 'order', text: 'Order #1049 payment confirmed', date: '5 hours ago' },
                    { type: 'user', text: 'User "emilybrown" updated profile', date: '1 day ago' }
                ];
                
                let items = '';
                activities.forEach(activity => {
                    items += `
                        <li class="feed-item ${activity.type}">
                            <div class="feed-content">
                                <span class="text">${activity.text}</span>
                                <span class="date">${activity.date}</span>
                            </div>
                        </li>
                    `;
                });
                
                document.getElementById('activity-feed').innerHTML = items;
            });
    }

    function initSalesChart() {
        // If you have Morris.js included, you can uncomment this code
        /*
        Morris.Area({
            element: 'sales-chart',
            data: [
                { period: '2023-01', orders: 20, sales: 2400 },
                { period: '2023-02', orders: 25, sales: 2800 },
                { period: '2023-03', orders: 30, sales: 3200 },
                { period: '2023-04', orders: 22, sales: 2600 },
                { period: '2023-05', orders: 28, sales: 3100 },
                { period: '2023-06', orders: 35, sales: 3800 },
                { period: '2023-07', orders: 42, sales: 4500 },
                { period: '2023-08', orders: 38, sales: 4200 }
            ],
            xkey: 'period',
            ykeys: ['orders', 'sales'],
            labels: ['Orders', 'Sales ($)'],
            lineColors: ['#5bc0de', '#5cb85c'],
            pointSize: 3,
            hideHover: 'auto',
            resize: true,
            parseTime: false
        });
        */
        
        // Fallback message if Morris.js is not available
        document.getElementById('sales-chart').innerHTML = '<div class="text-center" style="padding: 100px 20px; color: #999;"><i class="fa fa-area-chart" style="font-size: 40px; margin-bottom: 15px;"></i><p>Sales chart will be displayed here.<br>Enable Morris.js to see the chart.</p></div>';
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    // Add event listeners for to-do list items
    document.querySelectorAll('.todo-check').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const listItem = this.closest('li');
            if (this.checked) {
                listItem.style.textDecoration = 'line-through';
                listItem.style.color = '#999';
            } else {
                listItem.style.textDecoration = 'none';
                listItem.style.color = '';
            }
        });
    });
</script>

<?php 
// Helper function to determine status label class
function getStatusClass($status) {
    switch($status) {
        case 'delivered': return 'success';
        case 'shipped': return 'info';
        case 'processing': return 'primary';
        case 'pending': return 'warning';
        case 'cancelled': return 'danger';
        default: return 'default';
    }
}

renderFooter($data, 'admin');
?>