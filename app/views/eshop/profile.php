<?php
renderHeader($data);
?>

<section id="main-content" class="profile-page">
    <section class="wrapper">
        <div class="container profile-container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <!-- Profile Card -->
                    <div class="panel panel-profile">
                        <div class="panel-heading bg-primary" style="background: linear-gradient(135deg, #4a90e2, #2c3e50);">
                            <h2 class="text-light text-center">My Profile</h2>
                        </div>
                        <div class="panel-body">
                            <div class="row user-profile">
                                <!-- Avatar Column -->
                                <div class="col-sm-4 text-center">
                                    <div class="avatar-wrapper">
                                        <img src="<?= !empty($data['user_data']['avatar']) ? htmlspecialchars($data['user_data']['avatar'], ENT_QUOTES, 'UTF-8') : BASE_URL . 'uploads/avatars/default.png' ?>" class="img-circle img-thumbnail" width="120" alt="User Avatar">
                                        <div class="status-indicator online"></div>
                                    </div>
                                    <h4 class="user-name text-primary">
                                        <?= htmlspecialchars($data['user_data']['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </h4>
                                </div>
                                <!-- Info Column -->
                                <div class="col-sm-5">
                                    <ul class="list-unstyled">
                                        <li style="margin-bottom: 7px;"><i class="fa fa-calendar fa-fw text-muted"></i> Member Since: <strong><?= date('jS M Y', strtotime($data['user_data']['date'])) ?></strong></li>
                                        <li style="margin-bottom: 7px;"><i class="fa fa-envelope-o fa-fw text-muted"></i> <?= htmlspecialchars($data['user_data']['email'], ENT_QUOTES, 'UTF-8') ?></li>
                                        <li><i class="fa fa-line-chart fa-fw text-muted"></i> Total Spend: <strong class="text-success">$<?= number_format($data['total_spend'], 2) ?></strong></li>
                                    </ul>
                                </div>
                                <!-- Actions Column -->
                                <div class="col-sm-3">
                                    <a style="margin-bottom: 7px;" href="<?= BASE_URL ?>profile/edit" class="btn btn-primary btn-block"><i class="fa fa-pencil-square-o"></i> Edit Profile</a>
                                    <form action="<?= BASE_URL ?>profile/delete" method="post" class="btn-form">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                                        <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete your account?')">
                                            <i class="fa fa-trash-o"></i> Delete Account
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="panel panel-default orders-panel">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Order History</h3>
                        </div>
                        <div class="panel-body">
                            <?php if (!empty($data['orders'])): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover orders-table">
                                        <thead>
                                            <tr class="active">
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Tax</th>
                                                <th>Shipping</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Payment</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['orders'] as $order): ?>
                                                <tr class="<?= $order['status'] == 'pending' ? 'table-warning' : '' ?>">
                                                    <td><?= $order['id'] ?></td>
                                                    <td><?= date('M j, Y', strtotime($order['date'])) ?></td>
                                                    <td>$<?= number_format($order['tax'], 2) ?></td>
                                                    <td>$<?= number_format($order['shipping'], 2) ?></td>
                                                    <td>$<?= number_format($order['total'], 2) ?></td>
                                                    <td>
                                                        <span class="label label-<?= $order['status'] == 'delivered' ? 'success' : ($order['status'] == 'cancelled' ? 'danger' : ($order['status'] == 'processing' ? 'info' : 'warning')) ?>">
                                                            <?= ucfirst($order['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="label label-<?= $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'failed' ? 'danger' : 'warning') ?>">
                                                            <?= ucfirst($order['payment_status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($order['status'] == 'pending'): ?>
                                                            <a href="<?= BASE_URL ?>orders/cancel/<?= $order['id'] ?>" class="btn btn-xs btn-danger" title="Cancel Order" onclick="return confirm('Are you sure you want to cancel this order?')">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <button class="btn btn-xs btn-info toggle-details" data-target="#order-details-<?= $order['id'] ?>">
                                                            <i class="fa fa-chevron-down"></i> Details
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr id="order-details-<?= $order['id'] ?>" class="order-details-row" style="display: none;">
                                                    <td colspan="8">
                                                        <div class="order-items">
                                                            <h5>Order Items:</h5>
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product Name</th>
                                                                        <th>Quantity</th>
                                                                        <th>Price</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(isset($data['order_items'][$order['id']])): ?>
                                                                        <?php foreach ($data['order_items'][$order['id']] as $item):
                                                                            $description = htmlspecialchars($item['product_description'], ENT_QUOTES, 'UTF-8');
                                                                            $truncatedDescription = strlen($description) > 50 ? substr($description, 0, 45) . '...' : $description;
                                                                        ?>
                                                                            <tr>
                                                                                <td><a href="<?= BASE_URL . 'productDetails/' . (isset($item['slag']) ? $item['slag'] : 'product') ?>" title="<?= $description ?>"><?= $truncatedDescription ?></a></td>
                                                                                <td><?= $item['quantity'] ?></td>
                                                                                <td>$<?= number_format($item['price'], 2) ?></td>
                                                                                <td>$<?= number_format($item['total'], 2) ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">No items found for this order</td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info"><i class="fa fa-info-circle"></i> You haven't placed any orders yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<style>
    .profile-page {
        margin-top: 30px;
    }

    .panel-profile,
    .orders-panel {
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        margin-bottom: 30px;
    }

    .avatar-wrapper {
        position: relative;
        margin: -70px auto 20px;
        width: 120px;
    }

    .status-indicator {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        border: 2px solid #fff;
        background: #2ecc71;
    }

    .user-name {
        margin-bottom: 20px;
        font-weight: 600;
    }

    .btn-form {
        width: 100%;
    }

    .label {
        padding: 3px 6px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 3px;
    }

    .orders-table .table-warning {
        background: #fff3cd;
    }

    .orders-table .order-details-row {
        background: #f9f9f9;
    }

    .orders-table .toggle-details {
        margin-top: 5px;
    }

    .order-items {
        margin-top: 10px;
    }

    .order-items h5 {
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<script>
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            target.style.display = target.style.display === 'none' || target.style.display === '' ? 'table-row' : 'none';
            this.innerHTML = target.style.display === 'none' ? '<i class="fa fa-chevron-down"></i> Details' : '<i class="fa fa-chevron-up"></i> Hide Details';
        });
    });
</script>

<?php
renderFooter($data);
?>