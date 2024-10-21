<div class="event-cards-container">
    <?php foreach ($events as $event) : ?>
        <div class="event-card">
            <div class="event-card-image" style="background-image: url('<?php echo esc_url($event['image']); ?>')"></div>
            <div class="event-card-content">
                <h2><?php echo esc_html($event['title']); ?></h2>
                <p class="event-date"><?php echo esc_html($event['date']); ?></p>
                <p class="event-place"><?php echo esc_html($event['place']); ?></p>
                <button class="event-details-btn">See Details</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>