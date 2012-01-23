<?php
require_once (dirname(__FILE__).'/../objects/Collateral.php');
require_once (dirname(__FILE__).'/../objects/Location.php');

require_once (dirname(__FILE__).'/controls/CollateralCollectionControl.php');
require_once (dirname(__FILE__).'/controls/TextEditorScriptControl.php');

require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');

$text_editor_script_control = new TextEditorScriptControl();
$text_editor_script_control->render();
?>

<script type="text/javascript">

var nameValidator = new fmValidator();
    nameValidator.addValidator(new fmRequiredValidator("Please enter a Name."));
    nameValidator.addValidator(new fmMaxLengthValidator(256, "Name must have a length less that or equal to 256."));
    
var urlValidator = new fmValidator();
    urlValidator.addValidator(new fmRequiredValidator("Please enter a Url."));
    urlValidator.addValidator(new fmMaxLengthValidator(256, "Url must have a length less that or equal to 256."));
    
var urlTextValidator = new fmValidator();
    urlTextValidator.addValidator(new fmMaxLengthValidator(128, "Url Text must have a text length less that or equal to 128."));
    
var mapUrlValidator = new fmValidator();
    mapUrlValidator.addValidator(new fmMaxLengthValidator(1024, "Map Url must have a length less that or equal to 1024."));
    
var mapUrlTextValidator = new fmValidator();
    mapUrlTextValidator.addValidator(new fmMaxLengthValidator(128, "Map Url Text must have a text length less that or equal to 128."));
    
var addressValidator = new fmValidator();
    addressValidator.addValidator(new fmRequiredValidator("Please enter an Address."));
    addressValidator.addValidator(new fmMaxLengthValidator(64, "Address must have a length less that or equal to 64."));
    
var cityValidator = new fmValidator();
    cityValidator.addValidator(new fmRequiredValidator("Please enter a City."));
    cityValidator.addValidator(new fmMaxLengthValidator(32, "City must have a length less that or equal to 32."));
    
var stateValidator = new fmValidator();
    stateValidator.addValidator(new fmRequiredValidator("Please enter a State."));
    stateValidator.addValidator(new fmMaxLengthValidator(32, "State must have a length less that or equal to 32."));
    
var zipcodeValidator = new fmValidator();
    zipcodeValidator.addValidator(new fmRequiredValidator("Please enter a Zip Code."));
    zipcodeValidator.addValidator(new fmMaxLengthValidator(16, "Zip Code must have a length less that or equal to 16."));
    
var descriptionValidator = new fmValidator();
    descriptionValidator.addValidator(new fmRequiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new fmMaxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
function onSubmitLocationForm()
{
    var form = document.forms["location_form"];
    
    var name = form.elements["location_name"].value;
    var url = form.elements["location_url"].value;
    var url_text = form.elements["location_url_text"].value;
    var map_url = form.elements["location_map_url"].value;
    var map_url_text = form.elements["location_map_url_text"].value;
    var address = form.elements["location_address"].value;
    var city = form.elements["location_city"].value;
    var state = form.elements["location_state"].value;
    var zipcode = form.elements["location_zipcode"].value;
    var editor = tinyMCE.get("location_description");
    var description = editor.getContent();

    var errorString = nameValidator.validate(name);
    errorString = fmAppendLine(errorString, urlValidator.validate(url));
    errorString = fmAppendLine(errorString, urlTextValidator.validate(url_text));
    errorString = fmAppendLine(errorString, mapUrlValidator.validate(map_url));
    errorString = fmAppendLine(errorString, mapUrlTextValidator.validate(map_url_text));
    errorString = fmAppendLine(errorString, addressValidator.validate(address));
    errorString = fmAppendLine(errorString, cityValidator.validate(city));
    errorString = fmAppendLine(errorString, stateValidator.validate(state));
    errorString = fmAppendLine(errorString, zipcodeValidator.validate(zipcode));
    errorString = fmAppendLine(errorString, descriptionValidator.validate(description));
    
    if(errorString.length > 0)
    {
        alert(errorString);
    }
    else
    {
        form.submit();
    }
}

function onCancelLocationEditor()
{
    var form = document.forms["location_form"];
    
    form.elements["action"].value = "cancel_location_editor";
    form.submit();
}
</script>

<?php
if(isset($_SESSION['current_location']))
{
	$location = $_SESSION['current_location'];
}
else
{
	$location = new Location();
}

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}

$collateral_collection_control = new CollateralCollectionControl($location, "location_form", "location");
?>

<TABLE CLASS="border pallet"><THEAD class="h1"><TR CLASS="border"><TD>&nbsp;Location Editor</TD></TR></THEAD>
<TR><TD>&nbsp;</TD></TR>
<TR><TD width="700" align="center">
<FORM NAME="location_form" ID="location_form" METHOD="POST" ACTION="library/handler_location.php" enctype="multipart/form-data">
	<input type="hidden" name="action" value="save_location"/>
	<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>"/>
    <input type="hidden" name="location_id" value="<?php echo $location->getId(); ?>"/>
	<TABLE width="80%" align="center">
	  <TR><TD class="label">Name:</TD><TD><INPUT type="text" name="location_name" size="50" value="<?php echo $location->getName(); ?>"/></TD></TR>
	  <TR><TD class="label">Url:</TD><TD><INPUT type="text" name="location_url" size="50" value="<?php echo $location->getUrl(); ?>"/></TD></TR>
	  <TR><TD class="label">Url Text:</TD><TD><INPUT type="text" name="location_url_text" size="50" value="<?php echo $location->getUrlText(); ?>"/></TD></TR>
	  <TR><TD class="label">Map Url:</TD><TD><INPUT type="text" name="location_map_url" size="50" value="<?php echo $location->getMapUrl(); ?>"/></TD></TR>
	  <TR><TD class="label">Map Url Text:</TD><TD><INPUT type="text" name="location_map_url_text" size="50" value="<?php echo $location->getMapUrlText(); ?>"/></TD></TR>
	  <TR><TD class="label">Address:</TD><TD><INPUT type="text" name="location_address" size="50" value="<?php echo $location->getAddress(); ?>"/></TD></TR>
	  <TR><TD class="label">City:</TD><TD><INPUT type="text" name="location_city" size="50" value="<?php echo $location->getCity(); ?>"/></TD></TR>
	  <TR><TD class="label">State:</TD><TD><INPUT type="text" name="location_state" size="50" value="<?php echo $location->getState(); ?>"/></TD></TR>
	  <TR><TD class="label">Zip Code:</TD><TD><INPUT type="text" name="location_zipcode" size="50" value="<?php echo $location->getZipCode(); ?>"/></TD></TR>
	  <TR><TD class="label">Description:</TD><TD><TEXTAREA class="mceEditor" rows="10" cols="50" name="location_description"><?php echo $location->getDescription(); ?></TEXTAREA></TD></TR>
	  
		<TR>
			<TD>&nbsp;</TD>
		</TR>
		<TR>
			<TD class="label">Collateral:</TD>
			<TD><?php $collateral_collection_control->render(); ?></TD>
		</TR>
		<TR>
			<TD>&nbsp;</TD>
		</TR>
			
	  <TR><TD colspan="2" align="right"><br>
	    <BUTTON type="button" onClick="javascript:onCancelLocationEditor();">Cancel</BUTTON>
	    <BUTTON type="button" onClick="javascript:onSubmitLocationForm();">OK</BUTTON><BR><BR>
	  </TD></TR>
	</TABLE>
</FORM>
</TD></TR></TABLE>
<?php

if(isset($_SESSION['action_message']))
{
	echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
}

fmClearMessages();
?>