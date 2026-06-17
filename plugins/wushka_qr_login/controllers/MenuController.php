<?php

namespace QRL;

class MenuController
{

    public function registerMenus()
    {

        add_action('admin_menu', [$this, 'pluginMenu']);
    }

    public function pluginMenu()
    {

        add_menu_page(
            __('QR Login', 'textdomain'),
            'QR Login',
            'manage_options',
            'qrl',
            [$this, 'pluginDashboard'],
            'dashicons-screenoptions',
            71
        );

    }

    public function pluginDashboard()
    {
        if (is_file(plugin_dir_path(__FILE__) . '../views/qrl-dashboard.php')) {


            include_once plugin_dir_path(__FILE__) . '../views/qrl-dashboard.php';
        }
    }

  
}
