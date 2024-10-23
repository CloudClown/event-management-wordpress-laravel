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
        'sort' => 'date_desc',
        'view' => 'grid',
        'per_page' => 10,
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        'limit' => -1, // -1 means no limit
    ), $atts);

    // Fetch events from your API
    $api_url = 'http://localhost:8000/api/v1/events';
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return "Error fetching events: " . $response->get_error_message();
    }

    $events = json_decode(wp_remote_retrieve_body($response), true);

    if (!is_array($events)) {
        return "No events found or invalid response from API.";
    }

    // Extract unique categories from events
    $categories = array_unique(array_column($events, 'category'));

    // Apply category filter if set
    if (!empty($_GET['category'])) {
        $events = array_filter($events, function ($event) {
            return isset($event['category']) && $event['category'] == $_GET['category'];
        });
    }

    // Sort events
    $sort_order = isset($_GET['sort']) ? $_GET['sort'] : $atts['sort'];
    usort($events, function ($a, $b) use ($sort_order) {
        return ($sort_order === 'date_asc')
            ? strtotime($a['date']) - strtotime($b['date'])
            : strtotime($b['date']) - strtotime($a['date']);
    });

    // Apply limit and pagination
    $total_events = count($events);
    if ($atts['limit'] > 0) {
        $events = array_slice($events, 0, $atts['limit']);
        $total_pages = 1;
    } else {
        $total_pages = ceil($total_events / $atts['per_page']);
        $offset = ($atts['paged'] - 1) * $atts['per_page'];
        $events = array_slice($events, $offset, $atts['per_page']);
    }

    // Start output buffering
    ob_start();

    // Output CSS
    echo '<style>';
    include(get_stylesheet_directory() . '/css/event-list.css');
    echo '</style>';

?>
    <div class="event-list-container">
        <div class="event-list-card">
            <?php if ($atts['limit'] <= 0): ?>
                <!-- Only show filters and toggle when there's no specific limit -->
                <form method="get" action="" class="event-list-filter-form">
                    <select name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo esc_attr($category); ?>" <?php selected(isset($_GET['category']) ? $_GET['category'] : '', $category); ?>>
                                <?php echo esc_html($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="sort">
                        <option value="date_desc" <?php selected($sort_order, 'date_desc'); ?>>Most Recent to Oldest</option>
                        <option value="date_asc" <?php selected($sort_order, 'date_asc'); ?>>Oldest to Most Recent</option>
                    </select>
                    <input type="submit" value="Filter">
                </form>

                <div class="event-list-toggle">
                    <button class="event-list-toggle-btn active" data-view="grid">&#9783; Grid</button>
                    <button class="event-list-toggle-btn" data-view="list">&#9776; List</button>
                </div>
            <?php endif; ?>

            <div class="event-list-items" data-view="grid">
                <?php foreach ($events as $event):
                    $date = new DateTime($event['date']);
                    $time = new DateTime($event['time']);
                ?>
                    <div class="event-list-item">
                        <div class="event-list-image" style="background-image: url('<?php echo esc_url($event['image_url'] ?? ''); ?>');">
                            <div class="event-list-overlay">
                                <div class="event-list-date-badge">
                                    <span class="event-list-day"><?php echo $date->format('d'); ?></span>
                                    <span class="event-list-month"><?php echo $date->format('M'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="event-list-content">
                            <h3 class="event-list-title"><?php echo esc_html($event['title']); ?></h3>
                            <div class="event-list-meta">
                                <p class="event-list-time">
                                    <span class="emoji">&#128337;</span>
                                    <?php echo $time->format('g:i A'); ?>
                                </p>
                                <p class="event-list-location">
                                    <span class="emoji">&#128205;</span>
                                    <?php echo esc_html($event['location'] ?? 'TBA'); ?>
                                </p>
                            </div>
                            <div class="event-list-category-tag">
                                <span class="emoji">&#127991;</span>
                                <?php echo esc_html($event['category'] ?? 'Uncategorized'); ?>
                            </div>
                            <a href="<?php echo esc_url(home_url("/details/{$event['id']}")); ?>" class="event-list-button">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($atts['limit'] <= 0 && $total_pages > 1): ?>
                <div class="event-list-pagination">
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&#8249;',
                        'next_text' => '&#8250;',
                        'total' => $total_pages,
                        'current' => $atts['paged'],
                        'type' => 'list'
                    ));
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($atts['limit'] <= 0): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButtons = document.querySelectorAll('.event-list-toggle-btn');
                const eventListItems = document.querySelector('.event-list-items');

                toggleButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const view = this.getAttribute('data-view');
                        eventListItems.setAttribute('data-view', view);
                        toggleButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                    });
                });
            });
        </script>
    <?php endif; ?>

<?php
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
