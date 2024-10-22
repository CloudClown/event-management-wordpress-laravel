<?php
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$event = em_get_event($event_id);

if (!$event) {
    wp_die('Event not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_event = em_update_event($event_id, $_POST);
    if ($updated_event) {
        echo '<div class="updated"><p>Event updated successfully!</p></div>';
        $event = $updated_event;
    } else {
        echo '<div class="error"><p>Failed to update event. Please try again.</p></div>';
    }
}
?>

<div class="wrap">
    <h1>Edit Event</h1>
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th><label for="title">Title</label></th>
                <td><input type="text" name="title" id="title" class="regular-text" value="<?php echo esc_attr($event->title); ?>" required></td>
            </tr>
            <tr>
                <th><label for="description">Description</label></th>
                <td><textarea name="description" id="description" rows="5" cols="30" required><?php echo esc_textarea($event->description); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="date">Date</label></th>
                <td><input type="date" name="date" id="date" value="<?php echo esc_attr($event->date); ?>" required></td>
            </tr>
            <tr>
                <th><label for="time">Time</label></th>
                <td><input type="time" name="time" id="time" value="<?php echo esc_attr($event->time); ?>" required></td>
            </tr>
            <tr>
                <th><label for="location">Location</label></th>
                <td><input type="text" name="location" id="location" class="regular-text" value="<?php echo esc_attr($event->location); ?>" required></td>
            </tr>
            <tr>
                <th><label for="category">Category</label></th>
                <td><input type="text" name="category" id="category" class="regular-text" value="<?php echo esc_attr($event->category); ?>" required></td>
            </tr>
        </table>
        <?php submit_button('Update Event'); ?>
    </form>
</div>