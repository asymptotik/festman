<?php
require_once('functions.php');
updateFile("../ccsfg.log", date('c')." - ### Code Page ###. \r\n", "a");
if(!file_exists('../config.php')){ header('Location: index.php'); }
require_once('build_functions.php');
$page_name = 'code';
session_start();
if(!$_SESSION['username']){
		 header('Location: login.php');
}
	require('../cc_class.php');
	
	$ccConfigOBJ = new CC_Config();
	$ccUtilityOBJ = new CC_Utility();

	
$fragment = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\r\n";
$fragment .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">'."\r\n";
$fragment .= '<head><title>Signup Form</title></head>'."\r\n";
$fragment .= '<body>'."\r\n";
$fragment .= '<!-- SIGNUP FORM CODE BELOW THIS LINE -->'."\r\n\r\n";
$fragment .= generateFields($_REQUEST)."\r\n\r\n";
$fragment .= '<!-- SIGNUP FORM CODE ABOVE THIS LINE -->'."\r\n";
$fragment .= '</body></html>';

require('header.php');
?>

		
		<div class="grid_9 alpha">
		
		<form class="admin" name="source_code">
		
		<fieldset>
			
		<legend>Form HTML Code</legend>

		<textarea style="width:100%; height:400px" id="form_code"><?php echo generateFields(); ?><?php if($ccConfigOBJ->make_dialog) { echo "\n\n".'<a href="javascript:return false;" id="ccsfg_btn">Sign Me Up</a>'; } ?>
		</textarea>
		
		<input type="hidden" name="fragment" value="<?php echo htmlentities($fragment); ?>" />
		<div class="action-btns">
			<button type="button" class="btn blue small" onclick="get_element('preview_div').innerHTML=get_element('form_code').value">Update Preview</button>
		</div>		
		</fieldset>
		
		
		
		
		<?php if($ccUtilityOBJ->make_dialog) { ?>
		
		<fieldset>
			
		<legend>Dialog Pop-up Code</legend>
		
		<textarea style="width:100%; height:150px" id="form_pop_code"><?php echo generateScripts(); ?></textarea>
		<p class="note">Place this code in between the &lt;head&gt; and &lt;/head&gt; tags in your webpage.</p>
		</fieldset>
		
		<?php } ?>
		
		
		
		</form>
		
		<div class="action-btns">
			<button type="button" class="btn alt" ONCLICK="window.location.href='form.php'">Back</button>
			<button type="button" class="btn" ONCLICK="window.location.href='index.php'">Home</button>
		</div>
		
		</div>
		
		<div class="grid_7 omega">
		
			<fieldset class="preview">
				
				<legend>Preview</legend>
				
				<div id="preview_div">
				<?php echo generateFields(); ?>
				</div>
		
			</fieldset>

		
		</div>
		
			<hr class="clear" />

		</div>


		
	</body>

</html>