<?php
function em_get_events()
{
    $api_url = 'http://localhost:8000/api/v1/events';
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        error_log('Error fetching events: ' . $response->get_error_message());
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    if ($status_code !== 200) {
        error_log('Error fetching events. Status code: ' . $status_code . ', Body: ' . $body);
        return false;
    }

    return json_decode($body);
}

function em_get_event($event_id)
{
    $api_url = 'http://localhost:8000/api/v1/events/' . $event_id;
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        error_log('Error fetching event: ' . $response->get_error_message());
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    if ($status_code !== 200) {
        error_log('Error fetching event. Status code: ' . $status_code . ', Body: ' . $body);
        return false;
    }

    return json_decode($body);
}
function em_add_event($event_data) {
    $api_url = 'http://localhost:8000/api/v1/events';

    // Format date and time
    $event_data['date'] = date('Y-m-d', strtotime($event_data['date']));
    $event_data['time'] = date('H:i:s', strtotime($event_data['time']));

    error_log('Attempting to add event. Data: ' . print_r($event_data, true));

    $response = wp_remote_post($api_url, array(
        'body' => json_encode($event_data),
        'headers' => array('Content-Type' => 'application/json'),
        'timeout' => 45,
    ));

    if (is_wp_error($response)) {
        error_log('WP Error: ' . $response->get_error_message());
        return false;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    error_log('API Response Code: ' . $response_code);
    error_log('API Response Body: ' . $response_body);

    if ($response_code === 201) {
        $event = json_decode($response_body);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $event;
        } else {
            error_log('JSON Decode Error: ' . json_last_error_msg());
            return false;
        }
    } else {
        error_log('API Error: ' . $response_body);
        return false;
    }
}
function em_update_event($event_id, $event_data) {
    error_log('em_update_event function called');
    error_log('Event ID: ' . $event_id);
    error_log('Event data: ' . print_r($event_data, true));

    $api_url = 'http://localhost:8000/api/v1/events/' . $event_id;

    // Format date and time
    if (isset($event_data['date'])) {
        $event_data['date'] = date('Y-m-d', strtotime($event_data['date']));
    }
    if (isset($event_data['time'])) {
        $event_data['time'] = date('H:i:s', strtotime($event_data['time']));
    }

    $response = wp_remote_request($api_url, array(
        'method'  => 'PUT',
        'headers' => array('Content-Type' => 'application/json'),
        'body'    => json_encode($event_data),
        'timeout' => 45,
    ));

    if (is_wp_error($response)) {
        error_log('WP Error: ' . $response->get_error_message());
        return false;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    error_log('API Update Response Code: ' . $response_code);
    error_log('API Update Response Body: ' . $response_body);

    return $response_code === 200;
}
function em_delete_event($event_id)
{
    $api_url = 'http://localhost:8000/api/v1/events/' . $event_id;

    $response = wp_remote_request($api_url, array(
        'method' => 'DELETE',
        'timeout' => 45,
    ));

    if (is_wp_error($response)) {
        error_log('Error deleting event: ' . $response->get_error_message());
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);

    if ($status_code !== 204) {
        error_log('Error deleting event. Status code: ' . $status_code);
        return false;
    }

    return true;
}

function test_api_connection()
{
    $api_url = 'http://localhost:8000/api/v1/events';
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        error_log('API Connection Test Error: ' . $response->get_error_message());
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    error_log('API Connection Test Status: ' . $status_code);
    error_log('API Connection Test Body: ' . $body);

    return $status_code === 200;
}
