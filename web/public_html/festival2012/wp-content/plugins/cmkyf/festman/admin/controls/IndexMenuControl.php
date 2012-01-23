<?php

require_once dirname(__FILE__).'/../../objects/IControl.php';

class IndexMenuControl implements IControl
{
	private $collateral_collection = NULL;
	
	function __construct() {

	}
	
	public function render()
	{
		$unique_id = uniqid("noop");

echo <<<EOF
		<script type="text/javascript">
				
			function onManageProgramItems(form_name, klass)
			{
			    var form = document.forms[form_name];
			    
			    form.action = "library/handler_program_item.php";
			    form.elements["action"].value = "manage_program_items";
			    form.elements["program_item_class"].value = klass;
			    form.submit();
			}

			function onManageLocations(form_name)
			{
			    var form = document.forms[form_name];
				form.action = "library/handler_location.php";
			    form.elements["action"].value = "manage_locations";
			    form.submit();
			}

			function onManageEvents(form_name)
			{
			    var form = document.forms[form_name];
				form.action = "library/handler_event.php";
			    form.elements["action"].value = "manage_events";
			    form.submit();
			}
				
		</script>

		<TABLE ALIGN="left" width="100%">
			<THEAD class="h2">
				<TR CLASS="border">
					<TD CLASS="border">Options</TD>
				</TR>
			</THEAD>
			<TR>
				<TD>
		
				<FORM NAME="index_form" ID="index_form" METHOD="POST" ACTION="library/handler_general.php">
				<input type="hidden" name="action" value="noop" /> 
				<input type="hidden" name="action_id" value="$unique_id" />
				<input type="hidden" name="forward" value="index.php" /> 
				<input type="hidden" name="program_item_class" value="" />
		
				<TABLE>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageProgramItems('index_form', 'Act');">Manage
						Acts</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageProgramItems('index_form', 'Klass');">Manage
						Classes</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageProgramItems('index_form', 'Film');">Manage
						Films</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageProgramItems('index_form', 'Panel');">Manage
						Panels</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageProgramItems('index_form', 'Workshop');">Manage
						Workshops</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageProgramItems('index_form', 'Installation');">Manage
						Installations</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageLocations('index_form');">Manage
						Locations</a></TD>
					</TR>
					<TR>
						<TD><a class="medium" href="javascript:void(0);"
							onClick="javascript:onManageEvents('index_form');">Manage
						Events</a></TD>
					</TR>
				</TABLE>
				</FORM>
				</TD>
			</TR>
		</TABLE>
EOF;
	}
}