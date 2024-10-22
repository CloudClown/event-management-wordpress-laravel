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

function em_add_event($event_data)
{
    $api_url = 'http://localhost:8000/api/v1/events';

    error_log('Event data received: ' . print_r($event_data, true));
    error_log('Files data received: ' . print_r($_FILES, true));

    $curl = curl_init();

    $body = array(
        'title' => $event_data['title'],
        'description' => $event_data['description'],
        'date' => $event_data['date'],
        'time' => $event_data['time'],
        'location' => $event_data['location'],
        'category' => $event_data['category'],
    );

    // Handle primary image
    if (!empty($_FILES['primary_image']['tmp_name'])) {
        $body['primary_image'] = new CURLFile(
            $_FILES['primary_image']['tmp_name'],
            $_FILES['primary_image']['type'],
            $_FILES['primary_image']['name']
        );
    }

    // Handle gallery images
    if (!empty($_FILES['gallery_images']['tmp_name'])) {
        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            if (!empty($tmp_name)) {
                $body['gallery_images[]'] = new CURLFile(
                    $tmp_name,
                    $_FILES['gallery_images']['type'][$key],
                    $_FILES['gallery_images']['name'][$key]
                );
            }
        }
    }

    error_log('Prepared body for API request: ' . print_r($body, true));

    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    error_log('API Response Status: ' . $status_code);
    error_log('API Response Body: ' . $response);

    curl_close($curl);

    if ($status_code !== 201) {
        error_log('API Error: Unexpected status code ' . $status_code);
        return false;
    }

    return json_decode($response);
}

function em_update_event($event_id, $event_data)
{
    $api_url = 'http://localhost:8000/api/v1/events/' . $event_id;

    $body = array(
        'title' => $event_data['title'],
        'description' => $event_data['description'],
        'date' => $event_data['date'],
        'time' => $event_data['time'],
        'location' => $event_data['location'],
        'category' => $event_data['category'],
    );

    // Handle primary image
    if (!empty($_FILES['primary_image']['tmp_name'])) {
        $body['primary_image'] = new CURLFile($_FILES['primary_image']['tmp_name'], $_FILES['primary_image']['type'], $_FILES['primary_image']['name']);
    }

    // Handle gallery images
    if (!empty($_FILES['gallery_images']['tmp_name'])) {
        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            $body['gallery_images[]'] = new CURLFile($tmp_name, $_FILES['gallery_images']['type'][$key], $_FILES['gallery_images']['name'][$key]);
        }
    }

    $args = array(
        'body' => $body,
        'headers' => array('Content-Type' => 'multipart/form-data'),
        'timeout' => 60,
        'method' => 'POST',
    );

    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        error_log('Error updating event: ' . $response->get_error_message());
        return false;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($status_code !== 200) {
        error_log('Error updating event. Status code: ' . $status_code . ', Body: ' . $response_body);
        return false;
    }

    return json_decode($response_body);
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
