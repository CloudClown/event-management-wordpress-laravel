<div class="wrap">
    <h1>Event List</h1>
    <a href="<?php echo admin_url('admin.php?page=event-manager-add'); ?>" class="page-title-action">Add New Event</a>
    
    <?php
    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
        echo '<div class="updated"><p>Event deleted successfully!</p></div>';
    }
    
    $events = em_get_events();
    if ($events): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
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
        <p>No events found.</p>
    <?php endif; ?>
</div>