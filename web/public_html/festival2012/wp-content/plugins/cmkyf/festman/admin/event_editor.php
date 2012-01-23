<?php
require_once (dirname(__FILE__).'/../objects/Act.php');
require_once (dirname(__FILE__).'/../objects/Workshop.php');
require_once (dirname(__FILE__).'/../objects/Klass.php');
require_once (dirname(__FILE__).'/../objects/Film.php');
require_once (dirname(__FILE__).'/../objects/Panel.php');
require_once (dirname(__FILE__).'/../objects/Film.php');
require_once (dirname(__FILE__).'/../objects/Event.php');
require_once (dirname(__FILE__).'/../objects/Installation.php');
require_once (dirname(__FILE__).'/../objects/Collateral.php');
require_once (dirname(__FILE__).'/../objects/Location.php');
require_once (dirname(__FILE__).'/../objects/Program.php');
require_once (dirname(__FILE__).'/../objects/Program_ProgramItem.php');

require_once (dirname(__FILE__).'/controls/CollateralCollectionControl.php');
require_once (dirname(__FILE__).'/controls/TextEditorScriptControl.php');

require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');

$text_editor_script_control = new TextEditorScriptControl();
$text_editor_script_control->render();
?>

<script type="text/javascript">

var locationValidator = new fmValidator();
    locationValidator.addValidator(new rmRequiredValidator("Please select a Location."));
    
var nameValidator = new fmValidator();
    nameValidator.addValidator(new fmRequiredValidator("Please enter a Name."));
    nameValidator.addValidator(new fmMaxLengthValidator(256, "Name must have a length less that or equal to 256."));
    
var descriptionValidator = new fmValidator();
    descriptionValidator.addValidator(new fmRequiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new fmMaxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
var typeValidator = new fmValidator();
    typeValidator.addValidator(new fmRequiredValidator("Please select a Type."));
    
var startTimeValidator = new fmValidator();
    startTimeValidator.addValidator(new fmRequiredValidator("Please enter a Start Time."));
    startTimeValidator.addValidator(new fmDateTimeValidator("Start Time must have format mm/dd/yyyy hh:mm."));
    
var endTimeValidator = new fmValidator();
    endTimeValidator.addValidator(new fmRequiredValidator("Please enter an End Time."));
    endTimeValidator.addValidator(new fmDateTimeValidator("End Time must have format mm/dd/yyyy hh:mm."));

function onSubmitEventForm()
{
    var form = document.forms["event_form"];
    var elements = form.elements;
    var name = form.elements["event_name"].value;
    var location_id = form.elements["event_location_id"];
    var editor = tinyMCE.get("event_description");
    var description = editor.getContent();
    var start_time = form.elements["event_start_time"].value;
    var end_time = form.elements["event_end_time"].value;

    var errorString = locationValidator.validate(location_id);
    errorString = fmAppendLine(errorString, nameValidator.validate(name));
    errorString = fmAppendLine(errorString, descriptionValidator.validate(description));
    errorString = fmAppendLine(errorString, startTimeValidator.validate(start_time));
    errorString = fmAppendLine(errorString, endTimeValidator.validate(end_time));
    
    if(errorString.length > 0)
    {
        alert(errorString);
    }
    else
    {
        form.submit();
    }
}

function onCancelEventEditor()
{
    var form = document.forms["event_form"];
    
    form.elements["action"].value = "cancel_event_editor";
    form.submit();
}

function removeProgramItem(ppi_id)
{
    var form = document.forms["event_form"];
    form.elements["action"].value = "remove_program_item";
    form.elements["program_program_item_id"].value = ppi_id;
    
    form.submit();
}

// Handle the ObjectType (ProgramItem Type) option changes.
// we use php to predefine some javascript functions to help us set the
// ObjectType Instance option mennus to the available options from the db.
// Updated the UI in real time, saving the round trip.
function handleItemTypeOptionChange(index)
{
    var form = document.forms["event_form"];
    var object_class;
    var object_instance_parent;
    
    if(form.elements["object_classes[]"] == "[object NodeList]")
    {
        object_class = form.elements["object_classes[]"][index].value;
    }
    else
    {
        object_class = form.elements["object_classes[]"].value;
    }
    
    object_instance_parent = document.getElementById("object_instance_" + index);
    
   //alert("Type: object_class: " + object_class + ", " + object_instance_parent.nodeName);
    
    var option_html = '';
    
    //alert("OptionClass: " + object_class);
    if(object_class == "Act")
    {
        option_html = getActOptions();
    }
    else if(object_class == "Workshop")
    {
        option_html = getWorkshopOptions();
    }
    else if(object_class == "Panel")
    {
        option_html = getPanelOptions();
    }
    else if(object_class == "Film")
    {
        option_html = getFilmOptions();
    }
    else if(object_class == "Klass")
    {
        option_html = getKlassOptions();
    }
    else if(object_class == "Film")
    {
        option_html = getFilmOptions();
    }
    else if(object_class == "Installation")
    {
        option_html = getInstallationOptions();
    }
    
    if(option_html == '')
    {
        option_html = "<INPUT type=\"hidden\" name=\"instance_ids[]\" value=\"\" />Select a Type";
    }
    else
    {
        option_html = "<SELECT name=\"instance_ids[]\">" + option_html + "</SELECT>";
    }
    object_instance_parent.innerHTML = option_html;
}

function getActOptions()
{
<?php echo "    return '" . str_replace("\n", "", ProgramItem::getProgramItemSelectOptions("Act", "")) . "';"; ?>  
}

function getWorkshopOptions()
{
<?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Workshop", "") . "';"; ?>  
}

function getPanelOptions()
{
<?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Panel", "") . "';"; ?>  
}

function getFilmOptions()
{
<?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Film", "") . "';"; ?>  
}

function getKlassOptions()
{
<?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Klass", "") . "';"; ?>  
}

function getFilmOptions()
{
<?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Film", "") . "';"; ?>  
}

function getInstallationOptions()
{
<?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Installation", "") . "';"; ?>  
}
</script>

<?php
if(isset($_SESSION['current_event']))
{
	$event = $_SESSION['current_event'];
}
else
{
	$event = new Event();
}

$collateral_collection_control = new CollateralCollectionControl($event, "event_form", "event");
$location_select_options = Location::getLocationSelectOptions($event->getLocation_Id());

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}

