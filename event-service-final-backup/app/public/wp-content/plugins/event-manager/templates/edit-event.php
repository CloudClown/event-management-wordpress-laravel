<div class="wrap em-container">
    <h1 class="em-title"><?php echo isset($_GET['id']) ? 'Edit Event' : 'Add New Event'; ?></h1>
    <form method="post" action="" class="em-form">
        <div class="em-form-group">
            <label for="title" class="em-label">Title</label>
            <input name="title" id="title" type="text" class="em-input" required value="<?php echo isset($event) ? esc_attr($event->title) : ''; ?>">
        </div>
        <div class="em-form-group">
            <label for="description" class="em-label">Description</label>
            <textarea name="description" id="description" rows="5" class="em-textarea" required><?php echo isset($event) ? esc_textarea($event->description) : ''; ?></textarea>
        </div>
        <div class="em-form-row">
            <div class="em-form-group">
                <label for="date" class="em-label">Date</label>
                <input name="date" id="date" type="date" class="em-input" required value="<?php echo isset($event) ? esc_attr($event->date) : ''; ?>">
            </div>
            <div class="em-form-group">
                <label for="time" class="em-label">Time</label>
                <input name="time" id="time" type="time" class="em-input" required value="<?php echo isset($event) ? esc_attr($event->time) : ''; ?>">
            </div>
        </div>
        <div class="em-form-group">
            <label for="location" class="em-label">Location</label>
            <input name="location" id="location" type="text" class="em-input" required value="<?php echo isset($event) ? esc_attr($event->location) : ''; ?>">
        </div>
        <div class="em-form-group">
            <label for="category" class="em-label">Category</label>
            <select name="category" id="category" class="em-select" required>
                <option value="">Select a category</option>
                <option value="Conference" <?php echo (isset($event) && $event->category == 'Conference') ? 'selected' : ''; ?>>Conference</option>
                <option value="Seminar" <?php echo (isset($event) && $event->category == 'Seminar') ? 'selected' : ''; ?>>Seminar</option>
                <option value="Workshop" <?php echo (isset($event) && $event->category == 'Workshop') ? 'selected' : ''; ?>>Workshop</option>
                <option value="Webinar" <?php echo (isset($event) && $event->category == 'Webinar') ? 'selected' : ''; ?>>Webinar</option>
                <option value="Networking" <?php echo (isset($event) && $event->category == 'Networking') ? 'selected' : ''; ?>>Networking</option>
            </select>
        </div>
        <?php if (isset($event)) : ?>
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
        <?php endif; ?>
        <div class="em-form-group">
            <input type="submit" name="submit" id="submit" class="em-submit" value="<?php echo isset($event) ? 'Update Event' : 'Add Event'; ?>">
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
    transition: border-color 0.3s;
}

.em-input:focus,
.em-textarea:focus,
.em-select:focus {
    outline: none;
    border-color: #3498db;
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
    transition: background-color 0.3s;
}

.em-submit:hover {
    background-color: #2980b9;
}
</style>