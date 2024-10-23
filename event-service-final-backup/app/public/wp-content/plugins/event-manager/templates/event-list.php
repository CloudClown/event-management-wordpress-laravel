<div class="wrap em-container">
    <h1 class="em-title">Event Manager</h1>
    <a href="<?php echo admin_url('admin.php?page=event-manager-add'); ?>" class="em-add-button">Add New Event</a>
    <div class="em-event-grid">
        <?php
        $events = em_get_events();
        if ($events) {
            foreach ($events as $event) {
        ?>
                <div class="em-event-card">
                    <h2 class="em-event-title"><?php echo esc_html($event->title); ?></h2>
                    <p class="em-event-date"><?php echo esc_html(date('F j, Y', strtotime($event->date))); ?></p>
                    <p class="em-event-time"><?php echo esc_html(date('g:i A', strtotime($event->time))); ?></p>
                    <p class="em-event-location"><?php echo esc_html($event->location); ?></p>
                    <span class="em-event-category"><?php echo esc_html($event->category); ?></span>
                    <div class="em-event-actions">
                        <a href="<?php echo admin_url('admin.php?page=event-manager-edit&id=' . $event->id); ?>" class="em-button em-button-edit">Edit</a>
                        <a href="<?php echo admin_url('admin.php?page=event-manager-delete&id=' . $event->id); ?>" class="em-button em-button-delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        <a href="#" class="em-button em-button-view view-details" data-id="<?php echo $event->id; ?>">View Details</a>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="em-no-events">No events found.</p>';
        }
        ?>
    </div>
</div>

<div id="event-details-modal" class="em-modal">
    <div class="em-modal-content">
        <span class="em-modal-close">&times;</span>
        <h2 class="em-modal-title">Event Details</h2>
        <div id="event-details-content"></div>
    </div>
</div>

<style>
    .em-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .em-title {
        font-size: 2.5em;
        color: #333;
        margin-bottom: 20px;
    }

    .em-add-button {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .em-add-button:hover {
        background-color: #45a049;
    }

    .em-event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .em-event-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .em-event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }

    .em-event-title {
        font-size: 1.5em;
        color: #333;
        margin-bottom: 10px;
    }

    .em-event-date,
    .em-event-location {
        color: #666;
        margin-bottom: 5px;
    }

    .em-event-category {
        display: inline-block;
        background-color: #3498db;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9em;
        margin-bottom: 10px;
    }

    .em-event-actions {
        display: flex;
        justify-content: flex-start;
        margin-top: 15px;
        gap: 10px;
    }

    .em-button {
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .em-button-edit {
        background-color: #f39c12;
        color: white;
    }

    .em-button-edit:hover {
        background-color: #e67e22;
    }

    .em-button-delete {
        background-color: #e74c3c;
        color: white;
    }

    .em-button-delete:hover {
        background-color: #c0392b;
    }

    .em-button-view {
        background-color: #3498db;
        color: white;
    }

    .em-button-view:hover {
        background-color: #2980b9;
    }

    .em-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .em-modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 60%;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .em-modal-close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .em-modal-close:hover,
    .em-modal-close:focus {
        color: #333;
        text-decoration: none;
    }

    .em-modal-title {
        font-size: 1.8em;
        color: #333;
        margin-bottom: 20px;
    }

    .em-no-events {
        grid-column: 1 / -1;
        text-align: center;
        font-size: 1.2em;
        color: #666;
    }

    .em-event-details {
        padding: 20px;
    }

    .em-event-details .em-event-title {
        font-size: 24px;
        color: #333;
        margin-bottom: 15px;
    }

    .em-event-details .em-event-description {
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .em-event-details .em-event-info {
        margin-bottom: 10px;
    }

    .em-event-details .em-event-category {
        display: inline-block;
        background-color: #3498db;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.9em;
    }
</style>

<script>
    jQuery(document).ready(function($) {
        $('.view-details').on('click', function(e) {
            e.preventDefault();
            var eventId = $(this).data('id');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_event_details',
                    event_id: eventId
                },
                success: function(response) {
                    $('#event-details-content').html(response);
                    $('#event-details-modal').fadeIn(300);
                }
            });
        });

        $('.em-modal-close').on('click', function() {
            $('#event-details-modal').fadeOut(300);
        });

        $(window).on('click', function(event) {
            if (event.target == $('#event-details-modal')[0]) {
                $('#event-details-modal').fadeOut(300);
            }
        });
    });
</script>