<?php
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id) {
    $result = em_delete_event($event_id);

    if ($result) {
        echo '<div class="updated"><p>Event deleted successfully!</p></div>';
    } else {
        echo '<div class="error"><p>Error deleting event. Please try again.</p></div>';
    }
} else {
    echo '<div class="error"><p>Invalid event ID.</p></div>';
}

// Redirect back to the event list after a short delay
echo '<script>setTimeout(function() { window.location.href = "' . admin_url('admin.php?page=event-manager') . '"; }, 2000);</script>';
?>