<?php
// event-shortcode.php

function event_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'sort' => 'date',
        'view' => 'grid',
        'per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    ), $atts);

    // Fetch events from your API
    $api_url = 'http://localhost:8000/api/v1/events';
    if (!empty($atts['category'])) {
        $api_url .= '/category/' . $atts['category'];
    }

    $response = wp_remote_get($api_url);
    $events = json_decode(wp_remote_retrieve_body($response), true);

    // Sort events
    if ($atts['sort'] === 'date') {
        usort($events, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
    }

    // Pagination
    $total_events = count($events);
    $total_pages = ceil($total_events / $atts['per_page']);
    $offset = ($atts['paged'] - 1) * $atts['per_page'];
    $events = array_slice($events, $offset, $atts['per_page']);

    // Start output buffering
    ob_start();

    // Include the template
    include(get_stylesheet_directory() . '/templates/event-list-template.php');

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('event_list', 'event_list_shortcode');