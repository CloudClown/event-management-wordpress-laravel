<?php
/*
Plugin Name: Event API Integration
Description: Integrates with the Laravel Event API and displays events in cards with pagination
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class EventAPIIntegration {
    private $api_base_url = 'http://localhost:8000/api/v1/';

    public function __construct() {
        add_action('init', array($this, 'init'));
        add_shortcode('event_cards', array($this, 'event_cards_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    public function init() {
        // Initialize plugin
    }

    public function enqueue_styles() {
        wp_enqueue_style('event-cards-style', get_template_directory_uri() . '/path/to/your/css/file.css');
    }

    private function get_events($category = null, $page = 1, $per_page = 6) {
        $url = $this->api_base_url . '/events';
        
        if ($category) {
            $url = $this->api_base_url . '/events/category/' . urlencode($category);
        }
    
        $args = array(
            'headers' => array(
                'Accept' => 'application/json',
            ),
        );
    
        $response = wp_remote_get($url, $args);
    
        if (is_wp_error($response)) {
            error_log("API Error: " . $response->get_error_message());
            return ['error' => 'Unable to connect to the event server. Please try again later.'];
        }
    
        $body = wp_remote_retrieve_body($response);
        $events = json_decode($body, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Decode Error: " . json_last_error_msg());
            return ['error' => 'Error processing event data. Please try again later.'];
        }
    
        // Manual pagination since the API doesn't support it
        $offset = ($page - 1) * $per_page;
        $paginated_events = array_slice($events, $offset, $per_page);
    
        return [
            'data' => $paginated_events,
            'current_page' => $page,
            'last_page' => ceil(count($events) / $per_page),
            'total' => count($events),
        ];
    }

    public function event_cards_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'per_page' => 6
        ), $atts);
    
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
        $events_data = $this->get_events($atts['category'], $paged, $atts['per_page']);
    
        if (isset($events_data['error'])) {
            return '<p class="error-message">' . esc_html($events_data['error']) . '</p>';
        }
    
        $paginated_events = $events_data['data'];
        $total_pages = $events_data['last_page'];
    
        ob_start();
        ?>
        <div class="event-cards-container">
            <?php foreach ($paginated_events as $event) : ?>
                <div class="event-card">
                    <div class="event-card-image" style="background-image: url('<?php echo esc_url($event['image'] ?? 'default-image-url.jpg'); ?>')"></div>
                    <div class="event-card-content">
                        <h2><?php echo esc_html($event['title']); ?></h2>
                        <p class="event-date"><?php echo esc_html($event['date']); ?></p>
                        <p class="event-time"><?php echo esc_html($event['time']); ?></p>
                        <p class="event-place"><?php echo esc_html($event['location']); ?></p>
                        <p class="event-category"><?php echo esc_html($event['category']); ?></p>
                        <button class="event-details-btn">See Details</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    
        <?php if ($total_pages > 1): ?>
        <form method="post" class="pagination-controls">
            <?php wp_nonce_field('pagination_nonce', 'pagination_nonce_field'); ?>
            <?php if ($paged > 1): ?>
                <button type="submit" name="paged" value="1" class="pagination-btn">First</button>
                <button type="submit" name="paged" value="<?php echo ($paged - 1); ?>" class="pagination-btn">Previous</button>
            <?php endif; ?>
    
            <?php
            $start_page = max(1, $paged - 2);
            $end_page = min($total_pages, $paged + 2);
    
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <?php if ($i == $paged): ?>
                    <span class="pagination-btn current-page"><?php echo $i; ?></span>
                <?php else: ?>
                    <button type="submit" name="paged" value="<?php echo $i; ?>" class="pagination-btn"><?php echo $i; ?></button>
                <?php endif; ?>
            <?php endfor; ?>
    
            <?php if ($paged < $total_pages): ?>
                <button type="submit" name="paged" value="<?php echo ($paged + 1); ?>" class="pagination-btn">Next</button>
                <button type="submit" name="paged" value="<?php echo $total_pages; ?>" class="pagination-btn">Last</button>
            <?php endif; ?>
        </form>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }
}

new EventAPIIntegration();

add_action('elementor/widgets/widgets_registered', function() {
    require_once(__DIR__ . '/elementor-widget.php');
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_Event_Cards_Widget());
});