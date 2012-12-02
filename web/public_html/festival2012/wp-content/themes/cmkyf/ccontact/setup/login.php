<?php
require('functions.php');
updateFile("../ccsfg.log", date('c')." - ### Login Page ###. \r\n", "a");
if(!file_exists('../config.php')){ header('Location:index.php'); }
require('build_functions.php');
$page_name='login';
session_start();
require('../cc_class.php');
$ccUtilityOBJ = new CC_Utility();
$login = $ccUtilityOBJ->login;
$password = $ccUtilityOBJ->password;

if($login==""){ header('Location: index.php'); }

if($_REQUEST['submit']){
		if($_REQUEST['username'] == $login && $_REQUEST['password'] == $password){
			$_SESSION['username'] = $_REQUEST['username']; 
			// print_r($_SESSION);
			header('Location: index.php');
		}
		else { $err_msg = '<p class="error">The User Name and Password does not match the credentials that were last used to successfully access this utility. If you have changed you Constant Contact User Name and/or Password recently please login using your old User Name and Password and update your credentials accordingly.</p>';
		updateFile("../ccsfg.log", date('c')." - Error authenticating credentials stored in config file. (".$_REQUEST['username']." : ".$login.")\r\n", "a");
		 }
}

if($_REQUEST['logout']){
	session_destroy();
}



require('header.php');
?>

		
<form action="login.php" method="post" class="admin" name="login">

<fieldset>

		<legend>Login</legend>

					<?php echo $err_msg; ?>
		
			<table cellspacing="0" cellpadding="0">
			
			<tr>
			<th><label for="username">User Name</label></th>
			<td><input class="text" type="text" name="username" id="username" /></td>
			</tr>
			
			
			<tr>
			<th><label for="password">Password</label></th>
			<td><input class="text" type="password" name="password" id="password" /></td>
			</tr>
			
			</table>

		<p class="note">It appears that this signup form has already been configured. As a result you will need to verify the access credentials to continue.</p>

</fieldset>
		
		<div class="action-btns">
			<button type="submit" name="submit" value="submit" class="btn">Continue</button>
		</div>
</form>

		</div>
			
	</body>

</html>