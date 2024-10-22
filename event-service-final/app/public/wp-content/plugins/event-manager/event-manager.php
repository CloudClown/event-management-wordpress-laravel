<?php
/*
Plugin Name: Event Manager
Description: Manage events using external API
Version: 1.0
Author: Your Name
*/

// Prevent direct access to the plugin file
if (!defined('ABSPATH')) {
    exit;
}

// Include other necessary files
require_once(plugin_dir_path(__FILE__) . 'includes/admin-menu.php');
require_once(plugin_dir_path(__FILE__) . 'includes/event-functions.php');

// Add action to create admin menu
add_action('admin_menu', 'em_create_admin_menu');

define('LARAVEL_APP_URL', 'http://localhost:8000');