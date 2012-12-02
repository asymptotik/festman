<?php
require('functions.php');
updateFile("../ccsfg.log", date('c')." - ### Form Page ###. \r\n", "a");
if(!file_exists('../config.php')){ header('Location:index.php'); }
require('build_functions.php');
$page_name='form';
session_start();
require('../cc_class.php');
$ccUtilityOBJ = new CC_Utility();
$login = $ccUtilityOBJ->login;
if(!$_SESSION['username'] && $login!=""){
	header('Location: login.php');
}	
	$ccConfigOBJ = new CC_Config();
	$custom_field_labels = $ccConfigOBJ->custom_field_labels;
	$included_fields = $ccConfigOBJ->included_fields;
	$success_url = $ccConfigOBJ->success_url;
	$failure_url = $ccConfigOBJ->failure_url;
	
	if($_REQUEST['submit']){
		if($_REQUEST['fields']){
			if(!in_array('EmailAddress', $_REQUEST['fields'])) { $err_msg = '<p class="error">Email Address must be included in the form.</p>'; }
			else { updateConfigOptions();
			$included_fields_string = implode('&fields[]=',$included_fields);
			$custom_field_labels_string = implode('&custom_field_labels[]=',$custom_field_labels);
			header('Location:code.php');
			}
		}
	}

require('header.php');
?>

		
		<form action="" method="get" class="admin">
		
		
		<div class="grid_16 alpha">
			
			
			<fieldset>
			
			<legend>Form Options</legend>
			
			<table>
				
				<tr>
				<th><label for="success_url">Success URL</label></th>
				<td><input class="text" type="text" name="success_url" value="<?php echo $success_url; ?>" /></td>
				</tr>
				
				<tr>
				<th><label for="failure_url">Failure URL</label></th>
				<td><input class="text" type="text" name="failure_url" value="<?php echo $failure_url; ?>" /></td>
				</tr>
				
			</table>
			
			<p class="note">The options above determine what page the user will see after submitting the form. If left blank, default messaging will be displayed.</p>
			
			
			<table cellspacing="0" cellpadding="0">
			<tr>
			<th><label for="make_dialog">Place Signup form in a pop-up dialog</label></th>
			<?php $checked = ($ccUtilityOBJ->make_dialog ? ' checked="checked" ' : ''); ?>
			<td class="checkbox"><input type="checkbox" class="checkbox" <?php echo $checked ?> name="make_dialog" value="true" id="make_dialog" /></td>
			</tr>
			</table>
			
			
			</fieldset>
		

		
		</div>
		
		
		
		<div class="grid_8 alpha">
		
			<fieldset>
			
			<legend>Contact Information</legend>
			
			<?php echo $err_msg; ?>
			
			<table cellspacing="0" cellpadding="0">
			
			<?php 
			
foreach ($all_fields as $k=>$field) {
if($k == 'EmailAddress'){ $checked = ' disabled="disabled" checked="checked" '; } 
else if(in_array($k, $included_fields)){ $checked = ' checked="checked" '; }
else { $checked = ''; }

echo '<tr><th>';
echo '<label for="field_'.$k.'">'.$field[0].'</label>';
echo '</th><td class="checkbox"><input type="checkbox" '.$checked.' name="fields[]" value="'.$k.'" id="field_'.$k.'" />';
if($k == 'EmailAddress'){ echo '<input type="hidden" name="fields[]" value="'.$k.'" id="field_forced_'.$k.'" />'; }
echo '</td></tr>';

}
?>
	</table>
	</fieldset>
	</div>


	<div class="grid_8 omega">
	
	<fieldset>
	<legend>Custom Fields</legend>
	<table cellspacing="0" cellpadding="0">
	
<?php 
for($i=1; $i<16; $i++) {
$field = 'CustomField'.$i;
if(in_array($field, $included_fields)){ $checked = ' checked="checked" '; }
else { $checked = ''; }
echo '<tr><th>';
echo '<input class="text" type="text" value="'.$custom_field_labels[($i-1)].'" name="custom_field_labels[]" />';
echo '</th><td class="checkbox"><input type="checkbox" '.$checked.' name="fields[]" value="'.$field.'" id="field_'.$field.'" />';
echo '</td></tr>';

}



			?>
			
			</table>
			
			<p class="note">The custom field names entered above are stored locally 
			and will not update what is saved in your Constant Contact account.</p>
			
			</fieldset>
		
				<div class="action-btns">
			<button type="button" class="btn alt" ONCLICK="window.location.href='lists.php'">Back</button>
			<button type="submit" name="submit" value="submit" class="btn">Next</button>
		</div>
			
		</div>
		
		
		

		
		</form>


			<hr class="clear" />

		</div>
		
	</body>

</html>