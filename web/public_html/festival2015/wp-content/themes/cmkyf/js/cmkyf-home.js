(function($) {
    
    $(document).ready(function() {
        $('.home-spotlight-wrapper').spotlight({ 
            itemClass: 'home-spotlight', 
            navClass: 'home-spotlight-nav', 
            selectedClass: 'selected',
            animation: "fade", 
            rotateInterval: 7000,
            animationSpeed: 3000,
            navStopsRotation: false,
            preSwitch: function (current, next) {},
            postSwitch: function (current, next) {}
        });
    });
                
})(jQuery);
