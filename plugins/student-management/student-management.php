<?php

/*
 *
 * Plugin Name: Wushka Student Management
 * Description: This plugin is intended to move students across the schools inluding Archive students.
 *
 */

define('wsm_dashboard_assets',plugin_dir_url(__FILE__ ).'assets');

require_once  __DIR__ .'/dashboard/functions.php';

if(is_admin()){

    require_once  __DIR__ .'/dashboard/index.php';

 }