// Creates a table row for a Program_ProgramItem.
// ppi_id[], positions[], start_times[], object_classes[], instance_ids[].
function generateProgram_ProgramItemTableRow($num, $ppi)
{
	if($ppi == NULL)
	{
		$ppi = new Program_ProgramItem();
	}

	$program_item = $ppi->getProgramItem();
	$program_item_type_name = '';
	
	if($program_item != NULL)
	{
	    $program_item_type_name = $program_item->getObjectClass();
	    $program_item_id = $program_item->getId();
	}

	echo '<TR>'. "\n";

	echo '<TD><INPUT type="hidden" name="ppi_ids[]" value="'. $ppi->getId() . '" /><INPUT type="text" name="positions[]" size="3" value="' . $ppi->getPosition() . '" /></TD>'. "\n";
	echo '<TD><INPUT type="text" name="start_times[]" size="20" value="' . $ppi->getStartTimeString() . '" /></TD>'. "\n";
	echo '<TD><SELECT name="object_classes[]" onChange="handleItemTypeOptionChange('.$num.')">'. "\n";
	if($program_item_type_name == '') echo     '<OPTION value="" selected="true">&lt;Select One&gt;</OPTION>' . "\n";
	echo     '<OPTION value="Act"'. ($program_item_type_name == "Act" ? ' selected="true"' : ''). '>Act</OPTION>' . "\n";
	echo     '<OPTION value="Workshop"'. ($program_item_type_name == "Workshop" ? ' selected="true"' : ''). '>Workshop</OPTION>' . "\n";
	echo     '<OPTION value="Installation"'. ($program_item_type_name == "Installation" ? ' selected="true"' : ''). '>Installation</OPTION>' . "\n";
	echo     '<OPTION value="Panel"'. ($program_item_type_name == "Panel" ? ' selected="true"' : ''). '>Panel</OPTION>' . "\n";
	echo     '<OPTION value="Film"'. ($program_item_type_name == "Film" ? ' selected="true"' : ''). '>Film</OPTION>' . "\n";
	echo     '<OPTION value="Klass"'. ($program_item_type_name == "Klass" ? ' selected="true"' : ''). '>Class</OPTION>' . "\n";
	echo     '<OPTION value="Film"'. ($program_item_type_name == "Film" ? ' selected="true"' : ''). '>Film</OPTION>' . "\n";
	echo     '</SELECT></TD>' . "\n";
	echo '<TD class="label_sm" id="object_instance_'.$num.'">' . "\n";
	if($program_item != NULL)
	{
		echo "<SELECT name=\"instance_ids[]\">\n".
		$program_item->getTypedSelectOptions().
		"</SELECT>\n";
	}
	else
	{
		echo "<INPUT type=\"hidden\" name=\"instance_ids[]\" value=\"\" />Select a Type.";
	}
	echo '</TD>' . "\n";
	echo '<TD>'. "\n";
	if($ppi->getId() != NULL)
	{
		echo '    <a href="javascript:void(0);" onClick="javascript:removeProgramItem('.$ppi->getId().');"><FONT size="-3">remove</FONT></a>' . "\n";
	    //echo '    <INPUT type="checkbox" name="checked_ppi_ids_'.$num.'" value="' . $ppi->getId() . '" /><FONT size="-3">delete</FONT>' . "\n";
	}
	echo '</TD>'. "\n";
	echo '</TR>'. "\n";
}
?>

