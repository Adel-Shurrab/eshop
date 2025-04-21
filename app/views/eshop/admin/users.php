<?php
renderHeader($data, 'admin');
renderSidebar($data, 'admin');
?>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>

<style type="text/css">
    .user-avatar-sm {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .user-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .view-details-list {
        list-style: none;
        padding-left: 0;
    }

    .view-details-list li {
        margin-bottom: 12px;
    }

    .detail-icon {
        width: 25px;
        text-align: center;
        margin-right: 10px;
    }

    .dropdown-menu {
        min-width: 160px;
    }

    .dropdown-item {
        padding: 8px 15px;
        font-size: 13px;
    }

    .dropdown-item i {
        width: 18px;
        margin-right: 8px;
    }

    .btn-file {
        position: relative;
        overflow: hidden;
    }

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

    .input-group-addon {
        border-radius: 4px 0 0 4px !important;
    }

    .selectpicker {
        width: 100%;
    }

    .help-block {
        font-size: 12px;
        color: #999;
    }

    .btn-file {
        position: relative;
        overflow: hidden;
    }

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

    .input-group-addon {
        border-radius: 4px 0 0 4px !important;
    }

    .selectpicker {
        width: 100%;
    }

    .help-block {
        font-size: 12px;
        color: #999;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header.bg-primary {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border-radius: 8px 8px 0 0;
        padding: 20px;
    }

    .modal-header .modal-title {
        color: #fff;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .modal-header .close {
        color: #fff;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        padding: 20px;
        border-top: 1px solid #eee;
    }

    /* Form Styles */
    .form-control {
        height: 42px;
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: none;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.15);
    }

    textarea.form-control {
        height: auto;
    }

    .input-group-addon {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        color: #666;
    }

    .control-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #444;
    }

    .help-block {
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }

    /* Button Styles */
    .btn {
        border-radius: 6px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
    }

    .btn-lg {
        padding: 12px 24px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1c7a91, #5bc0db);
        transform: translateY(-1px);
    }

    .btn-default {
        background: #f8f9fa;
        border: 1px solid #ddd;
        color: #444;
    }

    .btn-default:hover {
        background: #e9ecef;
    }

    /* File Upload Styles */
    .btn-file {
        background: #f8f9fa;
        border: 1px solid #ddd;
        color: #444;
        border-radius: 6px 0 0 6px;
    }

    .btn-file:hover {
        background: #e9ecef;
    }

    /* Alert Styles */
    .alert-info {
        background-color: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 6px;
        color: #444;
    }

    .alert-info .fa-check-circle {
        color: #2193b0;
        margin-right: 8px;
    }

    /* Section Divider */
    hr.mt-20.mb-20 {
        margin: 25px 0;
        border-color: #eee;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }

        .modal-body {
            padding: 15px;
        }
    }

    /* Improved View User Details styling */
    .user-profile-img {
        padding: 20px 0;
    }

    .user-profile-img img {
        width: 150px;
        height: 150px;
        border: 5px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        margin-bottom: 10px;
    }

    .user-status-badge .badge {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .badge.active {
        background-color: #5cb85c;
    }

    .badge.inactive {
        background-color: #d9534f;
    }

    .user-info-tabs {
        margin-top: 15px;
    }

    .user-info-tabs .nav-tabs {
        border-bottom: 2px solid #eee;
    }

    .user-info-tabs .nav-tabs>li>a {
        border: none;
        color: #555;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 15px;
    }

    .user-info-tabs .nav-tabs>li.active>a,
    .user-info-tabs .nav-tabs>li>a:hover {
        border: none;
        background: transparent;
        color: #2193b0;
        border-bottom: 2px solid #2193b0;
    }

    .user-info-tabs .nav-tabs>li>a i {
        margin-right: 5px;
    }

    .user-info-panel {
        padding: 20px 0;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .info-item .detail-icon {
        width: 36px;
        height: 36px;
        background: #f5f5f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2193b0;
        font-size: 16px;
        margin-right: 15px;
    }

    .info-details {
        flex-grow: 1;
    }

    .info-details label {
        font-size: 12px;
        color: #777;
        margin-bottom: 5px;
        display: block;
    }

    .info-details p {
        font-size: 15px;
        margin: 0;
        color: #333;
        font-weight: 500;
        word-wrap: break-word;
    }

    .tab-content {
        padding-top: 15px;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .input-group .input-group-addon {
        background-color: #f8f9fa;
        border-color: #ddd;
        color: #666;
    }

    .input-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
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

    .user-filter-active {
        background-color: #e3f2fd !important;
        border-color: #90caf9 !important;
    }

    #searchInput::placeholder {
        color: #999;
        font-style: italic;
    }

    /* Stats Cards */
    .stats-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.05);
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
</style>

<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-users"></i> User Management</h3>

        <!-- Statistics Row -->
        <div class="row mt">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-users text-success"></i>
                    </div>
                    <div class="number" id="total-users">--</div>
                    <div class="title">Total Users</div>
                    <div class="stats-filtered-indicator" id="stats-filtered-message">Showing filtered results</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-user-circle text-info"></i>
                    </div>
                    <div class="number" id="admin-users">--</div>
                    <div class="title">Admins</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-user text-primary"></i>
                    </div>
                    <div class="number" id="customer-users">--</div>
                    <div class="title">Customers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="icon">
                        <i class="fa fa-trash-o text-danger"></i>
                    </div>
                    <div class="number" id="trash-users">--</div>
                    <div class="title">Deleted Users</div>
                </div>
            </div>
        </div>

        <div class="row mt">
            <div class="col-lg-12">
                <div class="content-panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="mb-3" id="table_title">
                                    <i class="fa fa-list"></i> Active Users
                                </h4>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm" onclick="showAddNew()">
                                        <i class="fa fa-plus"></i> Add User
                                    </button>
                                    <button class="btn btn-default btn-sm" onclick="refreshCustomers()">
                                        <i class="fa fa-refresh"></i> Refresh
                                    </button>
                                    <button class="btn btn-secondary btn-sm" id="trash_btn" onclick="toggleTrashView()">
                                        <i class="fa fa-trash-o"></i> <span id="trash_btn_text">View Trash</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="searchInput" placeholder="Search by ID, name, email, phone...">
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
                                            <i class="fa fa-user-circle"></i>
                                        </span>
                                        <select class="form-control" id="roleFilter" style="width: 130px;">
                                            <option value="">All Roles</option>
                                            <option value="admin">Admin</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </div>
                                    <div class="input-group" style="width: auto;">
                                        <span class="input-group-addon">
                                            <i class="fa fa-toggle-on"></i>
                                        </span>
                                        <select class="form-control" id="statusFilter" style="width: 130px;">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-default" type="button" onclick="clearFilters()" id="clearFiltersBtn" style="display: none;">
                                        <i class="fa fa-filter"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Orders</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_body">
                                <?php if (empty($data['users'])): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['users'] as $user): ?>
                                        <tr>
                                            <td>
                                                <img src="<?= !empty($user['avatar']) ? BASE_URL . $user['avatar'] : BASE_URL . 'uploads/avatars/default.png' ?>"
                                                    class="user-avatar-sm" alt="avatar">
                                                <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <span class="user-badge bg-<?= $user['rank'] === 'admin' ? 'primary' : 'info' ?>">
                                                    <?= ucfirst($user['rank']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="user-badge bg-<?= $user['status'] == '1' ? 'success' : 'danger' ?>">
                                                    <?= $user['status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= $user['order_count'] ?> orders
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-xs btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item text-info" href="javascript:void(0)"
                                                            onclick="viewCustomer(this)"
                                                            data-id="<?= (int)$user['id'] ?>"
                                                            data-name="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>"
                                                            data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"
                                                            data-gender="<?= htmlspecialchars($user['gender'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-phone="<?= htmlspecialchars($user['phone'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-address="<?= htmlspecialchars($user['address'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-country="<?= htmlspecialchars($user['country'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-state="<?= htmlspecialchars($user['state'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-zip="<?= htmlspecialchars($user['zip'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-last_login="<?= htmlspecialchars($user['last_login'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-date="<?= htmlspecialchars($user['date'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>"
                                                            data-rank="<?= htmlspecialchars($user['rank'], ENT_QUOTES, 'UTF-8') ?>"
                                                            data-status="<?= (int)$user['status'] ?>"
                                                            data-avatar="<?= !empty($user['avatar']) ? BASE_URL . htmlspecialchars($user['avatar'], ENT_QUOTES, 'UTF-8') : BASE_URL . 'uploads/avatars/default.png' ?>"
                                                            data-orderCount="<?= $user['order_count'] ?>"
                                                            data-urlAddress="<?= htmlspecialchars($user['url_address'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>">
                                                            <i class="fa fa-eye fa-fw"></i> View Details
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-primary" href="javascript:void(0)"
                                                            onclick="showEdit(
                                                                '<?= $user['id'] ?>',
                                                                '<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['country'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['state'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['zip'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['status'], ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['gender'] ?? '', ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= htmlspecialchars($user['rank'], ENT_QUOTES, 'UTF-8') ?>',
                                                                '<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar'], ENT_QUOTES, 'UTF-8') : BASE_URL . 'uploads/avatars/default.png' ?>'
                                                            )">
                                                            <i class="fa fa-edit fa-fw"></i> Edit
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <?php if ((int)$user['status'] === 1): ?>
                                                            <a class="dropdown-item text-warning" href="javascript:void(0)"
                                                                onclick="changeUserStatus(<?= (int)$user['id'] ?>, 0)"
                                                                title="Deactivate user account">
                                                                <i class="fa fa-times-circle fa-fw"></i> Deactivate
                                                            </a>
                                                        <?php else: ?>
                                                            <a class="dropdown-item text-success" href="javascript:void(0)"
                                                                onclick="changeUserStatus(<?= (int)$user['id'] ?>, 1)"
                                                                title="Activate user account">
                                                                <i class="fa fa-check-circle fa-fw"></i> Activate
                                                            </a>
                                                        <?php endif; ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                            onclick="deleteUser(<?= $user['id'] ?>)">
                                                            <i class="fa fa-trash-o fa-fw"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?= $data['pagination'] ?>
                    </div>

                    <!-- View User Modal -->
                    <div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title">
                                        <i class="fa fa-user"></i> User Details
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="user-profile-img">
                                                <img id="view_avatar" src="<?= BASE_URL ?>uploads/avatars/default.png"
                                                    class="img-circle img-responsive center-block" alt="avatar">
                                                <div class="user-status-badge mt-2">
                                                    <span id="view_status_badge" class="badge"></span>
                                                </div>
                                                <h4 id="view_name" class="mt-3"></h4>
                                                <p class="text-muted"><span id="view_rank" class="text-capitalize"></span></p>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="user-info-tabs">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active"><a href="#basic-info" aria-controls="basic-info" role="tab" data-toggle="tab"><i class="fa fa-user-circle"></i> Basic Info</a></li>
                                                    <li role="presentation"><a href="#contact-info" aria-controls="contact-info" role="tab" data-toggle="tab"><i class="fa fa-address-book"></i> Contact</a></li>
                                                    <li role="presentation"><a href="#account-info" aria-controls="account-info" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> Account</a></li>
                                                </ul>

                                                <div class="tab-content">
                                                    <div role="tabpanel" class="tab-pane active" id="basic-info">
                                                        <div class="user-info-panel">
                                                            <div class="info-item">
                                                                <i class="fa fa-envelope detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Email</label>
                                                                    <p id="view_email"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-transgender detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Gender</label>
                                                                    <p id="view_gender"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-id-badge detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>User ID</label>
                                                                    <p id="view_id"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-calendar detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Registered</label>
                                                                    <p id="view_date"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="contact-info">
                                                        <div class="user-info-panel">
                                                            <div class="info-item">
                                                                <i class="fa fa-phone detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Phone</label>
                                                                    <p id="view_phone"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-map-marker detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Address</label>
                                                                    <p id="view_address"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-globe detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Country</label>
                                                                    <p id="view_country"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-flag detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>State/Province</label>
                                                                    <p id="view_state"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-map-pin detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>ZIP Code</label>
                                                                    <p id="view_zip"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="account-info">
                                                        <div class="user-info-panel">
                                                            <div class="info-item">
                                                                <i class="fa fa-user-secret detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Role</label>
                                                                    <p id="view_rank_detail"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-toggle-on detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Status</label>
                                                                    <p id="view_status"></p>
                                                                </div>
                                                            </div>
                                                            <div class="info-item">
                                                                <i class="fa fa-clock-o detail-icon"></i>
                                                                <div class="info-details">
                                                                    <label>Last Login</label>
                                                                    <p id="view_last_login"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="showEditFromView()">Edit User</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add User Modal -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title">
                                        <i class="fa fa-user-plus"></i> Add New User
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="addUserForm" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); collectData();">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Full Name</label>
                                                    <input type="text" name="name" class="form-control" placeholder="Enter full name">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Email Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                        <input type="email" name="email" class="form-control" placeholder="Enter email address">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Password</label>
                                                            <div class="input-group">
                                                                <input type="password" name="password" id="add_password" class="form-control" placeholder="Enter password">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default toggle-password" type="button" onclick="togglePassword('add_password')">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Confirm Password</label>
                                                            <div class="input-group">
                                                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default toggle-password" type="button" onclick="togglePassword('confirm_password')">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option disabled selected value="">Select gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone Number</label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                        <input type="tel" name="phone" class="form-control" placeholder="Enter phone number">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Address</label>
                                                    <textarea name="address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">Country</label>
                                                            <select name="country" id="country" class="form-control" oninput="getStates(this.value)">
                                                                <option disabled selected value="0">Select Country</option>
                                                                <?php if (isset($data['countries']) && is_array($data['countries'])): ?>
                                                                    <?php foreach ($data['countries'] as $country): ?>
                                                                        <option value="<?= htmlspecialchars($country['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($country['country'], ENT_QUOTES, 'UTF-8') ?></option>
                                                                    <?php endforeach ?>
                                                                <?php endif ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">State/Province</label>
                                                            <select name="state" id="state" class="form-control">
                                                                <option disabled selected value="0">Select state</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">ZIP/Postal Code</label>
                                                    <input type="text" name="zip" class="form-control" placeholder="Enter ZIP code">
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="mt-20 mb-20">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">User Role</label>
                                                    <select name="rank" class="form-control">
                                                        <option disabled selected value="">Select role</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="admin">Administrator</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Account Status</label>
                                                    <select name="status" class="form-control">
                                                        <option disabled selected value="">Select status</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Upload Avatar</label>
                                                    <div class="text-center mb-3">
                                                        <img id="add_avatar_preview" src="<?php if (isset($data['user_data']['avatar'])): ?><?= BASE_URL . $data['user_data']['avatar'] ?><?php else: ?><?= BASE_URL ?>uploads/avatars/default.png<?php endif ?>" class="img-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                                    </div>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-default btn-file">
                                                                <i class="fa fa-upload"></i> Browse... <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this, 'add_avatar_preview')">
                                                            </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly placeholder="No file chosen">
                                                    </div>
                                                    <small class="help-block">Maximum file size: 2MB (JPEG, PNG)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Password Requirements</label>
                                                    <div class="alert alert-info mb-0">
                                                        <ul class="list-unstyled mb-0">
                                                            <li><i class="fa fa-check-circle"></i> 8-20 characters</li>
                                                            <li><i class="fa fa-check-circle"></i> At least one uppercase letter</li>
                                                            <li><i class="fa fa-check-circle"></i> At least one number</li>
                                                            <li><i class="fa fa-check-circle"></i> At least one special character</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            <i class="fa fa-times"></i> Cancel
                                        </button>
                                        <button type="submit" id="add_submit_btn" class="btn btn-primary">
                                            <i class="fa fa-user-plus"></i> Add User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit User Modal -->
                <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">
                                    <i class="fa fa-edit"></i> Edit User
                                </h4>
                            </div>
                            <form id="editUserForm" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); editUser();">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="id" id="edit_id">
                                            <div class="form-group">
                                                <label class="control-label">Full Name</label>
                                                <input type="text" name="name" id="edit_name" class="form-control input-lg">
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Email Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                    <input type="email" name="email" id="edit_email" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">New Password (leave blank to keep current)</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" id="edit_password" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default toggle-password" type="button" onclick="togglePassword('edit_password')">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <small class="form-text text-muted">Password must be 8-20 characters and include letters, numbers, and special characters.</small>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Gender</label>
                                                <select name="gender" id="edit_gender" class="form-control">
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Phone Number</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                    <input type="tel" name="phone" id="edit_phone" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">Address</label>
                                                <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Country</label>
                                                        <select name="country" id="edit_country" class="form-control" oninput="getStates(this.value)">
                                                            <option value="" disabled selected>Select Country</option> <!-- Placeholder option -->
                                                            <?php if (isset($data['countries']) && is_array($data['countries'])): ?>
                                                                <?php foreach ($data['countries'] as $country): ?>
                                                                    <option value="<?= htmlspecialchars($country['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($country['country'], ENT_QUOTES, 'UTF-8') ?></option>
                                                                <?php endforeach ?>
                                                            <?php endif ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">State/Province</label>
                                                        <select name="state" id="edit_state" class="form-control">
                                                            <option disabled selected value="0">Select state</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label">ZIP/Postal Code</label>
                                                <input type="text" name="zip" id="edit_zip" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="mt-20 mb-20">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">User Role</label>
                                                <select name="rank" id="edit_rank" class="form-control">
                                                    <option value="customer">Customer</option>
                                                    <option value="admin">Administrator</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Account Status</label>
                                                <select name="status" id="edit_status" class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Upload Avatar</label>
                                        <div class="text-center mb-3">
                                            <img id="edit_avatar_preview" src="<?php if (isset($data['user_data']['avatar'])): ?><?= BASE_URL . $data['user_data']['avatar'] ?><?php else: ?><?= BASE_URL ?>uploads/avatars/default.png<?php endif ?>" class="img-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <span class="btn btn-default btn-file">
                                                    <i class="fa fa-upload"></i> Browse... <input type="file" name="avatar" accept="image/*" onchange="previewAvatar(this, 'edit_avatar_preview')">
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" readonly placeholder="No file chosen">
                                        </div>
                                        <small class="help-block">Maximum file size: 2MB (JPEG, PNG)</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" id="edit_submit_btn" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

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
            </div>
        </div>
    </section>
</section>

<?php
renderFooter($data, 'admin');
?>

<script>
    function viewCustomer(button) {
        const userDetails = {
            id: button.dataset.id,
            name: button.dataset.name,
            email: button.dataset.email,
            gender: button.dataset.gender,
            phone: button.dataset.phone,
            address: button.dataset.address,
            country: button.dataset.country,
            state: button.dataset.state,
            zip: button.dataset.zip,
            last_login: button.dataset.last_login,
            date: button.dataset.date,
            rank: button.dataset.rank,
            status: button.dataset.status,
            avatar: button.dataset.avatar,
            order_count: button.dataset.orderCount,
            url_address: button.dataset.urlAddress
        };

        Object.entries(userDetails).forEach(([key, value]) => {
            const element = document.getElementById(`view_${key}`);
            if (element) {
                if (key === 'avatar') {
                    if (value && value.startsWith('http')) {
                        element.src = value;
                    } else {
                        element.src = value ? BASE_URL + value : BASE_URL + 'uploads/avatars/default.png';
                    }
                } else if (key === 'status') {
                    element.textContent = value === '1' ? 'Active' : 'Inactive';
                    const badgeElement = document.getElementById('view_status_badge');
                    if (badgeElement) {
                        badgeElement.textContent = value === '1' ? 'Active' : 'Inactive';
                        badgeElement.className = value === '1' ? 'badge active' : 'badge inactive';
                    }
                } else {
                    element.textContent = value;
                }
            }
        });

        const rankDetailElement = document.getElementById('view_rank_detail');
        if (rankDetailElement) {
            rankDetailElement.textContent = userDetails.rank || '';
        }

        fetchCountryName(userDetails.country);
        fetchStateName(userDetails.state);

        window.currentViewedUserId = userDetails.id;

        // Set order count
        const orderElement = document.getElementById('view_orders');
        if (orderElement) {
            const orderCount = parseInt(userDetails.order_count) || 0;
            orderElement.textContent = orderCount;
        }

        $('#viewCustomerModal').modal('show');
    }

    function fetchCountryName(countryId) {
        if (!countryId || countryId === 'N/A') {
            const countryElement = document.getElementById('view_country');
            if (countryElement) {
                countryElement.textContent = 'N/A';
                countryElement.setAttribute('data-country-id', '');
            }
            return;
        }

        const formData = new FormData();
        formData.append('c_id', countryId);
        formData.append('dataType', 'get_country_name');

        sendData(formData);
    }

    function fetchStateName(stateId) {
        if (!stateId || stateId === 'N/A') {
            const stateElement = document.getElementById('view_state');
            if (stateElement) {
                stateElement.textContent = 'N/A';
                stateElement.setAttribute('data-state-id', '');
            }
            return;
        }

        const formData = new FormData();
        formData.append('s_id', stateId);
        formData.append('dataType', 'get_state_name');

        sendData(formData);
    }

    function showAddNew() {
        document.getElementById('addUserForm').reset();
        const avatarPreview = document.getElementById('edit_avatar_preview');
        if (avatarPreview) {
            avatarPreview.src = BASE_URL + 'uploads/avatars/default.png';
        }
        $('#addUserModal').modal('show');
    }

    function showEdit(userId, name, email, phone, address, country, state, zip, status, gender, rank, avatar) {
        const form = document.getElementById('editUserForm');
        form.reset();

        document.getElementById('edit_id').value = userId || '';
        document.getElementById('edit_name').value = name || '';
        document.getElementById('edit_email').value = email || '';
        document.getElementById('edit_phone').value = phone || '';
        document.getElementById('edit_address').value = address || '';
        document.getElementById('edit_zip').value = zip || '';

        const genderSelect = document.getElementById('edit_gender');
        if (genderSelect) {
            for (let i = 0; i < genderSelect.options.length; i++) {
                if (genderSelect.options[i].value.toLowerCase() === (gender || '').toLowerCase()) {
                    genderSelect.selectedIndex = i;
                    break;
                }
            }
        }

        const statusSelect = document.getElementById('edit_status');
        if (statusSelect) {
            Array.from(statusSelect.options).forEach(option => {
                option.selected = option.value === status;
            });
        }

        const rankSelect = document.getElementById('edit_rank');
        if (rankSelect) {
            Array.from(rankSelect.options).forEach(option => {
                option.selected = option.value === rank;
            });
        }

        const countrySelect = document.getElementById('edit_country');
        if (countrySelect) {
            countrySelect.value = country || "";
            if (country) {
                getStates(country);
            }
        }

        setTimeout(() => {
            const stateSelect = document.getElementById('edit_state');
            if (stateSelect) {
                stateSelect.value = state || "";
            }
        }, 500);

        // Set avatar preview
        const avatarPreview = document.getElementById('edit_avatar_preview');
        if (avatarPreview) {
            // Check if avatar already includes the BASE_URL
            if (avatar && avatar.startsWith('http')) {
                avatarPreview.src = avatar;
            } else {
                avatarPreview.src = BASE_URL + (avatar || 'uploads/avatars/default.png');
            }
        }

        $('#editUserModal').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        }).on('shown.bs.modal', function() {
            document.getElementById('edit_name').focus();
        }).on('hidden.bs.modal', function() {
            form.reset();
        });
    }

    function getStates(countryId) {
        if (!countryId) {
            console.error('Country ID is required.');
            return;
        }

        const formData = new FormData();
        formData.append('id', countryId);
        formData.append('dataType', 'get_states');

        sendData(formData);
    }

    function sendData(data) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', BASE_URL + 'ajaxUser', true);

        let isFormData = data instanceof FormData;

        if (!isFormData) {
            xhr.setRequestHeader('Content-Type', 'application/json');
        }

        xhr.onreadystatechange = function() {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    try {
                        const obj = JSON.parse(this.responseText);
                        const dataType = isFormData ? data.get('dataType') : data.dataType;

                        if (dataType === 'get_user_stats') {
                            updateStatistics(obj);
                        }

                        if (dataType === 'get_states') {
                            const stateSelects = {
                                add: document.getElementById('state'),
                                edit: document.getElementById('edit_state')
                            };

                            Object.values(stateSelects).forEach(select => {
                                if (select) {
                                    select.innerHTML = '<option value="">Select state</option>';

                                    if (obj.states && Array.isArray(obj.states)) {
                                        if (obj.states.length === 0) {
                                            select.innerHTML = '<option value="">No states available</option>';
                                        } else {
                                            obj.states.forEach(state => {
                                                const option = document.createElement('option');
                                                option.value = state.id;
                                                option.textContent = state.state;
                                                select.appendChild(option);
                                            });
                                        }
                                    } else {
                                        select.innerHTML = '<option value="">Error loading states</option>';
                                        console.error('Invalid states data received:', obj);
                                    }
                                }
                            });

                            if (!obj.states || obj.states.length === 0) {
                                showNotification('No states found for the selected country', 'info');
                            }
                        }

                        if (dataType === 'get_user_stats') {
                            if (obj.success) {
                                updateStatistics(
                                    obj.statistics.total || 0,
                                    obj.statistics.admin || 0,
                                    obj.statistics.customer || 0,
                                    obj.statistics.trash || 0,
                                    false // Not filtered
                                );
                            }
                            return;
                        }

                        if (dataType === 'add_user') {
                            if (obj.success) {
                                $('#addUserModal').modal('hide');
                                showNotification(obj.message, 'success');
                                refreshUsers();

                                // Update statistics after adding a user
                                const statsFormData = new FormData();
                                statsFormData.append('dataType', 'get_user_stats');
                                sendData(statsFormData);
                            } else {
                                showNotification(obj.message, 'danger');
                            }

                            const submitBtn = document.querySelector('#add_submit_btn');
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fa fa-user-plus"></i> Add User';
                            }
                        }

                        if (dataType === 'edit_user') {
                            const submitBtn = document.querySelector('#edit_submit_btn');
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = 'Save Changes';
                            }

                            if (obj.success) {
                                $('#editUserModal').modal('hide');
                                showNotification(obj.message, 'success');
                                refreshUsers();

                                // Update statistics after editing a user
                                const statsFormData = new FormData();
                                statsFormData.append('dataType', 'get_user_stats');
                                sendData(statsFormData);
                            } else {
                                showNotification(obj.message, 'danger');
                            }
                        }

                        if (dataType === 'get_users' || dataType === 'delete_user' || dataType === 'change_user_status') {
                            if (obj.table_html) {
                                document.getElementById('table_body').innerHTML = obj.table_html;
                                if (obj.message) {
                                    showNotification(obj.message, obj.success ? 'success' : 'danger');
                                }

                                // Update statistics after these operations
                                const statsFormData = new FormData();
                                statsFormData.append('dataType', 'get_user_stats');
                                sendData(statsFormData);
                            }
                        }

                        if (dataType === 'check_trash') {
                            if (obj.has_deleted_users) {
                                document.getElementById('table_body').innerHTML = obj.table_html;
                                showNotification(obj.message, 'success');
                            } else {
                                document.getElementById('table_body').innerHTML = '<tr><td colspan="5" class="text-center">Trash is empty</td></tr>';
                                showNotification('Trash is empty', 'info');
                            }

                            // Update statistics after checking trash
                            const statsFormData = new FormData();
                            statsFormData.append('dataType', 'get_user_stats');
                            sendData(statsFormData);
                        }

                        if (dataType === 'restore_user') {
                            if (obj.success) {
                                document.getElementById('table_body').innerHTML = obj.table_html;
                                showNotification(obj.message, 'success');

                                // Update statistics after restoring a user
                                const statsFormData = new FormData();
                                statsFormData.append('dataType', 'get_user_stats');
                                sendData(statsFormData);
                            } else {
                                showNotification(obj.message, 'danger');
                            }
                        }

                        if (dataType === 'force_delete_user') {
                            if (obj.success) {
                                document.getElementById('table_body').innerHTML = obj.table_html;
                                showNotification(obj.message, 'success');

                                // Update statistics after permanent deletion
                                const statsFormData = new FormData();
                                statsFormData.append('dataType', 'get_user_stats');
                                sendData(statsFormData);
                            } else {
                                showNotification(obj.message, 'danger');
                            }
                        }

                        if (dataType === 'search_users') {
                            if (obj.success) {
                                document.getElementById('table_body').innerHTML = obj.table_html;

                                // Update statistics based on search results
                                if (obj.users) {
                                    let totalUsers = obj.users.length;
                                    let adminUsers = 0;
                                    let customerUsers = 0;

                                    obj.users.forEach(user => {
                                        if (user.rank === 'admin') {
                                            adminUsers++;
                                        } else {
                                            customerUsers++;
                                        }
                                    });

                                    // For filtered results, maintain trash count
                                    const currentTrashCount = document.getElementById('trash-users').textContent;

                                    updateStatistics(
                                        totalUsers,
                                        adminUsers,
                                        customerUsers,
                                        currentTrashCount,
                                        true // Indicate these are filtered results
                                    );
                                }
                            } else {
                                showNotification(obj.message || 'No users found matching your search.', 'info');

                                // If no results, show zeros but keep trash count
                                updateStatistics(
                                    0,
                                    0,
                                    0,
                                    document.getElementById('trash-users').textContent,
                                    true
                                );
                            }
                        }

                        if (obj.table_html && !dataType.includes('search')) {
                            document.getElementById('table_body').innerHTML = obj.table_html;
                        }

                        if (dataType === 'get_country_name') {
                            const countryElement = document.getElementById('view_country');
                            if (countryElement) {
                                if (obj.success) {
                                    countryElement.textContent = obj.c_name;
                                    countryElement.setAttribute('data-country-id', obj.c_id);
                                } else {
                                    countryElement.textContent = 'Country not found';
                                    countryElement.setAttribute('data-country-id', '');
                                }
                            }
                        }

                        if (dataType === 'get_state_name') {
                            const stateElement = document.getElementById('view_state');
                            if (stateElement) {
                                if (obj.success) {
                                    stateElement.textContent = obj.s_name;
                                    stateElement.setAttribute('data-state-id', obj.s_id);
                                } else {
                                    stateElement.textContent = 'State not found';
                                    stateElement.setAttribute('data-state-id', '');
                                }
                            }
                        }

                        if (obj.message && !dataType) {
                            showNotification(obj.message, obj.success ? 'success' : 'danger');
                        }

                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        showNotification('An error occurred while processing the response. Please try again.', 'danger');

                        document.querySelectorAll('button[type="submit"]').forEach(btn => {
                            btn.disabled = false;
                            if (btn.closest('#addUserForm')) {
                                btn.innerHTML = '<i class="fa fa-user-plus"></i> Add User';
                            } else if (btn.closest('#editUserForm')) {
                                btn.innerHTML = 'Save Changes';
                            }
                        });
                    }
                }
            }
        };

        xhr.send(isFormData ? data : JSON.stringify(data));
    }

    function collectData() {
        const form = document.querySelector('#addUserForm');

        if (!validateAddForm(form)) {
            return false;
        }

        const formData = new FormData(form);
        formData.append('dataType', 'add_user');

        const submitBtn = document.querySelector('#add_submit_btn');
        submitBtn.disabled = true; // Prevent double submission
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

        sendData(formData);
    }

    function editUser() {
        const form = document.querySelector('#editUserForm');

        if (!validateEditForm(form)) {
            return false;
        }

        const formData = new FormData(form);
        formData.append('dataType', 'edit_user');

        const submitBtn = document.querySelector('#edit_submit_btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

        sendData(formData);
    }

    function validateAddForm(form) {
        if (!form) {
            console.error('Form not found');
            return false;
        }

        clearErrors();
        let isValid = true;

        const inputs = {
            name: form.querySelector('input[name="name"]'),
            email: form.querySelector('input[name="email"]'),
            password: form.querySelector('input[name="password"]'),
            confirmPassword: form.querySelector('input[name="confirm_password"]'),
            gender: form.querySelector('select[name="gender"]'),
            phone: form.querySelector('input[name="phone"]'),
            address: form.querySelector('textarea[name="address"]'),
            country: form.querySelector('select[name="country"]'),
            state: form.querySelector('select[name="state"]'),
            zip: form.querySelector('input[name="zip"]'),
            rank: form.querySelector('select[name="rank"]'),
            status: form.querySelector('select[name="status"]'),
            avatar: form.querySelector('input[name="avatar"]')
        };

        const requiredFields = ['name', 'email', 'password', 'confirmPassword', 'rank', 'status'];

        requiredFields.forEach(field => {
            const input = inputs[field];
            if (!input || !input.value.trim()) {
                showError(input, `${field.replace(/([A-Z])/g, ' $1').toLowerCase()} is required`);
                isValid = false;
            }
        });

        if (inputs.name && inputs.name.value.trim()) {
            if (!isValidName(inputs.name.value)) {
                showError(inputs.name, 'Name must be 4-30 characters long and contain only letters and spaces');
                isValid = false;
            }
        }

        if (inputs.email && inputs.email.value.trim()) {
            if (!isValidEmail(inputs.email.value)) {
                showError(inputs.email, 'Please enter a valid email address');
                isValid = false;
            }
        }

        if (!inputs.password || !inputs.password.value.trim()) {
            showError(inputs.password, 'Password is required for new users');
            isValid = false;
        } else if (!isValidPassword(inputs.password.value)) {
            showError(inputs.password, 'Password must be 8-64 characters and include uppercase, lowercase, number and special character');
            isValid = false;
        }

        if (!inputs.confirmPassword || !inputs.confirmPassword.value.trim()) {
            showError(inputs.confirmPassword, 'Please confirm your password');
            isValid = false;
        } else if (inputs.password.value !== inputs.confirmPassword.value) {
            showError(inputs.confirmPassword, 'Passwords do not match');
            isValid = false;
        }

        if (inputs.rank && inputs.rank.value) {
            if (!['admin', 'customer'].includes(inputs.rank.value)) {
                showError(inputs.rank, 'Invalid role selected');
                isValid = false;
            }
        }

        if (inputs.status && inputs.status.value) {
            if (!['0', '1'].includes(inputs.status.value)) {
                showError(inputs.status, 'Invalid status selected');
                isValid = false;
            }
        }

        if (inputs.avatar && inputs.avatar.files.length > 0) {
            if (!isValidAvatar(inputs.avatar.files[0])) {
                showError(inputs.avatar, 'Please upload a JPEG or PNG file under 2MB');
                isValid = false;
            }
        }

        if (inputs.address && inputs.address.value.trim()) {
            if (!isValidAddress(inputs.address.value)) {
                showError(inputs.address, 'Address must be 4-100 characters long and contain only letters, numbers, spaces and basic punctuation');
                isValid = false;
            }
        }

        if (inputs.phone && inputs.phone.value.trim()) {
            if (!isValidPhone(inputs.phone.value)) {
                showError(inputs.phone, 'Please enter a valid phone number');
                isValid = false;
            }
        }

        if (inputs.zip && inputs.zip.value.trim()) {
            if (!isValidZip(inputs.zip.value)) {
                showError(inputs.zip, 'Please enter a valid zip/postal code');
                isValid = false;
            }
        }

        return isValid;
    }

    function validateEditForm(form) {
        if (!form) {
            console.error('Form not found');
            return false;
        }

        clearErrors();
        let isValid = true;

        const inputs = {
            id: form.querySelector('input[name="id"]'),
            name: form.querySelector('input[name="name"]'),
            email: form.querySelector('input[name="email"]'),
            password: form.querySelector('input[name="password"]'),
            gender: form.querySelector('select[name="gender"]'),
            phone: form.querySelector('input[name="phone"]'),
            address: form.querySelector('textarea[name="address"]'),
            country: form.querySelector('select[name="country"]'),
            state: form.querySelector('select[name="state"]'),
            zip: form.querySelector('input[name="zip"]'),
            rank: form.querySelector('select[name="rank"]'),
            status: form.querySelector('select[name="status"]'),
            avatar: form.querySelector('input[name="avatar"]')
        };

        const requiredFields = ['name', 'email', 'rank', 'status'];

        requiredFields.forEach(field => {
            const input = inputs[field];
            if (!input || !input.value.trim()) {
                showError(input, `${field.replace(/([A-Z])/g, ' $1').toLowerCase()} is required`);
                isValid = false;
            }
        });

        if (inputs.name && inputs.name.value.trim()) {
            if (!isValidName(inputs.name.value)) {
                showError(inputs.name, 'Name must be 4-30 characters long and contain only letters and spaces');
                isValid = false;
            }
        }

        if (inputs.email && inputs.email.value.trim()) {
            if (!isValidEmail(inputs.email.value)) {
                showError(inputs.email, 'Please enter a valid email address');
                isValid = false;
            }
        }

        if (inputs.password && inputs.password.value.trim() !== '') {
            if (!isValidPassword(inputs.password.value)) {
                showError(inputs.password, 'Password must be 8-64 characters and include uppercase, lowercase, number and special character');
                isValid = false;
            }
        }

        if (inputs.rank && inputs.rank.value) {
            if (!['admin', 'customer'].includes(inputs.rank.value)) {
                showError(inputs.rank, 'Invalid role selected');
                isValid = false;
            }
        }

        if (inputs.status && inputs.status.value) {
            if (!['0', '1'].includes(inputs.status.value)) {
                showError(inputs.status, 'Invalid status selected');
                isValid = false;
            }
        }

        if (inputs.avatar && inputs.avatar.files.length > 0) {
            if (!isValidAvatar(inputs.avatar.files[0])) {
                showError(inputs.avatar, 'Please upload a JPEG or PNG file under 2MB');
                isValid = false;
            }
        }

        if (inputs.address && inputs.address.value.trim()) {
            if (!isValidAddress(inputs.address.value)) {
                showError(inputs.address, 'Address must be 4-100 characters long and contain only letters, numbers, spaces and basic punctuation');
                isValid = false;
            }
        }

        if (inputs.phone && inputs.phone.value.trim()) {
            if (!isValidPhone(inputs.phone.value)) {
                showError(inputs.phone, 'Please enter a valid phone number');
                isValid = false;
            }
        }

        if (inputs.zip && inputs.zip.value.trim()) {
            if (!isValidZip(inputs.zip.value)) {
                showError(inputs.zip, 'Please enter a valid zip/postal code');
                isValid = false;
            }
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    function isValidPassword(password) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,64}$/;
        return regex.test(password);
    }

    function isValidName(name) {
        const nameRegex = /^[a-zA-Z\s]{4,30}$/;
        return nameRegex.test(name);
    }

    function isValidAddress(address) {
        const addressRegex = /^[a-zA-Z0-9\s\.,-]{4,100}$/;
        return addressRegex.test(address);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^\+?\d{7,15}$/;
        return phoneRegex.test(phone.replace(/\s|[-().]/g, ""));
    }

    function isValidZip(zip) {
        const regex = /^[A-Za-z0-9\s\-]{3,10}$/;
        return regex.test(zip);
    }

    function isValidAvatar(file) {
        if (!file) return true;

        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (file.size > maxSize) {
            showNotification('File is too large. Maximum size is 2MB.', 'warning');
            return false;
        }

        if (!allowedTypes.includes(file.type)) {
            showNotification('Invalid file type. Only JPG and PNG files are allowed.', 'warning');
            return false;
        }

        return true;
    }

    function showError(input, message) {
        if (!input) return;

        const formGroup = input.closest('.form-group');
        if (formGroup) {
            formGroup.classList.add('has-error');
            const helpBlock = formGroup.querySelector('.help-block') || createHelpBlock(formGroup);
            helpBlock.textContent = message;
        }
    }

    function createHelpBlock(formGroup) {
        const helpBlock = document.createElement('span');
        helpBlock.className = 'help-block';
        formGroup.appendChild(helpBlock);
        return helpBlock;
    }

    function clearErrors() {
        const formGroups = document.querySelectorAll('.form-group.has-error');
        formGroups.forEach(group => {
            group.classList.remove('has-error');
            const helpBlock = group.querySelector('.help-block');
            if (helpBlock) {
                helpBlock.textContent = '';
            }
        });
    }

    // Initialize statistics counters
    function updateStatistics(totalCount = 0, adminCount = 0, customerCount = 0, trashCount = 0, isFiltered = false) {
        document.getElementById('total-users').textContent = totalCount;
        document.getElementById('admin-users').textContent = adminCount;
        document.getElementById('customer-users').textContent = customerCount;
        document.getElementById('trash-users').textContent = trashCount;

        // Show/hide the filtered indicator
        const filteredIndicator = document.getElementById('stats-filtered-message');
        if (filteredIndicator) {
            filteredIndicator.style.display = isFiltered ? 'block' : 'none';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Request initial stats
        const formData = new FormData();
        formData.append('dataType', 'get_user_stats');
        sendData(formData);
    });

    function refreshUsers() {
        const formData = new FormData();
        formData.append('dataType', 'get_users');
        sendData(formData);
    }

    function deleteUser(id) {
        if (confirm("This will move the user to the trash. You can restore it later from the Trash.")) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('dataType', 'delete_user');
            sendData(formData);
        }
    }

    function restoreUser(id) {
        if (confirm("Are you sure you want to restore this user?")) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('dataType', 'restore_user');
            sendData(formData);
        }
    }

    function forceDeleteUser(id) {
        if (confirm("Are you sure you want to permanently delete this user? <strong>This action cannot be undone.</strong>")) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('dataType', 'force_delete_user');
            sendData(formData);
        }
    }

    let isTrashView = false;

    function toggleTrashView() {
        isTrashView = !isTrashView;
        const trashBtn = document.getElementById('trash_btn');
        const trashBtnText = document.getElementById('trash_btn_text');
        const tableTitle = document.getElementById('table_title');

        if (isTrashView) {
            trashBtn.classList.remove('btn-secondary');
            trashBtn.classList.add('btn-warning');
            trashBtnText.textContent = 'View Active Users';
            tableTitle.innerHTML = '<i class="fa fa-trash-o"></i> Trash';
            checkTrash();
        } else {
            trashBtn.classList.remove('btn-warning');
            trashBtn.classList.add('btn-secondary');
            trashBtnText.textContent = 'View Trash';
            tableTitle.innerHTML = '<i class="fa fa-list"></i> Active Users';
            refreshUsers();
        }
    }

    function checkTrash() {
        const formData = new FormData();
        formData.append('dataType', 'check_trash');
        sendData(formData);
    }

    function getDeletedUsers() {
        const formData = new FormData();
        formData.append('dataType', 'get_deleted_users');
        sendData(formData);
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

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function showEditFromView() {
        if (window.currentViewedUserId) {
            $('#viewCustomerModal').modal('hide');
            const userId = window.currentViewedUserId;
            const name = document.getElementById('view_name').textContent;
            const email = document.getElementById('view_email').textContent;
            const phone = document.getElementById('view_phone').textContent;
            const address = document.getElementById('view_address').textContent;
            const countryId = document.getElementById('view_country').getAttribute('data-country-id');
            const stateId = document.getElementById('view_state').getAttribute('data-state-id');
            const zip = document.getElementById('view_zip').textContent;
            const status = document.getElementById('view_status').textContent === 'Active' ? '1' : '0';
            const gender = document.getElementById('view_gender').textContent;
            const rank = document.getElementById('view_rank').textContent;
            const avatar = document.getElementById('view_avatar').src;

            showEdit(
                userId,
                name,
                email,
                phone,
                address,
                countryId,
                stateId,
                zip,
                status,
                gender,
                rank,
                avatar
            );
        }
    }

    function changeUserStatus(userId, status) {
        const formData = new FormData();
        formData.append('id', userId);
        formData.append('status', status);
        formData.append('dataType', 'change_user_status');
        sendData(formData);
    }

    function refreshCustomers() {
        if (isTrashView) {
            checkTrash();
        } else {
            refreshUsers();
        }
    }

    function previewAvatar(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];

        if (file) {
            if (!isValidAvatar(file)) {
                input.value = '';
                if (preview) {
                    preview.src = BASE_URL + 'uploads/avatars/default.png';
                }
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                }

                const fileNameDisplay = input.parentElement.nextElementSibling;
                if (fileNameDisplay && fileNameDisplay.classList.contains('form-control')) {
                    fileNameDisplay.value = file.name;
                }

                const viewAvatar = document.getElementById('view_avatar');
                if (viewAvatar) {
                    viewAvatar.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        } else if (preview) {
            preview.src = BASE_URL + 'uploads/avatars/default.png';
        }
    }

    let searchTimeout = null;
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');

    function updateFilterButtonsVisibility() {
        const searchHasValue = searchInput.value.trim().length > 0;
        const filtersActive = roleFilter.value !== '' || statusFilter.value !== '';

        clearSearchBtn.style.display = searchHasValue ? 'inline-block' : 'none';
        clearFiltersBtn.style.display = filtersActive ? 'inline-block' : 'none';

        roleFilter.parentElement.classList.toggle('user-filter-active', roleFilter.value !== '');
        statusFilter.parentElement.classList.toggle('user-filter-active', statusFilter.value !== '');
    }

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        const role = roleFilter.value;
        const status = statusFilter.value;

        updateFilterButtonsVisibility();

        if (searchTerm.length === 0 && role === '' && status === '') {
            refreshUsers();
            return;
        }

        const formData = new FormData();
        formData.append('dataType', 'search_users');
        formData.append('search', searchTerm);
        formData.append('role', role);
        formData.append('status', status);

        sendData(formData);
    }

    function clearSearch() {
        searchInput.value = '';
        updateFilterButtonsVisibility();
        performSearch();
    }

    function clearFilters() {
        roleFilter.value = '';
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

    if (roleFilter) {
        roleFilter.addEventListener('change', performSearch);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', performSearch);
    }

    // Initialize buttons visibility
    updateFilterButtonsVisibility();

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

    function deleteUser(id) {
        showConfirmModal(
            "This will move the user to the trash. You can restore it later from the Trash section.",
            function() {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('dataType', 'delete_user');
                sendData(formData);
            },
            'btn-warning',
            'fa-trash-o',
            'Move to Trash'
        );
    }

    function restoreUser(id) {
        showConfirmModal(
            "Are you sure you want to restore this user?",
            function() {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('dataType', 'restore_user');
                sendData(formData);
            },
            'btn-success',
            'fa-undo',
            'Restore'
        );
    }

    function forceDeleteUser(id) {
        showConfirmModal(
            "Are you sure you want to permanently delete this user? <strong>This action cannot be undone.</strong>",
            function() {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('dataType', 'force_delete_user');
                sendData(formData);
            },
            'btn-danger',
            'fa-trash',
            'Delete Permanently'
        );
    }

    function changeUserStatus(userId, status) {
        const action = status === 1 ? 'activate' : 'deactivate';
        const statusText = status === 1 ? 'active' : 'inactive';

        showConfirmModal(
            `Are you sure you want to ${action} this user? Their account will be ${statusText}.`,
            function() {
                const formData = new FormData();
                formData.append('id', userId);
                formData.append('status', status);
                formData.append('dataType', 'change_user_status');
                sendData(formData);
            },
            status === 1 ? 'btn-success' : 'btn-warning',
            status === 1 ? 'fa-check-circle' : 'fa-ban',
            status === 1 ? 'Activate' : 'Deactivate'
        );
    }

    function viewUserOrders() {
        if (window.currentViewedUserUrl) {
            // Close current modal and show loading indicator
            $('#viewCustomerModal').modal('hide');

            // Redirect to orders page with the user filter
            window.location.href = BASE_URL + 'admin/orders?user=' + encodeURIComponent(window.currentViewedUserUrl);
        } else {
            showAlert('error', 'User URL not found');
        }
    }
</script>

</html>