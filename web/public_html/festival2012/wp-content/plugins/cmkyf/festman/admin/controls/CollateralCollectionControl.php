<?php

require_once dirname(__FILE__).'/../../objects/IControl.php';

class CollateralCollectionControl implements IControl
{
	private $collateral_collection = NULL;
	private $form_name = NULL;
	private $collateral_collection_type = NULL;
	
	function __construct(AbstractCollateralCollection $collateral_collection, $form_name, $collateral_collection_type) {
		$this->collateral_collection = $collateral_collection;
		$this->form_name = $form_name;
		$this->collateral_collection_type = $collateral_collection_type;
	}
	
	public function render()
	{
echo <<<EOF
	<script type="text/javascript">

		function addCollateral(form_name)
		{
			var form = document.forms[form_name];
	
			form.action = "library/handler_collateral_collection.php";
			form.elements["action"].value = "add_collateral";
	
			form.submit();
		}
	
		function removeCollateral(form_name, collateral_id)
		{
			var form = document.forms[form_name];
	
			form.action = "library/handler_collateral_collection.php";
			form.elements["action"].value = "remove_collateral";
			form.elements["object_collateral_id"].value = collateral_id;
	
			form.submit();
		}
	
		function removeCollateralList(form_name)
		{
			var form = document.forms[form_name];
	
			form.action = "library/handler_collateral_collection.php";
			form.elements["action"].value = "remove_collateral_list";
	
			form.submit();
		}

	</script>
EOF;
	echo "    <input type=\"hidden\" name=\"collateral_collection_type\" value=\"".$this->collateral_collection_type."\" />";
	echo "    <input type=\"hidden\" name=\"object_collateral_id\" value=\"\" />";
	
echo <<< EOF

	<TABLE width="100%"><TR>
		
		<TD width="100%" align="center">


			<TABLE width="100%" class="border">
				<thead class="h2">
					<tr class="border">
						<td>&nbsp;</td>
						<td>Collateral</td>
						<td>Order</td>
						<td>Default</td>
						<td>&nbsp;</td>
					</tr>
				</thead>

EOF;
				$cc_object_collateral_list = &$this->collateral_collection->getAllObject_Collateral();
				$this->collateral_collection->sortObject_Collateral();
				$cc_object_collateral_count = count($cc_object_collateral_list);

				if($cc_object_collateral_count > 0)
				{
					for($i = 0; $i < $cc_object_collateral_count; $i++)
					{
						$cc_object_collateral = $cc_object_collateral_list[$i];
						$cc_collateral = $cc_object_collateral->getCollateral();
						$cc_collateral_id = $cc_collateral->getId();
						$cc_collateral_name = $cc_collateral->getName();
						$cc_object_collateral_sort_order = $cc_object_collateral->getSortOrder();
						$cc_object_collateral_is_default = $cc_object_collateral->getIsDefault();

						$action_id = uniqid("remove");

						echo "<tr class=\"border\">".
						"  <td><input type=\"hidden\" name=\"object_collateral_ids[]\" value=\"".$cc_collateral_id."\">\n".
						"    <input type=\"checkbox\" name=\"object_collateral_checked_ids[]\" value=\"".$cc_collateral_id."\"></td>\n".
						"  <td><a href=\"javascript:void(0)\" OnClick=\"javascript:window.open('../" . $cc_collateral->getUrl() . "', 'collateral')\">".$cc_collateral_name."</td>\n".
						"  <td><input type=\"text\" name=\"object_collateral_sort_order[]\" size=\"3\" value=\"".$cc_object_collateral_sort_order."\"></td>\n".
						"  <td><input type=\"radio\" name=\"object_collateral_default\" value=\"".$cc_collateral_id."\" ". ($cc_object_collateral_is_default == true ? "checked=\"true\"" : "") ."></td>\n".
						"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:removeCollateral('".$this->form_name."', '".$cc_collateral_id."');\">remove</a></td>\n".
						"</tr>\n";
					}
				}
				
			echo "</TABLE>\n";
			
			if($cc_object_collateral_count == 0)
			{
				echo "<BR/>\nNo Collateral Found!<BR/><BR/>";
			}
			else
			{
				echo "<BR/><BUTTON type=\"button\" onClick=\"javascript:removeCollateralList('".$this->form_name."');\">Remove Selected Collateral</BUTTON>";
			}

echo <<< EOF
			<BUTTON type="button" onClick="javascript:addCollateral('$this->form_name');">Add Collateral</BUTTON>
			</TD>
		</TR>
	</TABLE>

EOF;
	}
}
