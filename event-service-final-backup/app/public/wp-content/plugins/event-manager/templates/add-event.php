<div class="wrap">
    <h1>Add New Event</h1>
    
    <?php
    // Test API connection
    if (!test_api_connection()) {
        echo '<div class="error"><p>Unable to connect to the API. Please check your connection and try again.</p></div>';
    }
    ?>

    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th><label for="title">Title</label></th>
                <td><input type="text" name="title" id="title" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="description">Description</label></th>
                <td><textarea name="description" id="description" rows="5" cols="30" required></textarea></td>
            </tr>
            <tr>
                <th><label for="date">Date</label></th>
                <td><input type="date" name="date" id="date" required></td>
            </tr>
            <tr>
                <th><label for="time">Time</label></th>
                <td><input type="time" name="time" id="time" required></td>
            </tr>
            <tr>
                <th><label for="location">Location</label></th>
                <td><input type="text" name="location" id="location" class="regular-text" required></td>
            </tr>
            <tr>
                <th><label for="category">Category</label></th>
                <td><input type="text" name="category" id="category" class="regular-text" required></td>
            </tr>
        </table>
        <?php submit_button('Add Event'); ?>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log('Form submitted. POST data: ' . print_r($_POST, true));
    
    $result = em_add_event($_POST);
    if ($result) {
        echo '<div class="updated"><p>Event added successfully!</p></div>';
    } else {
        echo '<div class="error"><p>Failed to add event. Please check the error log for more details.</p></div>';
    }
}
?>