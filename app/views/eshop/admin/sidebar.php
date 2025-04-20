<!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <p class="centered"><a href="<?= BASE_URL ?>profile"><img src="<?= ASSETS . THEME ?>/admin/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
            <h5 class="centered" style="margin-bottom: 2px;"><?= htmlspecialchars($data['user_data']['name'], ENT_QUOTES, 'UTF-8') ?></h5>
            <p class="centered" style="color: #fffefe99; margin-bottom: 11px; font-size: 11px;"><?= htmlspecialchars($data['user_data']['email'], ENT_QUOTES, 'UTF-8') ?></p>
            <li class="mt">
                <a href="<?= BASE_URL ?>admin">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= BASE_URL ?>admin/users">
                    <i class="fa fa-barcode fa-fw"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= BASE_URL ?>admin/products">
                    <i class="fa fa-barcode fa-fw"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= BASE_URL ?>admin/categories">
                    <i class="fa fa-list fa-fw"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= BASE_URL ?>admin/orders">
                    <i class="fa fa-reorder fa-fw"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="<?= BASE_URL ?>admin/settings">
                    <i class="fa fa-cogs fa-fw"></i>
                    <span>Settings</span>
                </a>
                <ul class="sub">
                    <li><a href="<?= BASE_URL ?>admin/settings/slider_images">Slider Images</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a href="<?= BASE_URL ?>admin/backup">
                    <i class="fa fa-hdd-o fa-fw"></i>
                    <span>Website Backup</span>
                </a>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->