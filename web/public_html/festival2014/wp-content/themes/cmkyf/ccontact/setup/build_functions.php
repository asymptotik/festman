<?php 
	
	require_once(dirname(__FILE__) . '/functions.php');
	
	function makeSelectBox($name, $array){
		$box = '<select name="'.$name.'">'."\n";
		foreach ($array as $code => $name){
			$box .= '<option value="'.$code.'">'.$name.'</option>'."\n";
		}
		$box .= '</select><br />'."\n\n";
		return $box;
	}
	
	function makeRadioButton($name, $options){
		foreach ($options as $option){
			$radio .= '<input type="radio" name="'.$name.'" value="'.$option.'">'.$option.'';
		}
		$radio .= '<br />';
		return $radio;
		
	}

	function generateField($field, $labels){
		global $all_fields, $states, $countries;
		$field_labels = getIncludedFieldLabels();		
		$label = ( (substr($field,0,11)=='CustomField') ? $labels[(substr($field,11,1)-1)] : $all_fields[$field][0]);
		$field_html = '<!-- ########## '.$field_labels[$field].' ########## -->'."\n";
		$field_html .= '<label for="'.$field.'">'.$label.'</label>'."\n"; 
		if($all_fields[$field][1]=='states'){ $field_html .= makeSelectBox('StateCode', $states); }
		else if($all_fields[$field][1]=='countries'){ $field_html .= makeSelectBox('CountryCode', $countries); }
		else if($all_fields[$field][1]=='formats')
		{
			$field_html .= makeRadioButton('EmailType', $typeOptions=array('HTML', 'Text')); 
		}
		else {
			$field_html .= '<input type="text" name="'.$field.'" value="" id="'.$field.'" /><br />'."\n\n";
		}
		return $field_html;
	}
	
	
	
	function generateFields(){
		global $ccConfigOBJ;
		$custom_field_labels = $ccConfigOBJ->custom_field_labels;
		$fields = $ccConfigOBJ->included_fields;
		
		$form_uri = str_replace("setup/code.php", "signup/index.php", $_SERVER['SCRIPT_NAME']);
		

		
		$fields_html = '<form id="ccsfg" name="ccsfg" method="post" action="'.$form_uri.'">'."\n\n";
		
		$fields_html .= '<h4>Registration Form</h4>';
		$fields_html .= '<p>To join our mailing list, please complete the information below and click \'Join My Mailing List\'.</p>'."\n\n";
		
		foreach ($fields as $field){
			$fields_html .= generateField($field, $custom_field_labels);
		}
		
		$fields_html .= '<!-- ########## Contact Lists ########## -->'."\n";
		$lists = $ccConfigOBJ->contact_lists;
		$list_input_type = ($ccConfigOBJ->show_contact_lists ? 'checkbox' : 'hidden');
		
		if($list_input_type=='checkbox'){ 
			$fields_html .= '<h5>Your Interests</h5>'."\n";
			$fields_html .= '<p>Please select the areas of interest for which you would like to receive occasional email from us.</p>'."\n\n"; 
		}
		
		$checked=' checked="checked" ';
		foreach ($lists as $list){
			$fields_html .= '<input type="'.$list_input_type.'" '.$checked.' value="'.$list.'" name="Lists[]" id="list_'.$list.'" />'."\n";
			if($list_input_type=='checkbox'){ $fields_html .= '<label for="list_'.$list.'">'.$list.'</label><br />'."\n\n"; }
			$checked=false;
		}
		
	
	if($ccConfigOBJ->success_url || $ccConfigOBJ->failure_url){
	$fields_html .= '<!-- ########## Success / Failure Redirects ########## -->'."\n";
	}
	
	if($ccConfigOBJ->success_url){
		$fields_html .= '<input type="hidden" name="SuccessURL" value="'.$ccConfigOBJ->success_url.'" />'."\n";
	}
	
	if($ccConfigOBJ->failure_url){
		$fields_html .= '<input type="hidden" name="FailureURL" value="'.$ccConfigOBJ->failure_url.'" />'."\n\n";
	}
		
		
		
		
		$fields_html .= "\n".'<input type="submit" name="signup" id="signup" value="Join My Mailing List" />'."\n\n";
		
		$fields_html .= '</form>';
		

		
		return $fields_html;
	}
	
	function generateScripts(){
		global $ccConfigOBJ;
		$styles_uri = str_replace("setup/code.php", "styles.css", $_SERVER['SCRIPT_NAME']);
		$popup_js_uri = str_replace("setup/code.php", "popup.js", $_SERVER['SCRIPT_NAME']);
		$head_html = '<link href="'.$styles_uri.'" media="all" rel="stylesheet" type="text/css">'."\n";
		$head_html .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" language="javascript"></script>'."\n";
		$head_html .= '<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>'."\n";
		$head_html .= '<script src="'.$popup_js_uri.'" language="javascript" type="text/javascript"></script>'."\n";
		return $head_html;
	}

	function getIncludedFieldLabels(){
		global $ccConfigOBJ, $all_fields;
		$custom_field_labels = $ccConfigOBJ->custom_field_labels;
		$included_fields = $ccConfigOBJ->included_fields;
		$custom_fields = CustomFields();
		foreach($included_fields as $field){
			if(array_key_exists($field,$all_fields)){ $field_labels[$field] = $all_fields[$field][0]; }
			else { $field_labels[$field] = $custom_field_labels[(substr($field,11,1)-1)]; }
		}
		return $field_labels;
	}

?>