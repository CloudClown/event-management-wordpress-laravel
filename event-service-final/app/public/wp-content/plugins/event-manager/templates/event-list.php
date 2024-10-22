<div class="wrap">
    <h1>Event List</h1>
    <a href="<?php echo admin_url('admin.php?page=event-manager-add'); ?>" class="page-title-action">Add New Event</a>

    <?php
    if (isset($_GET['added']) && $_GET['added'] == 1) {
        echo '<div class="updated"><p>Event added successfully!</p></div>';
    }

    $events = em_get_events();
    if ($events && !empty($events)): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Primary Image</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td>
                            <?php if (!empty($event->primary_image)): ?>
                                <img src="<?php echo esc_url(LARAVEL_APP_URL . $event->primary_image); ?>" alt="Primary Image" style="max-width: 100px; height: auto;">
                            <?php endif; ?>
                            <?php
                            if (!empty($event->images)):
                                foreach ($event->images as $image):
                            ?>
                                <img src="<?php echo esc_url(LARAVEL_APP_URL . $image->image_data); ?>" alt="Gallery Image" style="max-width: 50px; height: auto; margin: 2px;">
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </td>
                        <td><?php echo esc_html($event->title); ?></td>
                        <td><?php echo esc_html($event->date); ?></td>
                        <td><?php echo esc_html($event->time); ?></td>
                        <td><?php echo esc_html($event->location); ?></td>
                        <td><?php echo esc_html($event->category); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=event-manager-edit&id=' . $event->id); ?>">Edit</a> |
                            <a href="<?php echo admin_url('admin.php?page=event-manager-delete&id=' . $event->id); ?>" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No events found or error fetching events.</p>
    <?php endif; ?>
</div>