		jQuery(document).ready(function(){
				
				_dialog = jQuery('#ccsfg');
				_title = _dialog.find('h4').html();	
				_dialog.attr('title', _title);
				_action = jQuery(_dialog).attr('action');
				_submitBtn = jQuery('#ccsfg').find('#signup');
				_submitBtnText = jQuery(_submitBtn).val();
				_submitBtn.remove();
				
				var dialog_buttons = {};
				dialog_buttons[_submitBtnText] = function(){ 
					
					query = jQuery(_dialog).serializeArray()
					json = {};
					for (i in query) { json[query[i].name] = query[i].value	}				
					json['RequestType'] = 'ajax';								
					$.get(_action, json,
					   function(data){
					   		tmp = jQuery('<div id="tmp"></div>');
					   		jQuery(tmp).html(data);
					   		code = jQuery(tmp).find('#code').attr('title');
					   		if(code==201){
					   			if(json['SuccessURL']){ window.location = json['SuccessURL']; }
					     	}
					     	else {
					     		if(json['FailureURL']){ window.location = json['FailureURL']; }     		
					     	}				     	
					    jQuery('#ccsfg').html(data);
					    _btnPane = jQuery('.ui-dialog-buttonpane');				
					    _btnPane.remove();				     
					   });						
				}			
				jQuery('#ccsfg').dialog({autoOpen:false,modal:true, resizable:false, closeText:'close', draggable:false, width:500, buttons: dialog_buttons
				});
				
				jQuery('#ccsfg_btn').click(function(){
					jQuery('#ccsfg').dialog( 'open' );
				})
		})