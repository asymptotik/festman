<?php
require_once dirname(__FILE__).'/../objects/Collateral.php';
require_once dirname(__FILE__).'/../objects/CollateralLocation.php';
require_once dirname(__FILE__).'/../objects/Act.php';
require_once dirname(__FILE__).'/../objects/Klass.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Program.php';
require_once dirname(__FILE__).'/../objects/Panel.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Workshop.php';
require_once dirname(__FILE__).'/../objects/Installation.php';
require_once dirname(__FILE__).'/../objects/Event.php';
require_once dirname(__FILE__).'/../objects/RelatedPerson.php';
require_once dirname(__FILE__).'/../objects/Location.php';
require_once dirname(__FILE__).'/../objects/FileExtension.php';

require_once dirname(__FILE__).'/library/admin_prebody_header.php';
require_once dirname(__FILE__).'/../library/config.php';
require_once dirname(__FILE__).'/../library/opendb.php';
require_once dirname(__FILE__).'/../library/utils.php';
require_once dirname(__FILE__).'/library/admin_utils.php';

require_once dirname(__FILE__).'/library/admin_htmlhead_start.php';
?>

<script type="text/javascript">

function onSubmitCollateralSetForm()
{
    var form = document.forms["collateral_collection_form"];
    
    form.elements["action"].value = "add_selected_collateral";
    form.submit();
}

function checkExtension(extension)
{
	var found = false;
<?php
$file_extensions = FileExtension::getAllFileExtensions();
echo "	var suffix_list = new Array(".count($file_extensions).");\n\n";
for($i = 0; $i < count($file_extensions); $i++)
{
	echo "	suffix_list[".$i."] = \"".$file_extensions[$i]->getExtension()."\";\n";
}
?>

	for(i = 0; i < suffix_list.length; i++)
	{
		if(extension == suffix_list[i])
		{
			found = true;
			break;
		}
	}
	
	return found;
}

function validateFileName(filename)
{
	if(filename.length > 0)
	{
		var suffix = getFileSuffix(filename);
		if(suffix == null || checkExtension(suffix) == false)
		{
			return "Invalid file: '" + filename + "'";
		}
	}
	
	return "";
}

function validateFileNames()
{
	var message = "";
	
	var form = document.forms["upload_form"];
	for(var i = 0; i < form.elements["file[]"].length; i++)
	{
		message = appendLine(message, validateFileName(form.elements["file[]"][i].value));
	}
	
	if(message.length > 0)
	{
		alert(message);
	}	
}

function onSubmitUploadForm()
{
    var form = document.forms["upload_form"];
    validateFileNames();
    form.elements["action"].value = "upload_collateral";
    form.submit();
}

function onCancelCollateralSetEditor()
{
    var form = document.forms["collateral_collection_form"];
    
    form.elements["action"].value = "cancel_collateral_collection_editor";
    form.submit();
}

function onFilterCollateral()
{
    var form = document.forms["collateral_collection_form"];
    
    form.elements["action"].value = "filter_collateral";
    form.submit();
}

</script>

<?php
require_once dirname(__FILE__).'/library/admin_head_end.php';
require_once dirname(__FILE__).'/library/admin_body_start.php';
?>

<?php
if(isset($_SESSION['current_collateral_collection_type']))
{
	$collateral_collection_type = $_SESSION['current_collateral_collection_type'];
}
else
{
	$collateral_collection_type = "program_item";
}

if(isset($_SESSION['current_collateral_location_name']))
{
	$collateral_location_name = $_SESSION['current_collateral_location_name'];
}
else
{
	$collateral_location_name = "all";
}

$collateral_collection = NULL;
$collateral_collection_name = '';
$collateral_collection_second_name = '';
$collateral_locations = CollateralLocation::getAllCollateralLocations();

switch($collateral_collection_type)
{

	case "program_item":
		if(isset($_SESSION['current_program_item']))
		{
			$collateral_collection = $_SESSION['current_program_item'];
			$collateral_collection_name = ProgramItem::getObjectClassDisplayName($collateral_collection->getObjectClass());
			$collateral_collection_second_name = $collateral_collection->getName();
		}
		break;
	case "related_person":
		if(isset($_SESSION['current_related_person']))
		{
			$collateral_collection = $_SESSION['current_related_person'];
			$collateral_collection_name = "Related Person";
			$collateral_collection_second_name = $collateral_collection->getName();
		}
		break;
	case "location":
		if(isset($_SESSION['current_location']))
		{
			$collateral_collection = $_SESSION['current_location'];
			$collateral_collection_name = "Location";
			$collateral_collection_second_name = $collateral_collection->getName();
		}
		break;
	case "event":
		if(isset($_SESSION['event']))
		{
			$collateral_collection = $_SESSION['event'];
			$collateral_collection_name = "Event";
			$collateral_collection_second_name = $collateral_collection->getName();
		}
		break;
		
}

$selected_collateral_ids = array();

if(isset($collateral_collection))
{
	$selected_collateral = $collateral_collection->getAllCollateral();
	$selected_collateral_count = count($selected_collateral);
	
	for($i = 0; $i < $selected_collateral_count; $i++)
	{
		$selected_collateral_ids[$selected_collateral[$i]->getId()] = true;
	}
}

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}

