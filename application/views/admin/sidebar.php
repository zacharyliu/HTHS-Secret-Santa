<link href="<?php echo base_url('css/admin.css')?>" rel="stylesheet">
<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <?php
        $sidebar = array(
            /**
             * Item settings
             * text: Displayed text in navbar
             * route: base_url() URL route
             *
             */
            array('text'=>'Group Management', 'route'=>'admin/groups'),
            array('text'=>'General Settings', 'route'=>'admin/general')
        );
        $sidebar_html="";
        foreach ($sidebar as $item) {
            // Check if current route matches item
            $current = ($this->router->uri->uri_string == $item['route']);

            // Append item to specified side
            $sidebar_html .= '<li ' . ($current ? 'class="active" ' : '') . '><a  href="' . base_url($item['route']) . '">' . $item['text'] . '</a></li>';

        }
        ?>
        <?=$sidebar_html?>
    </ul>
</div>