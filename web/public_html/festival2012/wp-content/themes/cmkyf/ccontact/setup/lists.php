<?php
require('functions.php');
updateFile("../ccsfg.log", date('c')." - ### List Page ###. \r\n", "a");
if(!file_exists('../config.php')){ header('Location:index.php'); }
require('build_functions.php');
$page_name='lists';
session_start();
require('../cc_class.php');
$ccUtilityOBJ = new CC_Utility();
$login = $ccUtilityOBJ->login;
if(!$_SESSION['username'] && $login!=""){
	header('Location: login.php');
}
	if($_REQUEST['submit']){
		if($_REQUEST['lists']){
			if(updateConfigLists()){
				header('Location:form.php');
			}
		} else { $err_msg = '<p class="error">Please select at least one list.</p>'; }
	}

require('header.php');	
?>

		
		<form action="" method="post" class="admin">
		
		
		<div class="grid_10 alpha">
		
		<fieldset>
		
		<legend>Choose Lists</legend>

		
		<?php echo $err_msg; ?>
		
		<table cellspacing="0" cellpadding="0">
		
		<?php 
		$ccListOBJ = new CC_List();
		$ccUtilityOBJ = new CC_Utility(); 
		if($_GET['debug']){$ccUtilityOBJ->curl_debug=true;}
		$allLists = $ccListOBJ->getLists('', true);
	
		foreach ($allLists as $k=>$item) {
		$checked = '';
		
			if($ccListOBJ->contact_lists){	
				if (in_array($item['title'],$ccListOBJ->contact_lists)) {
					$checked = ' checked ';
				}
			}
		
		echo '<tr><th><label for="chk_'. htmlspecialchars($k, ENT_QUOTES) . '">'.htmlspecialchars($item['title'], ENT_QUOTES).'</label></th><td class="checkbox"><input type="checkbox" '.$checked . $disabled .' class="checkbox" name="lists[]" value="'.htmlspecialchars($item['title'], ENT_QUOTES).'" id="chk_'.$k.'" /></td></tr>';
		}
	
		?>
		
		</table>
		
		</fieldset>
		
		</div>
		
		<div class="grid_6 omega">
		
			<fieldset>
			<legend>List Options</legend>
			
			<table cellspacing="0" cellpadding="0">
			<tr>
			<th><label for="show_contact_lists">Display Contact Lists to Visitors</label></th>
			<?php $checked = ($ccUtilityOBJ->show_contact_lists ? ' checked="checked" ' : ''); ?>
			<td class="checkbox"><input type="checkbox" class="checkbox" <?php echo $checked ?> name="show_contact_lists" value="true" id="show_contact_lists" /></td>
			</tr>
			</table>
			
			<p class="note">You may give users the option of choosing their lists from the group of lists that 
			you make available or you can hide them and force the users to sign up to all of your selected 
			lists by toggling the <strong>Display Contact Lists to Visitors</strong> option above.</p>
			
			</fieldset>
		
		<div class="action-btns">
			<button type="button" class="btn alt" ONCLICK="window.location.href='credentials.php'">Back</button>
			<button type="submit" name="submit" value="submit" class="btn">Next</button>
		</div>
		
		</div>
		
		
		
		</form>
		
			<hr class="clear" />	
		</div>
	</body>
</html>
