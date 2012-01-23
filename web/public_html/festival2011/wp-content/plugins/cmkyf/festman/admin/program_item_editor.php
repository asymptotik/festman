<?php
require_once dirname(__FILE__).'/../objects/ProgramItem.php';
require_once dirname(__FILE__).'/../objects/RelatedPerson.php';
require_once dirname(__FILE__).'/../objects/Collateral.php';
require_once dirname(__FILE__).'/../objects/Act.php';
require_once dirname(__FILE__).'/../objects/Klass.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Workshop.php';
require_once dirname(__FILE__).'/../objects/Panel.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Installation.php';

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
    
var originValidator = new validator();
    originValidator.addValidator(new maxLengthValidator(128, "Origin must have a length less that or equal to 128."));
    
var descriptionValidator = new validator();
    descriptionValidator.addValidator(new requiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new maxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
function onSubmitProgramItemForm()
{
    var form = document.forms["program_item_form"];
    var elements = form.elements;
    
    var name = form.elements["program_item_name"].value;
    var url = form.elements["program_item_url"].value;
    var url_text = form.elements["program_item_url_text"].value;
    var origin = form.elements["program_item_origin"].value;
    var editor = tinyMCE.get("program_item_description");
    var description = editor.getContent();
    
    var errorString = nameValidator.validate(name);
    errorString = appendLine(errorString, urlValidator.validate(url));
    errorString = appendLine(errorString, urlTextValidator.validate(url_text));
    errorString = appendLine(errorString, originValidator.validate(origin));
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

function onCancelProgramItemEditor()
{
    var form = document.forms["program_item_form"];
    
    form.elements["action"].value = "cancel_program_item_editor";
    form.submit();
}

function addRelatedPerson()
{
    var form = document.forms["program_item_form"];
    
    form.elements["action"].value = "create_related_person";
    
    form.submit();
}

function editRelatedPerson(related_person_id)
{
    var form = document.forms["program_item_form"];
    form.elements["action"].value = "edit_related_person";
    form.elements["related_person_id"].value = related_person_id;
    
    form.submit();
}

function deleteRelatedPerson(related_person_id)
{
    var form = document.forms["program_item_form"];
    form.elements["action"].value = "delete_related_person";
    form.elements["related_person_id"].value = related_person_id;
    
    form.submit();
}

function deleteRelatedPersons()
{
    var form = document.forms["program_item_form"];
    form.elements["action"].value = "delete_related_persons";
    
    form.submit();
}

</script>



<?php
require_once dirname(__FILE__).'/library/admin_head_end.php';
require_once dirname(__FILE__).'/library/admin_body_start.php';
?>

<?php
if(isset($_SESSION['current_program_item']))
{
	$program_item = $_SESSION['current_program_item'];
	$program_item_class = $program_item->getObjectClass();
}
else
{
	$program_item = new ProgramItem();
	$program_item_class = $_SESSION['current_program_item_class'];
	$program_item->setObjectClass($program_item_class);
}

$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item_class);
$collateral_collection_control = new CollateralCollectionControl($program_item, "program_item_form", "program_item");

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}
?>

<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;<?php echo $object_class_display_name; ?> Editor</TD>
		</TR>
	</THEAD>
	<TR>
		<TD>&nbsp;</TD>
	</TR>
	<TR>
		<TD width="700">
		<FORM NAME="program_item_form" ID="program_item_form" METHOD="POST" ACTION="library/handler_program_item.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="save_program_item" /> 
			<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" />
			<input type="hidden" name="program_item_id" value="<?php echo $program_item->getId(); ?>" /> 
			<input type="hidden" name="related_person_id" value=""/>
			<input type="hidden" name="program_item_class" value="<?php echo $program_item_class; ?>" /> 
		<TABLE width="80%" align="center">
			<TR>
				<TD class="label">Name:</TD>
				<TD><INPUT type="text" name="program_item_name" size="50"
					value="<?php echo $program_item->getName(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Origin:</TD>
				<TD><INPUT type="text" name="program_item_origin" size="50"
					value="<?php echo $program_item->getOrigin(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Url:</TD>
				<TD><INPUT type="text" name="program_item_url" size="50"
					value="<?php echo $program_item->getUrl(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Url Text:</TD>
				<TD><INPUT type="text" name="program_item_url_text" size="50"
					value="<?php echo $program_item->getUrlText(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Description:</TD>
				<TD><TEXTAREA class="mceEditor" rows="10" cols="50" name="program_item_description"><?php echo $program_item->getDescription(); ?></TEXTAREA></TD>
			</TR>

			<TR>
				<TD>&nbsp;</TD>
				<TD></TD>
			</TR>



			<TR>
				<TD class="label">Related Persons:</TD>
				<TD>
				<TABLE width="100%">
					<TR>
						<TD width="100%" align="center">

						<TABLE width="100%" class="border">
							<thead class="h2">
								<tr class="border">
									<td>&nbsp;</td>
									<td>Name (Collateral Count)</td>
									<td>Role</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</thead>

							<?php
							$related_persons = &$program_item->getRelatedPersons();
							$num_rows = count($related_persons);

							if($num_rows > 0)
							{
								for($i = 0; $i < $num_rows; $i++)
								{
									$related_person_id = $related_persons[$i]->getId();
									$related_persons_name = $related_persons[$i]->getName();
									$related_persons_role = $related_persons[$i]->getRole();
									$collateral_count = ($related_person_id == NULL ? count($related_persons[$i]->getAllCollateral()) : RelatedPerson::getCollateralCount($related_person_id));
									$action_id = uniqid("delete");

									echo "<tr class=\"border\">".
									"  <td><input type=\"checkbox\" name=\"related_person_ids[]\" value=\"".$related_person_id."\"</td>\n".
									"  <td>".$related_persons_name."&nbsp;(".$collateral_count.")</td>\n".
									"  <td>".$related_persons_role."</td>\n".
									"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editRelatedPerson('".$related_person_id."');\">edit</a></td>\n".
									"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteRelatedPerson('".$related_person_id."');\">delete</a></td>\n".
									"</tr>\n";
								}
							}
							?>
						</TABLE>
						<?php
						if($num_rows == 0)
						{
							echo "<BR/>\nNo Related Persons Found!<BR/><BR/>";
						}
						else
						{
							echo "<BR/><BUTTON type=\"button\" onClick=\"javascript:deleteRelatedPersons();\">Delete Selected Persons</BUTTON>";
						}
						?>
						<BUTTON type="button" onClick="javascript:addRelatedPerson();">Add
						Related Person</BUTTON>
						</TD>
					</TR>
				</TABLE>
				</TD>
			</TR>

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
			
			<TR>
				<TD colspan="2" align="right"><br>
				<BUTTON type="button"
					onClick="javascript:onCancelProgramItemEditor();">Cancel</BUTTON>
				<BUTTON type="button"
					onClick="javascript:onSubmitProgramItemForm();">OK</BUTTON>
				</TD>
			</TR>
		</TABLE>
		</FORM>
		<br>
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