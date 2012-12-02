<?php
require_once('functions.php');
updateFile("../ccsfg.log", date('c')." - ### Credentials Page ###. \r\n", "a");
if(!file_exists('../config.php')){ header('Location: index.php'); }
require_once('build_functions.php');
$page_name = 'credentials';
session_start();
require_once('../cc_class.php');
$ccUtilityOBJ = new CC_Utility();
$login = $ccUtilityOBJ->login;
if(!$_SESSION['username'] && $login!=""){
	header('Location: login.php');
}
	
	if($_REQUEST['submit']){
		if(updateConfigCredentials($_REQUEST['username'], $_REQUEST['password'])){
			$ccUtilityOBJ->login = $_REQUEST['username'];
			$ccUtilityOBJ->password = $_REQUEST['password'];
			$ccUtilityOBJ->requestLogin = $ccUtilityOBJ->apikey."%".$ccUtilityOBJ->login.":".$ccUtilityOBJ->password;
            	$ccUtilityOBJ->apiPath = 'https://api.constantcontact.com/ws/customers/'.$ccUtilityOBJ->login;
            	$credentials = $ccUtilityOBJ->getServiceDescription();
			if($credentials==200){
				$_SESSION['username'] = $_REQUEST['username'];
				header('Location:lists.php');
			} else {
				updateConfigCredentials('', '', 'reset');		
				switch ($credentials){
				case '401':
					$err_msg = '<p class="error">We could not authenticate those credentials! <br />
					Please make sure that you are using the correct Constant Contact User Name and Password. (401)</p>';
					updateFile("../ccsfg.log", date('c')." - 401 Error authenticating credentials using service description for ".$_REQUEST['username'].".\r\n", "a");
				break;				
				case '500':
					$err_msg = '<p class="error">An Internal Server Error Occurred. Please try again. (500)</p>';
					updateFile("../ccsfg.log", date('c')." - 500 Server Error accessing service description for ".$_REQUEST['username'].".\r\n", "a");
				break;
				default:
					$err_msg = '<p class="error">We could not authenticate those credentials! Please try again. (?)</p>';
					updateFile("../ccsfg.log", date('c')." - ??? Error authenticating credentials using service description for ".$_REQUEST['username'].".\r\n", "a");
				break;
				}
			}
		}
		
		
	}


require('header.php');
?>

		
		<form action="" method="post" class="admin">
		
		<fieldset>
		
		<legend>Access Credentials</legend>
			
			<?php echo $err_msg; ?>
		
			<table cellspacing="0" cellpadding="0">
			
			<tr>
			<th><label for="username">User Name</label> <em>(ex: joesflowers)</em></th>
			<td><input class="text" type="text" name="username" value="" /></td>
			</tr>
			
			<tr>
			<th><label for="password">Password</label></th>
			<td><input class="text" type="password" name="password" value="" /></td>
			</tr>

			
			</table>
			
			
		</fieldset>
		
		<div class="action-btns">
			<button type="button" class="btn alt" ONCLICK="window.location.href='index.php'">Back</button>
			<button type="submit" name="submit" value="submit" class="btn">Next</button>
		</div>
			
		
		</form>
		
		
			<hr class="clear" />	
		</div>
	</body>
</html>