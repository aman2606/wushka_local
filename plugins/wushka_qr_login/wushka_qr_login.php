<?php

/*
 *
 * Plugin Name: Wushka QR Login
 * Description: This plugin is intended to make settings of QR login Functionality.
 *
 */

define('qrl_dashboard_assets',plugin_dir_url(__FILE__ ).'assets');

require_once  __DIR__ . '/functions.php';

if (is_admin()) {

    function qr_login_register($class)
    {
        $namespace = 'QRL';

        if (strpos($class, $namespace) !== 0) {
            return;
        }

        $class = str_replace($namespace, '', $class);

        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        $directory = plugin_dir_path(__FILE__);

        $path = $directory . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $class;


        if (file_exists($path)) {
            require_once($path);
        }
    }

   spl_autoload_register('qr_login_register');

    $menuController = new QRL\MenuController();

    $menuController->registerMenus();
}
