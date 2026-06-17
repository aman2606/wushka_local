<?php

namespace WSM\Dashboard;

class MenuController
{

    public function registerMenus()
    {

        add_action('admin_menu', [$this, 'pluginMenu']);
    }

    public function pluginMenu()
    {

        add_menu_page(
            __('Transfer Users', 'textdomain'),
            'Transfer Users',
            'manage_options',
            'wsm',
            [$this, 'pluginDashboard'],
            'dashicons-external',
            70
        );

        // add_submenu_page('wsm', __('Transfer Students'), __('Transfer Students'), 'manage_options', 'wsm_student',[$this, 'pluginDashboardTeacher']);
        add_submenu_page('wsm', __('Transfer Students'), __('Transfer Students'), 'manage_options', 'wsm', [$this, 'pluginDashboard']);
        add_submenu_page('wsm', __('Transfer Teachers'), __('Transfer Teachers'), 'manage_options', 'wsm_teacher', [$this, 'pluginDashboardTeacher']);
        add_submenu_page('wsm', __('Transfer Class'), __('Transfer Class'), 'manage_options', 'wsm_class', [$this, 'pluginDashboardClass']);
        //add_submenu_page('wsm', __('Clear Old Data'), __('Clear Old Data'), 'manage_options', 'wsm_old_data', [$this, 'pluginDashboardClearOldData']);
    }

    public function pluginDashboard()
    {


        if (is_file(plugin_dir_path(__FILE__) . '../views/wsm-dashboard.php')) {


            include_once plugin_dir_path(__FILE__) . '../views/wsm-dashboard.php';
        }
    }

    public function pluginDashboardTeacher()
    {

        if (is_file(plugin_dir_path(__FILE__) . '../views/wsm-dashboard.php')) {


            include_once plugin_dir_path(__FILE__) . '../views/wsm-teacher-dashboard.php';
        }
    }

    public function pluginDashboardClass()
    {

        if (is_file(plugin_dir_path(__FILE__) . '../views/wsm-transfer-class-dashboard.php')) {


            include_once plugin_dir_path(__FILE__) . '../views/wsm-transfer-class-dashboard.php';
        }
    }

    public function pluginDashboardClearOldData()
    {


        if (is_file(plugin_dir_path(__FILE__) . '../views/wsm-clear-old-data.php')) {


            include_once plugin_dir_path(__FILE__) . '../views/wsm-clear-old-data.php';
        }
    }
}
