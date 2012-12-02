(function($) {
    
    $(document).ready(function() {
        $('.spotlight-wrapper').spotlight({ 
            itemClass: 'spotlight', 
            navClass: 'spotlight-nav', 
            selectedClass: 'selected',
            animation: "fade", 
            rotateInterval: 5000,
            animationSpeed: 3000,
            navStopsRotation: false,
            preSwitch: function (current, next) {},
            postSwitch: function (current, next) {}
        });
    });
                
})(jQuery);