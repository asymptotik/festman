<?php
require_once dirname(__FILE__).'/../objects/ProgramItem.php';
require_once dirname(__FILE__).'/../objects/Act.php';
require_once dirname(__FILE__).'/../objects/Klass.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Panel.php';
require_once dirname(__FILE__).'/../objects/Film.php';
require_once dirname(__FILE__).'/../objects/Workshop.php';
require_once dirname(__FILE__).'/../objects/Installation.php';
require_once dirname(__FILE__).'/../objects/Collateral.php';

require_once dirname(__FILE__).'/library/admin_prebody_header.php';
require_once dirname(__FILE__).'/../library/config.php';
require_once dirname(__FILE__).'/../library/opendb.php';
require_once dirname(__FILE__).'/../library/utils.php';
require_once dirname(__FILE__).'/library/admin_utils.php';

require_once dirname(__FILE__).'/library/admin_htmlhead_start.php';
?>

<script type="text/javascript">

function addProgramItem()
{
    var form = document.forms["program_item_form"];
    
    form.elements["action"].value = "create_program_item";
    
    form.submit();
}

function editProgramItem(program_item_id)
{
    var form = document.forms["program_item_form"];
    form.elements["action"].value = "edit_program_item";
    form.elements["program_item_id"].value = program_item_id;
    
    form.submit();
}

function deleteProgramItem(program_item_id)
{
    var form = document.forms["program_item_form"];
    form.elements["action"].value = "delete_program_item";
    form.elements["program_item_id"].value = program_item_id;
    
    form.submit();
}

function cancelProgramItemSelector()
{
    var form = document.forms["program_item_form"];
    form.elements["action"].value = "cancel_program_item_selector";
    
    form.submit();
}

// not used
function selectProgramItem(program_item_id)
{
    var form = document.forms["program_item_form"];

    for(i = 0; i < form.elements["program_item_ids[]"].length; i++)
    {
        if(form.elements["program_item_ids[]"][i].value == program_item_id) 
        {
            form.elements["program_item_ids[]"][i].checked = true;
        }
        else
        {
            form.elements["program_item_ids[]"][i].checked = false;
        }
    }
}

</script>

<?php
require_once dirname(__FILE__).'/library/admin_head_end.php';
require_once dirname(__FILE__).'/library/admin_body_start.php';

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}
?>

<?php
$program_item_class = $_SESSION['current_program_item_class'];
if(isset($program_item_class) == false)
	$program_item_class = "Act";
$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item_class);
?>

<FORM NAME="program_item_form" ID="program_item_form" METHOD="POST" ACTION="library/handler_program_item.php">
    <input type="hidden" name="action" value="delete_program_items" /> 
    <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
    <input type="hidden" name="program_item_id" value="" /> 
    <input type="hidden" name="program_item_class" value="<?php echo $program_item_class ?>" />

<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;<?php echo $object_class_display_name; ?> Selector</TD>
		</TR>
	</THEAD>
	<TR>
		<TD>&nbsp;</TD>
	</TR>
	<TR>
		<TD width="700" align="center">

		<TABLE class="border">
			<thead class="h2">
				<tr class="border">
					<td>&nbsp;</td>
					<td><?php echo $object_class_display_name; ?> (Collateral Count)</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</thead>

			<?php
			$program_items = ProgramItem::getAllTypedProgramItems($program_item_class);
			$num_rows = count($program_items);

			if($num_rows > 0)
			{
				for($i = 0; $i < $num_rows; $i++)
				{
					$program_item_id = $program_items[$i]->getId();
					$program_item_name = $program_items[$i]->getName();
					$collateral_count = ProgramItem::getCollateralCount($program_item_id);
					$action_id = uniqid("delete");

					echo "<tr class=\"border\">".
					"  <td><input type=\"checkbox\" name=\"program_item_ids[]\" value=\"".$program_item_id."\"</td>\n".
					"  <td>".$program_item_name."&nbsp;(".$collateral_count.")</td>\n".
					"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editProgramItem('".$program_item_id."');\">edit</a></td>\n".
					"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteProgramItem('".$program_item_id."');\">delete</a></td>\n".
					"</tr>\n";
				}
			}
			?>
		</TABLE>
		<?php
		if($num_rows == 0)
		{
			echo "<BR/>\nNo " . $object_class_display_name . " objects found!<BR/><BR/>";
		}
		else
		{
			echo "<BR/><BUTTON type=\"submit\">Delete Selected</BUTTON>";
		}
		?>
		<BUTTON type="button" onClick="javascript:addProgramItem();">Add <?php echo $object_class_display_name; ?></BUTTON>
		</TD>
	</TR>
	<TR>
		<TD><BR>
		</TD>
	</TR>
	<TR>
		<TD align="right">
		<BUTTON type="button" onClick="javascript:exitProgramItemSelector();">Done</BUTTON>
		&nbsp;<br />
		</TD>
	</TR>
	<TR>
		<TD height="5"></TD>
	</TR>
</TABLE>

</FORM>

		<?php
		if(isset($_SESSION['action_message']))
		{
			echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
		}

		include dirname(__FILE__).'/../library/closedb.php';
		include dirname(__FILE__).'/library/admin_bodyhtml_end.php';
?>