<?php
require_once dirname(__FILE__).'/library/admin_prebody_header.php';
require_once dirname(__FILE__).'/../library/config.php';
require_once dirname(__FILE__).'/../library/opendb.php';
require_once dirname(__FILE__).'/../library/utils.php';
require_once dirname(__FILE__).'/library/admin_utils.php';
require_once dirname(__FILE__).'/library/admin_htmlhead_start.php';
?>

<script type="text/javascript">

function addEvent()
{
    var form = document.forms["event_form"];
    
    form.elements["action"].value = "create_event";
    
    form.submit();
}

function editEvent(event_id)
{
    var form = document.forms["event_form"];
    form.elements["action"].value = "edit_event";
    form.elements["event_id"].value = event_id;
    
    form.submit();
}

function deleteEvent(event_id)
{
    var form = document.forms["event_form"];
    form.elements["action"].value = "delete_event";
    form.elements["event_id"].value = event_id;
    
    form.submit();
}

function cancelEventSelector()
{
    var form = document.forms["event_form"];
    form.elements["action"].value = "cancel_event_selector";
    
    form.submit();
}

</script>

<?php
require_once dirname(__FILE__).'/library/admin_head_end.php';
require_once dirname(__FILE__).'/library/admin_body_start.php';
require_once dirname(__FILE__).'/../objects/Event.php';

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}
?>



<FORM NAME="event_form" ID="event_form" METHOD="POST" ACTION="library/handler_event.php">
<input type="hidden" name="action" value="delete_events" /> 
<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
<input type="hidden" name="event_id" value="" />

<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;Event Selector</TD>
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
					<td>Event Name (Collateral Count)</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</thead>

			<?php
			$events = Event::getAllEvents();
            $num_rows = count($events);
            
            if($num_rows > 0)
			{
				for($i = 0; $i < $num_rows; $i++)
				{
					$event_id = $events[$i]->getId();
					$event_name = $events[$i]->getName();
					$collateral_count = Event::getCollateralCount($event_id);
					$action_id = uniqid("delete");
	
					echo "<tr class=\"border\">".
					"  <td><input type=\"checkbox\" name=\"event_ids[]\" value=\"".$event_id."\"></td>\n".
					"  <td>".$event_name."&nbsp;(".$collateral_count.")</td>\n".
					"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editEvent('".$event_id."');\">edit</a></td>\n".
					"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteEvent('".$event_id."');\">delete</a></td>\n".
					"</tr>\n";
				}
			}
			?>
		</TABLE>
		<?php
			if($num_rows == 0)
			{
			    echo "<BR/>\nNo Events Found!<BR/><BR/>";
			}
		    else
			{
			    echo "<BR/><BUTTON type=\"submit\">Delete Selected</BUTTON>";
			}
		?>
		<BUTTON type="button" onClick="javascript:addEvent();">Add Event</BUTTON>
		</TD>
	</TR>
	<TR><TD><BR></TD></TR>
	<TR><TD align="right"><BUTTON type="button" onClick="javascript:exitEventSelector();">Done</BUTTON>&nbsp;<br/></TD></TR>
	<TR><TD height="5"></TD></TR>
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