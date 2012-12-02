(function($) {
    
    $(document).ready(function() {
        $('#EmailAddress').watermark('email list');
        $('#signup').click(function () {
            $('#ccsfg').submit();
        });
    });
    
})(jQuery);