<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;Event Editor</TD>
		</TR>
	</THEAD>
	<TR>
		<TD>&nbsp;</TD>
	</TR>
	<TR>
		<TD width="700" align="center">
		<FORM NAME="event_form" ID="event_form" METHOD="POST" ACTION="library/handler_event.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="save_event" /> 
		<input type="hidden" name="program_program_item_id" value="-1" />
		<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" />
		<input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>" /> 
		<TABLE width="80%" align="center">
			<TR>
				<TD class="label">Name:</TD>
				<TD><INPUT type="text" name="event_name" size="50"
					value="<?php echo $event->getName(); ?>" /></TD>
			</TR>
			<TR>
				<TD class="label">Location:</TD>
				<TD><SELECT name="event_location_id">
				<?php echo $location_select_options; ?>
				</SELECT></TD>
			</TR>
			<TR>
				<TD class="label">Start Time:</TD>
				<TD><INPUT type="text" name="event_start_time" size="30"
					value="<?php echo $event->getStartTimeString(); ?>" /> <SPAN class="label_md">(mm/dd/yyyy hh:mm)</SPAN></TD>
			</TR>
			<TR>
				<TD class="label">End Time:</TD>
				<TD><INPUT type="text" name="event_end_time" size="30"
					value="<?php echo $event->getEndTimeString(); ?>" /> <SPAN class="label_md">(mm/dd/yyyy hh:mm)</SPAN></TD>
			</TR>
			<TR>
				<TD class="label">Description:</TD>
				<TD><TEXTAREA class="mceEditor" rows="10" cols="50" name="event_description"><?php echo $event->getDescription(); ?></TEXTAREA></TD>
			</TR>
			<TR>
				<TD class="label" align="center" colspan="2"><BR>Program</TD>
			</TR>
			<TR>
				<TD colspan="2" align="right">
				<TABLE width="100%">
					<THEAD class="h2">
						<TR class="border">
							<TD>Pos</TD>
							<TD>Start Time</TD>
							<TD>Type</TD>
							<TD>Item</TD>
							<TD></TD>
						</TR>
					</THEAD>

					<?php
					$program = $event->getProgram();
					$count = 0;
					$num_old_rows = 0;
					
					if($program != NULL)
					{
						$ppis = &$program->getProgram_ProgramItems();

						while($count < count($ppis))
						{
							if($ppis[$count]->getId() != null) $num_old_rows++;
							
							generateProgram_ProgramItemTableRow($count, $ppis[$count]);
							$count++;
						}
					}
					
					//
                    // We want to leave at lease 2 un-saved rows to edit.
                    // 4 if there are no rows at all.
                    //
					if($count == 0) $num_extra_rows = 4;
					else $num_extra_rows = 2;
					
					$max = $num_extra_rows - $count + $num_old_rows;
					
					for($i = 0; $i < $max; $i++)
					{
						generateProgram_ProgramItemTableRow($count++, NULL);
					}
					
					?>

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
				<BUTTON type="button" onClick="javascript:onCancelEventEditor();">Done
				Editing</BUTTON>
				<BUTTON type="button" onClick="javascript:onSubmitEventForm();">Save</BUTTON><br><br>
				</TD>
			</TR>
			
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

fmClearMessages();
?>

