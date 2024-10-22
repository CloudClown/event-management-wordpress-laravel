<?php
function astra_child_enqueue_styles()
{
    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

    // Enqueue the parent theme stylesheet
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');

    // Enqueue the child theme stylesheet, making it dependent on the parent theme stylesheet
    wp_enqueue_style('astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), wp_get_theme()->get('Version'));

    // list of events styles and scripts
    wp_enqueue_style('event-list-style', get_stylesheet_directory_uri() . '/assets/css/event-list.css', array(), '1.0.0');
    wp_enqueue_script('event-list-script', get_stylesheet_directory_uri() . '/assets/js/event-list.js', array('jquery'), '1.0.0', true);

    // Conditionally enqueue event details CSS
    if (is_page('details') || get_query_var('event_id')) {
        wp_enqueue_style('event-details-style', get_stylesheet_directory_uri() . '/assets/css/event-details.css', array(), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');


function event_detail_cards_shortcode($atts)
{
    // Static data for three events (replace with API data in the future)
    $events = array(
        array(
            'title' => 'Summer Music Festival',
            'date' => 'July 15, 2023',
            'place' => 'Central Park',
            'image' => 'https://picsum.photos/400/225.jpg',
        ),
        array(
            'title' => 'Tech Conference 2023',
            'date' => 'September 22, 2023',
            'place' => 'Convention Center',
            'image' => 'https://picsum.photos/400/225.jpg',
        ),
        array(
            'title' => 'Food & Wine Expo',
            'date' => 'November 5, 2023',
            'place' => 'City Hall',
            'image' => 'https://picsum.photos/400/225.jpg',
        ),
    );

    // Load HTML template
    ob_start();
    include(plugin_dir_path(__FILE__) . './card-html/event-cards-template.php');
    $output = ob_get_clean();

    return $output;
}

add_shortcode('event_cards', 'event_detail_cards_shortcode');

function event_detail_cards_all_shortcode($atts)
{
    // Simulate 15 events (replace with API data in the future)
    $events = array();
    for ($i = 1; $i <= 15; $i++) {
        $events[] = array(
            'title' => "Event $i",
            'date' => date('F j, Y', strtotime("+$i days")),
            'place' => "Location $i",
            'image' => "https://picsum.photos/400/225?random=$i",
        );
    }

    $events_per_page = 5;

    if (isset($_POST['pagination_nonce_field']) && wp_verify_nonce($_POST['pagination_nonce_field'], 'pagination_nonce')) {
        $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    } else {
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    }

    $total_events = count($events);
    $total_pages = ceil($total_events / $events_per_page);
    $offset = ($paged - 1) * $events_per_page;
    $paginated_events = array_slice($events, $offset, $events_per_page);

    // Load HTML template
    ob_start();
    include(plugin_dir_path(__FILE__) . './card-html/event-all-cards.php');
    $output = ob_get_clean();

    return $output;
}

add_shortcode('event_all_cards', 'event_detail_cards_all_shortcode');

function event_list_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'category' => '',
        'sort' => 'date',
        'view' => 'grid',
        'per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        'limit' => -1, // -1 means no limit
    ), $atts);

    // Fetch events from your API
    $api_url = 'http://localhost:8000/api/v1/events';
    if (!empty($atts['category'])) {
        $api_url .= '/category/' . $atts['category'];
    }

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return "Error fetching events: " . $response->get_error_message();
    }

    $events = json_decode(wp_remote_retrieve_body($response), true);

    if (!is_array($events)) {
        return "No events found or invalid response from API.";
    }

    // Sort events
    if ($atts['sort'] === 'date') {
        usort($events, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
    }

    // Apply limit
    if ($atts['limit'] > 0) {
        $events = array_slice($events, 0, $atts['limit']);
        $total_events = count($events);
        $total_pages = 1;
    } else {
        $total_events = count($events);
        $total_pages = ceil($total_events / $atts['per_page']);
        $offset = ($atts['paged'] - 1) * $atts['per_page'];
        $events = array_slice($events, $offset, $atts['per_page']);
    }

    // Start output buffering
    ob_start();

    // Include the template
    include(get_stylesheet_directory() . '/templates/event-list-template.php');

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('event_list', 'event_list_shortcode');

function event_details_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'id' => get_query_var('event_id', 0),
    ), $atts);

    if (empty($atts['id'])) {
        return "No event ID provided.";
    }

    // Fetch event details from your API
    $api_url = 'http://localhost:8000/api/v1/events/' . $atts['id'];
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return "Error fetching event details: " . $response->get_error_message();
    }

    $event = json_decode(wp_remote_retrieve_body($response), true);

    if (!is_array($event)) {
        return "Event not found or invalid response from API.";
    }

    // Start output buffering
    ob_start();

    // Include the template
    include(get_stylesheet_directory() . '/templates/event-details-template.php');

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('event_details', 'event_details_shortcode');

// Add rewrite rule for event details
function custom_rewrite_rules()
{
    add_rewrite_rule('^details/([0-9]+)/?', 'index.php?pagename=details&event_id=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_rules');

function custom_query_vars($vars)
{
    $vars[] = 'event_id';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');
