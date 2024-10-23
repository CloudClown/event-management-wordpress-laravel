<?php
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$event = $event_id ? em_get_event($event_id) : null;

if (isset($_POST['submit'])) {
    $event_data = array(
        'title' => sanitize_text_field($_POST['title']),
        'description' => sanitize_textarea_field($_POST['description']),
        'date' => sanitize_text_field($_POST['date']),
        'time' => sanitize_text_field($_POST['time']),
        'location' => sanitize_text_field($_POST['location']),
        'category' => sanitize_text_field($_POST['category'])
    );

    $result = em_update_event($event_id, $event_data);

    if ($result) {
        echo '<div class="notice notice-success is-dismissible"><p>Event updated successfully!</p></div>';
        $event = em_get_event($event_id); // Refresh event data
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>Error updating event. Please try again.</p></div>';
    }
}
?>

<div class="wrap em-container">
    <h1 class="em-title">Edit Event</h1>
    <form method="post" action="" class="em-form">
        <div class="em-form-group">
            <label for="title" class="em-label">Title</label>
            <input name="title" id="title" type="text" class="em-input" required value="<?php echo esc_attr($event->title); ?>">
        </div>
        <div class="em-form-group">
            <label for="description" class="em-label">Description</label>
            <textarea name="description" id="description" rows="5" class="em-textarea" required><?php echo esc_textarea($event->description); ?></textarea>
        </div>
        <div class="em-form-row">
            <div class="em-form-group">
                <label for="date" class="em-label">Date</label>
                <input name="date" id="date" type="date" class="em-input" required value="<?php echo esc_attr($event->date); ?>">
            </div>
            <div class="em-form-group">
                <label for="time" class="em-label">Time</label>
                <input name="time" id="time" type="time" class="em-input" required value="<?php echo esc_attr(date('H:i', strtotime($event->time))); ?>">
            </div>
        </div>
        <div class="em-form-group">
            <label for="location" class="em-label">Location</label>
            <input name="location" id="location" type="text" class="em-input" required value="<?php echo esc_attr($event->location); ?>">
        </div>
        <div class="em-form-group">
            <label for="category" class="em-label">Category</label>
            <select name="category" id="category" class="em-select" required>
                <option value="">Select a category</option>
                <?php
                $categories = array('Conference', 'Seminar', 'Workshop', 'Webinar', 'Networking');
                foreach ($categories as $category) {
                    $selected = ($event->category == $category) ? 'selected' : '';
                    echo "<option value=\"$category\" $selected>$category</option>";
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
        <div class="em-form-group">
            <input type="submit" name="submit" id="submit" class="em-submit" value="Update Event">
        </div>
    </form>
</div>

<style>
.em-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.em-title {
    font-size: 2.5em;
    color: #333;
    margin-bottom: 20px;
}

.em-form {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.em-form-group {
    margin-bottom: 20px;
}

.em-form-row {
    display: flex;
    gap: 20px;
}

.em-form-row .em-form-group {
    flex: 1;
}

.em-label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.em-input,
.em-textarea,
.em-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.em-input:focus,
.em-textarea:focus,
.em-select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
}

.em-submit {
    background-color: #3498db;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.1s;
}

.em-submit:hover {
    background-color: #2980b9;
}

.em-submit:active {
    transform: translateY(1px);
}
</style>