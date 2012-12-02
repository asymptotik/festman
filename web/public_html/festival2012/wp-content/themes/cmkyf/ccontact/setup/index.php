<?php
require('functions.php');
updateFile("../ccsfg.log", date('c')." - ### Setup Home Page ###. \r\n", "a");
if(!file_exists('../config.php')){ createConfig(); }
require('build_functions.php');
$page_name='home';
session_start();
require('../cc_class.php');
$ccUtilityOBJ = new CC_Utility();
if($_GET['debug']){$ccUtilityOBJ->curl_debug=true;}
$login = $ccUtilityOBJ->login;
if(!$_SESSION['username'] && $login!=""){
	header('Location: login.php');
}

$contact_lists = $ccUtilityOBJ->contact_lists;
require('header.php');
?>
		
		<p class="note">Welcome to the Signup Form setup utility.</p>
				
		<?php 
		$service_desc = $ccUtilityOBJ->getServiceDescription();
		if(!$service_desc && $login!=""){ 
		// $btn_class = "btn_error";
		$class2=$class3=$class4=' disabled '; $click2=$click3=$click4='onclick="return false;"';
		echo '<p class="error">It appears as though you may have changed your Constant Contact User Name or Password. 
		As a result, your signup form will not function until you update your credentials to the correct information.</p>'; 
		}
		?>
				
		<div class="grid_16 ">
		
		<a class="big_btn <?php echo $btn_class; ?>" href="credentials.php"><strong>1.</strong> Access Credentials</a>
		
		<?php if($login==""){ $class2=$class3=' disabled '; $click2=$click3='onclick="return false;"'; } ?>
		
		<a class="big_btn <?php echo $class2; ?>" <?php echo $click2; ?> href="lists.php"><strong>2.</strong> List Selection</a>
		
		<?php if(empty($contact_lists)){ $class3=' disabled '; $click3='onclick="return false;"'; } ?>
		
		<a class="big_btn <?php echo $class3; ?>" <?php echo $click3; ?> href="form.php"><strong>3.</strong> Web Form Generator</a>
		
		<?php if(empty($included_fields)){ $class4=' disabled '; $click4='onclick="return false;"'; } ?>
		
		<a class="big_btn <?php echo $class4; ?>" <?php echo $click4; ?> href="code.php"><strong>4.</strong> Preview Code</a>
		
		</div>
		
		
		</div>
		
	</body>

</html>