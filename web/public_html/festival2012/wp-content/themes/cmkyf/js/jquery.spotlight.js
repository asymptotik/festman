//This jQuery Plugin Requires the JQuery UI effects core and effects

(function ($) {
    //Attach this new method to jQuery
    $.fn.extend({

        //This is where you write your plugin's name
        spotlight: function (settings) {

            //Default Options
            var defaultopts = {
                itemClass: 'spotlight',
                navClass: 'spotlight-nav',
                selectedClass: 'selected',
                rotateInterval: 5000,
                animationSpeed: 3000,
                animation: "fade",
                preSwitch: null,
                postSwitch: null,
                navStopsRotation: false
            };

            //Merge parameter settings with the default operators
            var opts = $.extend(defaultopts, settings);

            var _this = $(this);
            var nuggets = $(_this).find('.' + opts.itemClass);
            var nav = $(_this).find('.' + opts.navClass + ' div');
            var count = (nuggets.length) - 1;
            var timeout = -1;

            nuggets.filter(':first').addClass(opts.selectedClass);
            nav.filter(':first').addClass(opts.selectedClass);
            
            /* Spotlight rotation */
            function contentRotate(nextNugget, repeat) {

                var currentNugget = nuggets.filter('.' + opts.selectedClass);

                if (opts.preSwitch != null) 
                    opts.preSwitch(currentNugget, nextNugget);

                //Change selected class for navigation
                nav.filter('.' + opts.selectedClass).removeClass(opts.selectedClass);
                nav.filter('.' + nextNugget.attr('id')).addClass(opts.selectedClass);
                    
                currentNugget.hide(opts.animation, {}, opts.animationSpeed);

                nextNugget.show(opts.animation, {}, opts.animationSpeed, function () 
                {
                    //Change selected class for the spotlight nuggets
                    nuggets.filter('.' + opts.selectedClass).removeClass(opts.selectedClass);
                    nextNugget.addClass(opts.selectedClass);
                    
                    if(repeat)
                    {
                        var newNext = $(nextNugget.next());
                        
                        if (newNext.length == 0)
                            newNext = nuggets.filter(':first');
                    
                        timeout = setTimeout(function () 
                        {
                            contentRotate(newNext, true);
                        }, opts.rotateInterval);
                    }
                    
                    if (opts.postSwitch != null)
                        opts.postSwitch(currentNugget, nextNugget);
                });
            }

            if (count >= 1) {
                //The First rotation shows the second element
                timeout = setTimeout(function () 
                {
                    contentRotate(nuggets.filter('.' + opts.selectedClass).next(), true);
                }, opts.rotateInterval);
            }

            nav.each(function () {
                $(this).click(function () {

                    if ($(this).hasClass(opts.selectedClass))
                        return;

                    if (timeout !== -1)
                        window.clearTimeout(timeout);

                    contentRotate(nuggets.filter($(this).attr('rel')), opts.navStopsRotation == false);
                });
            });
        }
    });       
})(jQuery);