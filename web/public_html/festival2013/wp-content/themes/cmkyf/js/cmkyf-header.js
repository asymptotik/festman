(function($) {
    
    $(document).ready(function() {
        $('#mce-EMAIL').watermark('email list');
        $('#mc-embedded-subscribe').click(function () {
            $('#mc-embedded-subscribe-form').submit();
        });
    });
    
})(jQuery);