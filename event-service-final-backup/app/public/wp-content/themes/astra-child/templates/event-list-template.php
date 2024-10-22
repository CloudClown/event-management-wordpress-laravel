<div class="event-list-container">
    <div class="event-list-card">
        <?php if ($atts['limit'] <= 0): ?>
            <div class="event-list-toggle">
                <button class="event-list-toggle-btn" data-view="grid">&#9783; Grid</button>
                <button class="event-list-toggle-btn" data-view="list">&#9776; List</button>
            </div>
        <?php endif; ?>

        <div class="event-list-items">
            <?php foreach ($events as $event):
                $date = new DateTime($event['date']);
                $time = new DateTime($event['time']);
            ?>
                <div class="event-list-item">
                    <div class="event-list-image">
                        <div class="event-list-overlay">
                            <div class="event-list-date-badge">
                                <span class="event-list-day"><?php echo $date->format('d'); ?></span>
                                <span class="event-list-month"><?php echo $date->format('M'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="event-list-content">
                        <h3 class="event-list-title"><?php echo esc_html($event['title']); ?></h3>
                        <div class="event-list-meta">
                            <p class="event-list-time">
                                <span class="emoji">&#128337;</span>
                                <?php echo $time->format('g:i A'); ?>
                            </p>
                            <p class="event-list-location">
                                <span class="emoji">&#128205;</span>
                                <?php echo esc_html($event['location'] ?? 'TBA'); ?>
                            </p>
                        </div>
                        <div class="event-list-category-tag">
                            <span class="emoji">&#127991;</span>
                            <?php echo esc_html($event['category'] ?? 'Uncategorized'); ?>
                        </div>
                        <a href="<?php echo esc_url(home_url("/details/{$event['id']}")); ?>" class="event-list-button">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($atts['limit'] <= 0 && $total_pages > 1): ?>
            <div class="event-list-pagination">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => '&#8249;',
                    'next_text' => '&#8250;',
                    'total' => $total_pages,
                    'current' => $atts['paged'],
                    'type' => 'list'
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>