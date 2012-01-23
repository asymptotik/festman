<?php
require_once (dirname(__FILE__).'/../objects/Location.php');
require_once (dirname(__FILE__).'/../objects/Collateral.php');

require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');
?>

<script type="text/javascript">

function addLocation()
{
    var form = document.forms["location_form"];
    
    form.elements["action"].value = "create_location";
    
    form.submit();
}

function editLocation(location_id)
{
    var form = document.forms["location_form"];
    form.elements["action"].value = "edit_location";
    form.elements["location_id"].value = location_id;
    
    form.submit();
}

function deleteLocation(location_id)
{
    var form = document.forms["location_form"];
    form.elements["action"].value = "delete_location";
    form.elements["location_id"].value = location_id;
    
    form.submit();
}

function cancelLocationSelector()
{
    var form = document.forms["location_form"];
    form.elements["action"].value = "cancel_location_selector";
    
    form.submit();
}

</script>

<?php

if(isset($_SESSION['error_message']))
{
	echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}
?>

<FORM NAME="location_form" ID="location_form" METHOD="POST" ACTION="library/handler_location.php">
<input type="hidden" name="action" value="delete_locations" /> 
<input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
<input type="hidden" name="location_id" value="" />

<TABLE CLASS="border pallet">
	<THEAD class="h1">
		<TR CLASS="border">
			<TD>&nbsp;Location Selector</TD>
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
					<td>Location Name (Collateral Count)</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</thead>

			<?php
			$locations = Location::getAllLocations();
            $num_rows = count($locations);
            
            if($num_rows > 0)
			{
				for($i = 0; $i < $num_rows; $i++)
				{
					$location_id = $locations[$i]->getId();
					$location_name = $locations[$i]->getName();
					$collateral_count = Location::getCollateralCount($location_id);
					$action_id = uniqid("delete");
	
					echo "<tr class=\"border\">".
					"  <td><input type=\"checkbox\" name=\"location_ids[]\" value=\"".$location_id."\"</td>\n".
					"  <td>".$location_name."&nbsp;(".$collateral_count.")</td>\n".
					"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editLocation('".$location_id."');\">edit</a></td>\n".
					"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteLocation('".$location_id."');\">delete</a></td>\n".
					"</tr>\n";
				}
			}
			?>
		</TABLE>
		<?php
			if($num_rows == 0)
			{
			    echo "<BR/>\nNo Locations Found!<BR/><BR/>";
			}
		    else
			{
			    echo "<BR/><BUTTON type=\"submit\">Delete Selected</BUTTON>";
			}
		?>
		<BUTTON type="button" onClick="javascript:addLocation();">Add Location</BUTTON>
		</TD>
	</TR>
	<TR><TD><BR></TD></TR>
	<TR><TD align="right"><BUTTON type="button" onClick="javascript:cancelLocationSelector();">Done</BUTTON>&nbsp;<br/></TD></TR>
	<TR><TD height="5"></TD></TR>
</TABLE>

</FORM>
		
<?php
if(isset($_SESSION['action_message']))
{
	echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
}

fmClearMessages();
?>