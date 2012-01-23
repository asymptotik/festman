
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
				
				var url = cmkyf_theme_url + "/fragments/" +item_type + ".php?" + item_type + "_id=" + item_id;
				
				var menu_index = false;
				
				switch(item_type)
				{
					case "act":
						menu_index = 0;
						break;
					case "workshop":
						menu_index = 1;
						break;
					case "installation":
						menu_index = 2;
						break;
					case "film":
						menu_index = 3;
						break;
					case "event":
						menu_index = 4;
						break;
					case "venue":
						menu_index = 5;
						break;
					case "schedule":
						menu_index = 6;
						break;
					case "program":
						menu_index = 6;
						break;
				}
				
				jQuery("#processing").fadeIn(100);
				jQuery("#accordion").accordion('activate', menu_index);
				//$("#content").load(url);
				jQuery("#content").load(url, function() {  jQuery("#processing").fadeOut(10); } );
				return false;
			}
		} else {
			// start page
			jQuery("#content").empty();
		}
	}
	
	// initilize childItems with a class and toggle that class from defaul tot active when clicked while toggling
	// the current active item to default.
    (function($) {

        $.fn.selectItem = function(settings) {
            settings = $.extend({
                childType: "a",    // child to select
                classActive: "ui-state-active",    // class for active item
                classDefault: "ui-state-default"    // class for default item
            }, settings || {});

            var top = this;
            
            $(this).find(settings.childType).each(function() {
                $(this).addClass(settings.classDefault);
                $(this).click(function() {
                	$(top).find('.' + settings.classActive).removeClass(settings.classActive).addClass(settings.classDefault); 
                	$(this).removeClass(settings.classDefault).addClass(settings.classActive); 
                });
            });

    	    return $;
        };

    })(jQuery);
	
	jQuery(document).ready(function($){
		
		$('.scroll-pane').jScrollPane({scrollbarOnLeft:true, scrollbarMargin:10});
	    $("#accordion").accordion({ header: "h3", autoHeight: true, animated: 'slide', collapsible: false });
	    $('.sub-menu').selectItem();
	    
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

