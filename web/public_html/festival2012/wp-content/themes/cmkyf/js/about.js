// PageLoad function
// This function is called when:
// 1. after calling $.historyInit();
// 2. after calling $.historyLoad();
// 3. after pushing "Go Back" button of a browser
function pageload(hash) {
	// alert("pageload: " + hash);
	// hash doesn't contain the first # character.
	if(hash) {
		// restore ajax loaded state
		if(jQuery.browser.msie) {
			// jquery's $.load() function does't work when hash include special characters like aao.
			hash = encodeURIComponent(hash);
		}
		
		var items = hash.split('-');
		
		if(items.length == 2)
		{
			var item_type = items[0];
			var item_id = items[1];
			
			var url = cmkyf_theme_url + "/fragments/about.php?page_id=" + item_id;
			
			var item_map = getCmkyItemMap();
			var item = item_map["item_" + item_id];
			
			jQuery("#processing").fadeIn(100);
			
			if(item && item_type != 'about')
			{
				jQuery("#menu").accordionMenuActivate({ itemIndex: item.item, subItemIndex: item.sub_item });
			}
			//jQuery("#content").load(url);
			jQuery("#content").load(url, function() {  jQuery("#processing").fadeOut(10); } );
			return false;
		}
	} else {
		// start page
		jQuery("#content").empty();
	}
}

// Control for our nested menu system.
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
				// add default class
				$(this).children('li').addClass(settings.classDefault);
				// add event handler.
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
				var elementName = checkElement.html();
				if ((checkElement.is('ul')) && (!checkElement.is(':hidden'))) {
					if (settings.collapsible == true)
						checkElement.slideUp('normal');
					return false;
				}
				if ((checkElement.is('ul')) && (checkElement.is(':hidden'))) {
					$('#menu ul:visible').slideUp('normal');
					checkElement.slideDown('normal');
					return false;
				}
			});
		});

		return $;
	};

})(jQuery);

//Control for our nested menu system.
(function($) {

	$.fn.accordionMenuActivate = function(settings) {
		settings = $.extend( {
			collapsible : true,
			childType: "a",    // child to select
            classActive: "ui-state-active",    // class for active item
            classDefault: "ui-state-default",    // class for default item
            itemIndex: 0,
            subItemIndex: 0
		// can all items be closed
				}, settings || {});

		var item = $(this).children('li:eq(' + settings.itemIndex  + ')');
		if($(item).hasClass(settings.classActive) == false)
		{
			$(this).children('li').children('ul').hide();
			$(item).children('ul').show();
			$(item).removeClass(settings.removeActive).addClass(settings.classActive);
		}
	
		if(settings.subItemIndex >= 0)
		{
			var subItem = $(item).children('ul:first').children('li:eq(' + settings.subItemIndex + ')');
			if($(subItem).hasClass(settings.classActive) == false)
			{
				$(subItem).removeClass(settings.removeActive).addClass(settings.classActive);
			}
		}
		else	
		{
			$(item).children('ul:first').find('.' + settings.classActive).removeClass(settings.classActive).addClass(settings.classDefault);
		}
		return $;
	};

})(jQuery);

$(document).ready(function($){
	$("#menu").accordionMenu();
	
	// Initialize history plugin.
	// The callback is called at once by present location.hash. 
	$.historyInit(pageload, "jquery_history.html");
	
	// set onlick event for buttons
	$("a[rel='history']").click(function(){
		// 
		
		var hash = this.href;
		hash = hash.replace(/^.*#/, '');
		// moves to a new page. 
		// pageload is called at once. 
		// hash don't contain "#", "?"
		$.historyLoad(hash);
		return false;
	});
	
	var current_hash = location.hash.replace(/\?.*$/, '');
	if(!current_hash)
	{
		// load the first url
		$("a[rel='history']:eq(0)").each(function() {

			var hash = this.href;
			hash = hash.replace(/^.*#/, '');
			// moves to a new page. 
			// pageload is called at once. 
			// hash don't contain "#", "?"
			$.historyLoad(hash);
		});
	}
});

