<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link navbar-dark">
        <img src="<?= base_url('assets/img/logo_toko.png'); ?>" alt="POS F&B" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            <b class="text-white text-logo">SiPeKu</b>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/img/profile/' . $user->img_user ); ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info ml-2">
                <a href="#" class="d-block"><i class="fa fa-circle text-success text-sm mr-2"></i><?= session()->get('username'); ?></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <?php $db = \Config\Database::connect(); ?>
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-compact" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Query Menu -->
                <?php
                $role_id = session()->get('role');
                $queryMenu = "SELECT `menu`.*
                                    FROM `menu` JOIN `access` 
                                    ON `menu`.`id_menu` = `access`.`id_menu`
                                    WHERE `access`.`id_role` = $role_id
                                    AND `menu`.`fungsi_menu` != 2
                                    ORDER BY `access`.`id_menu` ASC";
                $menu = $db->query($queryMenu)->getResultArray();
                ?>

                <!-- Looping Menu -->
                <?php foreach ($menu as $m) : ?>
                    <?php if($m['is_aktif'] == 1 && $m['deleted_at'] == null) : ?>
                        <?php if ($m['sub_menu'] == 0) : ?>
                            <?php if ($title == $m['nm_menu']) : ?>
                                <li class="nav-item has-treeview menu-open">
                                    <a href="<?= base_url($m['url']); ?>" class="nav-link active">
                            <?php else : ?>
                                <li class="nav-item has-treeview">
                                    <a href="<?= base_url($m['url']); ?>" class="nav-link">
                            <?php endif; ?>
                                    <p>
                                        <?= $m['nm_menu']; ?>
                                    </p>
                                    </a>
                                </li>
                        <?php else : ?>
                            <?php if ($title == $m['nm_menu']) : ?>
                                <li class="nav-item has-treeview menu-open">
                                    <a href="#" class="nav-link active">
                            <?php else : ?>
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                            <?php endif; ?>
                                    <p>
                                        <?= $m['nm_menu']; ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <!-- Query Sub Menu -->
                                        <?php
                                        $menuId = $m['id_menu'];
                                        $querySubMenu = "SELECT *
                                                FROM `submenu`
                                                WHERE `id_menu` = $menuId
                                                AND `is_aktive` = 1";
                                        $subMenu = $db->query($querySubMenu)->getResultArray();
                                        ?>
                                        <!-- Looping Sub Menu -->
                                        <?php foreach ($subMenu as $sm) : ?>
                                            <?php if($sm['delete_at'] == null) : ?>
                                                <?php if ($sub_title == $sm['title']) : ?>
                                                    <li class="nav-item">
                                                        <a href="<?= base_url($sm['sub_url']); ?>" class="nav-link active">
                                                <?php else : ?>
                                                    <li class="nav-item">
                                                        <a href="<?= base_url($sm['sub_url']); ?>" class="nav-link">
                                                <?php endif; ?>
                                                        <i class="<?= $sm['icon']; ?> nav-icon"></i>
                                                        <p><?= $sm['title']; ?></p>
                                                        </a>
                                                </li>
                                            <?php endif ;?>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>