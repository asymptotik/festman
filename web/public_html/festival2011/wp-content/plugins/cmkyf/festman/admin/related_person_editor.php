<?php
require_once dirname(__FILE__).'/../objects/Act.php';
require_once dirname(__FILE__).'/../objects/Klass.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Panel.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Workshop.php';
require_once dirname(__FILE__).'/../objects/Installation.php';
require_once dirname(__FILE__).'/../objects/RelatedPerson.php';
require_once dirname(__FILE__).'/../objects/Collateral.php';

require_once dirname(__FILE__).'/controls/CollateralCollectionControl.php';
require_once dirname(__FILE__).'/controls/TextEditorScriptControl.php';

require_once dirname(__FILE__).'/library/admin_prebody_header.php';
require_once dirname(__FILE__).'/../library/config.php';
require_once dirname(__FILE__).'/../library/opendb.php';
require_once dirname(__FILE__).'/../library/utils.php';
require_once dirname(__FILE__).'/library/admin_utils.php';

require_once dirname(__FILE__).'/library/admin_htmlhead_start.php';

$text_editor_script_control = new TextEditorScriptControl();
$text_editor_script_control->render();
?>

<script type="text/javascript">

var nameValidator = new validator();
    nameValidator.addValidator(new requiredValidator("Please enter a Name."));
    nameValidator.addValidator(new maxLengthValidator(256, "Name must have a length less that or equal to 256."));
    
var urlValidator = new validator();
    urlValidator.addValidator(new requiredValidator("Please enter a Url."));
    urlValidator.addValidator(new maxLengthValidator(256, "Url must have a length less that or equal to 256."));

var urlTextValidator = new validator();
    urlTextValidator.addValidator(new maxLengthValidator(128, "Url must have a text length less that or equal to 128."));
    
var descriptionValidator = new validator();
    descriptionValidator.addValidator(new requiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new maxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
var roleValidator = new validator();
    roleValidator.addValidator(new requiredValidator("Please select a Role."));
    
function onSubmitRelatedPersonForm()
{
    var form = document.forms["related_person_form"];
    var elements = form.elements;
    
    var name = form.elements["related_person_name"].value;
    var url = form.elements["related_person_url"].value;
    var url_text = form.elements["related_person_url_text"].value;
    var editor = tinyMCE.get("related_person_description");
    var description = editor.getContent();
    var related_person_role = form.elements["related_person_role"].value;
    
    var errorString = nameValidator.validate(name);
    errorString = appendLine(errorString, urlValidator.validate(url));
    errorString = appendLine(errorString, urlTextValidator.validate(url_text));
    errorString = appendLine(errorString, roleValidator.validate(related_person_role));
    errorString = appendLine(errorString, descriptionValidator.validate(description));

    if(errorString.length > 0)
    {
        alert(errorString);
    }
    else
    {
        form.submit();
    }
}

function onCancelRelatedPersonEditor()
{
    var form = document.forms["related_person_form"];
    
    form.elements["action"].value = "cancel_related_person_editor";
    form.submit();
}

</script>

<?php
require_once dirname(__FILE__).'/library/admin_head_end.php';
require_once dirname(__FILE__).'/library/admin_body_start.php';
?>

<?php
if(isset($_SESSION['current_related_person']))
{
	$related_person = $_SESSION['current_related_person'];
}
else
{
	$related_person = new RelatedPerson();
}

$program_item        = $_SESSION['current_program_item'];
$related_person_role = $related_person->getRole();

$collateral_collection_control = new CollateralCollectionControl($related_person, "related_person_form", "related_person");

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}

?>
<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;Related Person Editor <font size="-1"><?php echo $program_item->getObjectClass() . " - " . $program_item->getName() ?></font></TD>
		</TR>
	</THEAD>
	<TR>
		<TD>&nbsp;</TD>
	</TR>
	<TR>
		<TD width="700">
		<FORM NAME="related_person_form" ID="related_person_form" METHOD="POST" ACTION="library/handler_program_item.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="store_related_person" /> 
		<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
		<input type="hidden" name="related_person_id" value="<?php echo $related_person->getId(); ?>" /> 
		<TABLE width="80%" align="center">
			<TR>
				<TD class="label">Name:</TD>
				<TD><INPUT type="text" name="related_person_name" size="50"
					value="<?php echo $related_person->getName(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Url:</TD>
				<TD><INPUT type="text" name="related_person_url" size="50"
					value="<?php echo $related_person->getUrl(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Url Text:</TD>
				<TD><INPUT type="text" name="related_person_url_text" size="50"
					value="<?php echo $related_person->getUrlText(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Role:</TD>
				<TD><SELECT name="related_person_role">
					<OPTION value=""<?php echo ($related_person_role == "" ? " selected=\"true\"" : ""); ?>>&lt;Select&gt;</OPTION>
					<OPTION value="artist"<?php echo ($related_person_role == "artist" ? " selected=\"true\"" : ""); ?>>Artist</OPTION>
					<OPTION value="video"<?php echo ($related_person_role == "video" ? " selected=\"true\"" : ""); ?>>Video</OPTION>
					<OPTION value="lead"<?php echo ($related_person_role == "lead" ? " selected=\"true\"" : ""); ?>>Lead</OPTION>
					<OPTION value="member"<?php echo ($related_person_role == "member" ? " selected=\"true\"" : ""); ?>>Member</OPTION>
				</SELECT></TD>
			</TR>
			<TR>
				<TD class="label">Description:</TD>
				<TD><TEXTAREA class="mceEditor" rows="10" cols="50" name="related_person_description"><?php echo $related_person->getDescription(); ?></TEXTAREA></TD>
			</TR>



			<TR>
				<TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD class="label">Collateral:</TD>
				<TD><?php $collateral_collection_control->render(); ?></TD>
		</TR>
		
		
	  <TR>
		<TD colspan="2" align="right"><br>
	    <BUTTON type="button" onClick="javascript:onCancelRelatedPersonEditor();">Cancel</BUTTON>
	    <BUTTON type="button" onClick="javascript:onSubmitRelatedPersonForm();">OK</BUTTON><BR><BR>
	  </TD></TR>
	</TABLE>
</FORM>
</TD>
</TR>
</TABLE>
<?php

if(isset($_SESSION['action_message']))
{
	echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
}

include dirname(__FILE__).'/../library/closedb.php';
include dirname(__FILE__).'/library/admin_bodyhtml_end.php';
?>