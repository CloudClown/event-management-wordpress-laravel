<?php
function em_create_admin_menu() {
    add_menu_page(
        'Event Manager',
        'Event Manager',
        'manage_options',
        'event-manager',
        'em_display_event_list',
        'dashicons-calendar-alt',
        20
    );

    add_submenu_page(
        'event-manager',
        'Add New Event',
        'Add New',
        'manage_options',
        'event-manager-add',
        'em_display_add_event_form'
    );

    // These are hidden menu items for edit and delete
    add_submenu_page(
        null,
        'Edit Event',
        'Edit Event',
        'manage_options',
        'event-manager-edit',
        'em_display_edit_event_form'
    );

    add_submenu_page(
        null,
        'Delete Event',
        'Delete Event',
        'manage_options',
        'event-manager-delete',
        'em_handle_delete_event'
    );
}

function em_display_event_list() {
    include(plugin_dir_path(__FILE__) . '../templates/event-list.php');
}

function em_display_add_event_form() {
    include(plugin_dir_path(__FILE__) . '../templates/add-event.php');
}

function em_display_edit_event_form() {
    include(plugin_dir_path(__FILE__) . '../templates/edit-event.php');
}

function em_handle_delete_event() {
    include(plugin_dir_path(__FILE__) . '../templates/delete-event.php');
}