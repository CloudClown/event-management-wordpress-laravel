<?php
function astra_child_enqueue_styles() {
    // Enqueue the parent theme stylesheet
    wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');

    // event card 
    wp_enqueue_style('event-cards-style', get_stylesheet_directory_uri() . './card-html/event-cards-style.css');
    wp_enqueue_style('event-all-cards-style', get_stylesheet_directory_uri() . './card-html/event-all-cards-style.css');

    // Enqueue the child theme stylesheet, making it dependent on the parent theme stylesheet
    wp_enqueue_style('astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');

function event_detail_cards_shortcode($atts) {
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
    include(plugin_dir_path(__FILE__).'./card-html/event-cards-template.php');
    $output = ob_get_clean();

    return $output;
}

add_shortcode('event_cards', 'event_detail_cards_shortcode');

function event_detail_cards_all_shortcode($atts) {
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