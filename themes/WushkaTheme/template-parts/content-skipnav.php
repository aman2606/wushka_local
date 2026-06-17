<a id="skippy" class="sr-only sr-only-focusable text-center" href="#main-content">
    <div class="container">
        <span class="skiplink-text">Skip to main content <i class="fa fa-angle-double-right"></i></span>
    </div>
</a>
<script>
$('#skippy').click(function(event) {
    // On-page links
    if (
        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
        location.hostname == this.hostname
    ) {
        // Figure out element to scroll to
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        // Does a scroll target exist?
        if (target.length) {
            // Only prevent default if animation is actually gonna happen
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 30
            }, 1000, function() {
                // Callback after animation
                // Must change focus!
                var $target = $(target);
                $target.focus();
                if ($target.is(":focus")) { // Checking if the target was focused
                    return false;
                } else {
                    $target.attr('tabindex', '-1').addClass(
                        'main-content-focus'); // Adding tabindex for elements not focusable
                    $target.focus(); // Set focus again
                };
            });
        }
    }
});

$(document).on('focusout', '.main-content-focus', function() {
    $('.main-content-focus').removeAttr('tabindex').removeClass('main-content-focus');
});
</script>