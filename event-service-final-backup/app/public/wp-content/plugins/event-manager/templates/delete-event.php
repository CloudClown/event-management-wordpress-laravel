<?php
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleted = em_delete_event($event_id);
    if ($deleted) {
        wp_redirect(admin_url('admin.php?page=event-manager&deleted=1'));
        exit;
    } else {
        wp_die('Failed to delete event. Please try again.');
    }
} else {
    $event = em_get_event($event_id);
    if (!$event) {
        wp_die('Event not found');
    }
}
?>

<div class="wrap">
    <h1>Delete Event</h1>
    <p>Are you sure you want to delete the event "<?php echo esc_html($event->title); ?>"?</p>
    <form method="post" action="">
        <?php submit_button('Yes, Delete This Event', 'delete'); ?>
        <a href="<?php echo admin_url('admin.php?page=event-manager'); ?>" class="button">Cancel</a>
    </form>
</div>