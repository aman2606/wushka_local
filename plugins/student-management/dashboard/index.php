<?php

spl_autoload_register('wsm_dashboard_register');

function wsm_dashboard_register($class) {


    $namespace = 'WSM\Dashboard';

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

$menuController = new WSM\Dashboard\MenuController();

$menuController->registerMenus();