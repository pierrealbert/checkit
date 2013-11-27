$(document).ready(function(){
    if ($('ul.errors').length > 0) {
        $('.block-grid.grid-inset').each(function() {
            var hasErrors = $(this).find('ul.errors');
            if (hasErrors.length > 0) {
                $(this).css('background', "#f2c5cc url('/assets/images/btn-error.png') 98% 12px no-repeat");
            } else {
                $(this).css('background', "#c2eef1 url('/assets/images/icon-tick.png') 98% 12px no-repeat");
            }
        });
    }
});