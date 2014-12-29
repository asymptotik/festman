/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var fm_hasChanged = false;
var fm_ignoreChanges = false;

function fmOnDataChanged() 
{
    fm_hasChanged = true;
}

(function( $ ) {

    $(document).ready(function() {

        var lastClicked = false, checks, first, last, checked;

        $('select').change(fmOnDataChanged);
        $('input:text, textarea').on("propertychange change keyup paste input", fmOnDataChanged);
        $('input:checkbox, input:radio').click(fmOnDataChanged);

        $(window).bind('beforeunload', function(event) {

            if (fm_hasChanged && !fm_ignoreChanges) {
                var message = 'Changes have been made. Are you sure you want to leave the page?';
                (event || window.event).returnValue = message; //Gecko + IE
                return message;                                //Gecko + Webkit, Safari, Chrome etc.
            } else {
                event.preventDefault();
            }                            
        });
        
        // check all checkboxes
        $('tbody').children().children('.sc-check-column').find(':checkbox').click( function(e) {
                if ( 'undefined' == e.shiftKey ) { return true; }
                if ( e.shiftKey ) {
                        if ( !lastClicked ) { return true; }
                        checks = $( lastClicked ).closest( 'form' ).find( ':checkbox' );
                        first = checks.index( lastClicked );
                        last = checks.index( this );
                        checked = $(this).prop('checked');
                        if ( 0 < first && 0 < last && first != last ) {
                                checks.slice( first, last ).prop( 'checked', function(){
                                        if ( $(this).closest('tr').is(':visible') )
                                                return checked;

                                        return false;
                                });
                        }
                }
                lastClicked = this;
                return true;
        });

        $('thead, tfoot').find('.sc-check-column :checkbox').click( function(e) {
                var c = $(this).prop('checked'),
                        kbtoggle = 'undefined' == typeof toggleWithKeyboard ? false : toggleWithKeyboard,
                        toggle = e.shiftKey || kbtoggle;

                $(this).closest( '.fm-scroll-table' ).find( 'tbody' ).filter(':visible')
                .children().children('.sc-check-column').find(':checkbox')
                .prop('checked', function() {
                        if ( $(this).closest('tr').is(':hidden') )
                                return false;
                        if ( toggle )
                                return $(this).prop( 'checked' );
                        else if (c)
                                return true;
                        return false;
                });

                $(this).closest('.fm-scroll-table').find('thead,  tfoot').filter(':visible')
                .children().children('.sc-check-column').find(':checkbox')
                .prop('checked', function() {
                        if ( toggle )
                                return false;
                        else if (c)
                                return true;
                        return false;
                });
        });
    
    });
    
})(jQuery);



