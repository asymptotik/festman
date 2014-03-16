
// PageLoad function
// This function is called when:
// 1. after calling $.historyInit();
// 2. after calling $.historyLoad();
// 3. after pushing "Go Back" button of a browser
function cmkyf_pageload(event) {
    
    var hash = event.value.lpath_trim();
    // alert("pageload: " + hash);
    // hash doesn't contain the first # character.
    if(hash) {
        // restore ajax loaded state
        if(jQuery.browser.msie) {
            // jquery's $.load() function does't work when hash include special characters like aao.
            hash = encodeURIComponent(hash);
        }
			
        var items = hash.split('/');
			
        if(items.length == 2)
        {
            var item_type = items[0];
            var item_id = items[1];
				
            var url = cmkyf_theme_url + "/fragments/" +item_type + ".php?id=" + item_id;
            cmkyf_load_lightbox(url);
        }
    } else {
        // start page
        jQuery.fancybox.close();
    }
}
	
function cmkyf_load_lightbox(url)
{
    var isOpen = jQuery.fancybox.isOpen;
    if(isOpen)
    {
        jQuery.fancybox.showLoading();

	jQuery.ajax({
		cache	        : true,
		url		: url,
		success: function(data) {
                    jQuery.fancybox(data);
		}
	});
    }
    else
    {
        jQuery.fancybox(
        {
            href : url, 
            type : 'ajax', 
            afterClose : function() 
            { 
                //jQuery.address.value(''); 
                location.hash = '!';
            }
        });
    }
}

function cmkyf_query_args(hash)
{
    var args = hash.split('&');

    argsParsed = {};

    for (i=0; i < args.length; i++)
    {
        arg = unescape(args[i]);

        if (arg.indexOf('=') == -1)
        {
            argsParsed[arg.trim()] = true;
        }
        else
        {
            kvp = arg.split('=');
            argsParsed[kvp[0].trim()] = kvp[1].trim();
        }
    }
    
    return argsParsed;
}

// initilize childItems with a class and toggle that class from defaul tot active when clicked while toggling
// the current active item to default.
(function($) {

    String.prototype.path_trim=function()
    {
        return this.replace(/^\/\/*/, '').replace(/\/\/*$/, '');
    };
    String.prototype.lpath_trim=function()
    {
        return this.replace(/^\/+/,'');
    }
    String.prototype.rpath_trim=function()
    {
        return this.replace(/\/+$/,'');
    }

    $(document).ready(function() {
        $.address.change(cmkyf_pageload).crawlable(true);  
        //$('.cf-nugget-hero').rolloverItem();
    });
    
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

    $.fn.rolloverItem = function(settings) {
        settings = $.extend({
            childType: ".cf-nugget-rollover"   // child to select
        }, settings || {});

        var top = this;
            
        $(this).find(settings.childType).each(function() {
            $(top).mouseover(function() {
                $(top).find(settings.childType).animate({ opacity: 0.75 }, 250);
              
            });
            
            $(top).mouseout(function() {
                $(top).find(settings.childType).animate({ opacity: 0.0 }, 250);
              
            });
        });

        return $;
    };
    
})(jQuery);
	