?>
<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;Collateral Set Editor for <?php echo $collateral_collection_name?>:<BR>
			&nbsp;<?php echo $collateral_collection_second_name ?></TD>
		</TR>
	</THEAD>
	<TR>
		<TD>&nbsp;</TD>
	</TR>
	<TR>
		<TD width="700">
		<FORM NAME="collateral_collection_form" ID="collateral_collection_form" METHOD="POST" ACTION="library/handler_collateral_collection.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="add_selected_collateral" /> 
		<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
		<input type="hidden" name="collateral_location_name" value="<?php echo $collateral_location_name ?>" /> 
		<input type="hidden" name="collateral_collection_type" value="<?php echo $collateral_collection_type ?>" />
		<TABLE width="80%" align="center">
			<TR><TD colspan="2" align="right">
				<SELECT name="collateral_location_name" OnChange="onFilterCollateral()">
					<OPTION value="all"<?php echo ($collateral_location_name == "all" ? " selected=\"true\"" : "")?>>all</OPTION>
					<?php
					    // use the association to do a reverse lookup.
						$collateral_location_to_name_assoc = array();
						$collateral_location_count = count($collateral_locations);
						for($i = 0; $i < $collateral_location_count; $i++)
						{
							$collateral_location_to_name_assoc[$collateral_locations[$i]->getLocation()] = $collateral_locations[$i]->getName();
							echo "<OPTION value=\"".$collateral_locations[$i]->getName()."\"".($collateral_location_name == $collateral_locations[$i]->getName() ? " selected=\"true\"" : "").">".$collateral_locations[$i]->getName()."</OPTION>\n";
						}
					?>
				</SELECT>
				<BUTTON type="button" onClick="javascript:onFilterCollateral();">filter</BUTTON>
			</TD></TR>
			<TR>
				<TD colspan="2">
				<TABLE width="100%" class="border">
				    <THEAD class="h2">
						<tr class="border">
							<td width="30">&nbsp;</td>
							<td>Name</td>
							<td width="100">Store</td>
						</tr>
					</THEAD>
					<TR><TD colspan="3">
					<DIV STYLE="height: 200px; width: 100%; overflow: auto;">
						<TABLE width="100%">
							<?php
							$collateral_selection = Collateral::getCollateralByCollateralLocation($collateral_location_name);
							$collateral_selection_count = count($collateral_selection);
							
							if($collateral_selection_count > 0)
							{
								for($i = 0; $i < $collateral_selection_count; $i++)
								{
									$collateral_selection_collateral = $collateral_selection[$i];
									$collateral_selection_collateral_name = $collateral_selection_collateral->getName();
									if(isset($collateral_location_to_name_assoc[$collateral_selection_collateral->getLocation()]) == true)
									{
										$collateral_selection_collateral_location = $collateral_location_to_name_assoc[$collateral_selection_collateral->getLocation()];
									}
									else
									{
										$collateral_selection_collateral_location = "external";
									}
									
									$collateral_selection_is_selected = false;
									if(isset($selected_collateral_ids[$collateral_selection_collateral->getId()]))
									{
										$collateral_selection_is_selected = true;
									}
									
									if($i % 2 == 0)
										echo "          <TR class=\"stripe\">\n";
									else
										echo "          <TR>\n";
										
									echo "            <TD width=\"30\"><input type=\"checkbox\" name=\"object_collateral_ids[]\" value=\"".$collateral_selection_collateral->getId()."\"". ($collateral_selection_is_selected == true ? " checked=\"true\" disabled=\"true\"" : "") ." /></TD>\n";
									echo "            <TD><A href=\"javascript:void(0)\" OnClick=\"javascript:window.open('../" . $collateral_selection_collateral->getUrl() . "', 'collateral')\">" . $collateral_selection_collateral_name . "</A></TD>\n";
									echo "            <TD width=\"100\">" . $collateral_selection_collateral_location . "</TD>\n";
									echo "          </TR>\n";
								}
							}
							?>
						</TABLE>
					</DIV>
				    </TD></TR>
				</TABLE>
				</TD>
			</TR>
			<TR>
				<TD colspan="2" align="right"><br>
				<BUTTON type="button" onClick="javascript:onCancelCollateralSetEditor();">Cancel</BUTTON>
				<BUTTON type="button" onClick="javascript:onSubmitCollateralSetForm();">OK</BUTTON>
				</TD>
			</TR>
		</TABLE>
		</FORM>


		<FORM NAME="upload_form" ID="upload_form" METHOD="POST" ACTION="library/handler_collateral_collection.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="upload_collateral" /> 
		<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" />
		<input type="hidden" name="collateral_collection_type" value="<?php echo $collateral_collection_type ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="24000000" />
		<TABLE width="80%" align="center">

			<TR>
				<TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD>
			</TR>
			<TR>
				<TD colspan="3" align="center" class="label">Upload <?php echo $collateral_collection_name?> Collateral</TD>
			</TR>
			<TR>
				<TD class="label">Collateral 1:</TD>
				<TD colspan="2"><INPUT type="file" name="file[]" size="40" /></TD>
			</TR>
			<TR>
				<TD class="label">Collateral 2:</TD>
				<TD colspan="2"><INPUT type="file" name="file[]" size="40" /></TD>
			</TR>
			<TR>
				<TD class="label">Collateral 3:</TD>
				<TD colspan="2"><INPUT type="file" name="file[]" size="40" /></TD>
			</TR>
			<TR>
				<TD>&nbsp;</TD>
				<TD colspan="2"><INPUT type="checkbox" name="overwrite_existing_collateral" />Overwrite Existing Collateral</TD>
				<TD align="right"><BUTTON type="button" onClick="javascript:onSubmitUploadForm();">Upload</BUTTON>
				</TD>
			</TR>
		</TABLE>
		</FORM>
		<br><br>
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