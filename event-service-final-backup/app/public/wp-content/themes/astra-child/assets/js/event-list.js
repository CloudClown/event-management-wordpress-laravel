jQuery(document).ready(function($) {
    $('.event-list-toggle-btn').on('click', function() {
        var view = $(this).data('view');
        $('.event-list-items').attr('data-view', view);
        $('.event-list-toggle-btn').removeClass('active');
        $(this).addClass('active');
    });

    // Set initial active state
    $('.event-list-toggle-btn[data-view="grid"]').addClass('active');
    $('.event-list-items').attr('data-view', 'grid');
});