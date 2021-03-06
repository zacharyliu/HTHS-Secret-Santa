<link href="<?php echo base_url('css/admin.css') ?>" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <h3>Admin Panel</h3>
            <ul class="nav nav-sidebar">
                <?php
                $sidebar = array(
                    /**
                     * Item settings
                     * text: Displayed text in navbar
                     * route: base_url() URL route
                     *
                     */
                    array('text' => 'Group Management', 'route' => 'admin/groups'),
                    array('text' => 'General Settings', 'route' => 'admin/general'),
                    array('text' => 'Advanced Settings', 'route' => 'admin/advanced')
                );
                $sidebar_html = "";
                foreach ($sidebar as $item) {
                    // Check if current route matches item
                    $current = ($this->router->uri->uri_string == $item['route']);

                    // Append item to specified side
                    $sidebar_html .= '<li ' . ($current ? 'class="active" ' : '') . '><a  href="' . base_url($item['route']) . '">' . $item['text'] . '</a></li>';

                }
                ?>
                <?= $sidebar_html ?>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <? //begin main content?>