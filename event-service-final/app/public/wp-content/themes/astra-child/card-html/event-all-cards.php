<div class="event-cards-container">
    <?php foreach ($paginated_events as $event) : ?>
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

<form method="post" class="pagination-controls">
    <?php wp_nonce_field('pagination_nonce', 'pagination_nonce_field'); ?>
    <?php if ($paged > 1): ?>
        <button type="submit" name="paged" value="1" class="pagination-btn">First</button>
        <button type="submit" name="paged" value="<?php echo ($paged - 1); ?>" class="pagination-btn">Previous</button>
    <?php endif; ?>

    <?php
    $start_page = max(1, $paged - 2);
    $end_page = min($total_pages, $paged + 2);

    for ($i = $start_page; $i <= $end_page; $i++): ?>
        <?php if ($i == $paged): ?>
            <span class="pagination-btn current-page"><?php echo $i; ?></span>
        <?php else: ?>
            <button type="submit" name="paged" value="<?php echo $i; ?>" class="pagination-btn"><?php echo $i; ?></button>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($paged < $total_pages): ?>
        <button type="submit" name="paged" value="<?php echo ($paged + 1); ?>" class="pagination-btn">Next</button>
        <button type="submit" name="paged" value="<?php echo $total_pages; ?>" class="pagination-btn">Last</button>
    <?php endif; ?>
</form>