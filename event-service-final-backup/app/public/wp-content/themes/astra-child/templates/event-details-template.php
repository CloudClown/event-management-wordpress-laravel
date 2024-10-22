<div class="event-details-container">
    <header class="event-header" style="background-image: url('<?php echo esc_url($event['image'] ?? ''); ?>');">
        <div class="event-header-content">
            <h1 class="event-title"><?php echo esc_html($event['title']); ?></h1>
            <div class="event-meta">
                <p class="event-date-time">
                    <span class="emoji">&#128197;</span> <?php echo esc_html(date('F j, Y', strtotime($event['date']))); ?>
                    <span class="emoji">&#128336;</span> <?php echo esc_html(date('g:i A', strtotime($event['time']))); ?>
                </p>
                <p class="event-location">
                    <span class="emoji">&#128205;</span> <?php echo esc_html($event['location']); ?>
                </p>
            </div>
        </div>
    </header>

    <main class="event-body">
        <section class="event-description">
            <h2>About This Event</h2>
            <?php echo wpautop($event['description']); ?>
        </section>

        <section class="event-details">
            <h2>Event Details</h2>
            <ul>
                <li><strong>Category:</strong> <?php echo esc_html($event['category']); ?></li>
                <li><strong>Organizer:</strong> <?php echo esc_html($event['organizer'] ?? 'N/A'); ?></li>
                <!-- Add more details as needed -->
            </ul>
        </section>
    </main>
</div>