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

function em_get_event_details() {
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $event = em_get_event($event_id);

    if ($event) {
        $date = date('F j, Y', strtotime($event->date));
        $time = date('g:i A', strtotime($event->time)); // This line ensures correct time format

        echo '<div class="em-event-details">';
        echo '<h3 class="em-event-title">' . esc_html($event->title) . '</h3>';
        echo '<p class="em-event-description"><strong>Description:</strong> ' . esc_html($event->description) . '</p>';
        echo '<p class="em-event-info"><strong>Date:</strong> ' . esc_html($date) . '</p>';
        echo '<p class="em-event-info"><strong>Time:</strong> ' . esc_html($time) . '</p>';
        echo '<p class="em-event-info"><strong>Location:</strong> ' . esc_html($event->location) . '</p>';
        echo '<p class="em-event-info"><strong>Category:</strong> <span class="em-event-category">' . esc_html($event->category) . '</span></p>';
        echo '</div>';
    } else {
        echo '<p>Event not found.</p>';
    }

    wp_die();
}
add_action('wp_ajax_get_event_details', 'em_get_event_details');