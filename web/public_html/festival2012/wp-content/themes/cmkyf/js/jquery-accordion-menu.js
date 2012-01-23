(function($) {

	$.fn.accordionMenu = function(settings) {
		settings = $.extend( {
			collapsible : true,
			childType: "a",    // child to select
            classActive: "ui-state-active",    // class for active item
            classDefault: "ui-state-default"    // class for default item
		// can all items be closed
				}, settings || {});

		var top = this;

		$(this).children('li').children('ul').hide();

		if (settings.collapsible == false)
			$(this).children('li:first').children('ul:first').show();

		$(this).children('li').each(function() {
			
			$(this).addClass(settings.classDefault);
			
			// sub menus
			$(this).children('ul').each(function() {
				var sub_top = this;
				$(this).children('li').addClass(settings.classDefault);
				$(this).children('li').children('a').click(function() {
					
					$(top).find('.' + settings.classActive).removeClass(settings.classActive).addClass(settings.classDefault); 
					$(sub_top).parent('li').removeClass(settings.classDefault).addClass(settings.classActive); 
					
					$(sub_top).find('.' + settings.classActive).removeClass(settings.classActive).addClass(settings.classDefault); 
	            	$(this).parent('li').removeClass(settings.classDefault).addClass(settings.classActive); 
				});
			});
			
			$(this).children('a:first').click(function() {
				
				$(top).find('.' + settings.classActive).removeClass(settings.classActive).addClass(settings.classDefault); 
            	$(this).parent('li').removeClass(settings.classDefault).addClass(settings.classActive); 
            	
				var checkElement = $(this).next();
				if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
					if (settings.collapsible == true)
						checkElement.slideUp('normal');
					return false;
				}
				if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
					$('#menu ul:visible').slideUp('normal');
					checkElement.slideDown('normal');
					return false;
				}
			});
		});

		return $;
	};

})(jQuery);
