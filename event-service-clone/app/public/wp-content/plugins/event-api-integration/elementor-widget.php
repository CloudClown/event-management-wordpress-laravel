<?php
class Elementor_Event_Cards_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'event_cards';
    }

    public function get_title() {
        return __('Event Cards', 'event-api-integration');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'event-api-integration'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'event-api-integration'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'per_page',
            [
                'label' => __('Events per page', 'event-api-integration'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 6,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $shortcode = '[event_cards';
        if (!empty($settings['category'])) {
            $shortcode .= ' category="' . esc_attr($settings['category']) . '"';
        }
        $shortcode .= ' per_page="' . esc_attr($settings['per_page']) . '"';
        $shortcode .= ']';

        echo do_shortcode($shortcode);
    }
}