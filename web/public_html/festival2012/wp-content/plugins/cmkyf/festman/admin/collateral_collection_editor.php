<?php
require_once (dirname(__FILE__) . '/../objects/Collateral.php');
require_once (dirname(__FILE__) . '/../objects/CollateralLocation.php');
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Program.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');
require_once (dirname(__FILE__) . '/../objects/Event.php');
require_once (dirname(__FILE__) . '/../objects/RelatedPerson.php');
require_once (dirname(__FILE__) . '/../objects/Location.php');
require_once (dirname(__FILE__) . '/../objects/FileExtension.php');

require_once (dirname(__FILE__) . '/../library/config.php');
require_once (dirname(__FILE__) . '/../library/utils.php');
require_once (dirname(__FILE__) . '/admin_utils.php');
?>

<script type="text/javascript">

    fm_ignoreChanges = true;

    function fmOnSubmitCollateralSetForm()
    {
        var form = document.forms["collateral_collection_form"];
    
        form.elements["action"].value = "add_selected_collateral";
        form.submit();
    }

    function fmCheckExtension(extension)
    {
        var found = false;
        
<?php
$file_extensions = FileExtension::getAllFileExtensions();
echo "	var suffix_list = new Array(" . count($file_extensions) . ");\n\n";
for ($i = 0; $i < count($file_extensions); $i++)
{
    echo "	suffix_list[" . $i . "] = \"" . $file_extensions[$i]->getExtension() . "\";\n";
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

            function fmValidateFileName(filename)
            {
                if(filename.length > 0)
                {
                    var suffix = fmGetFileSuffix(filename);
                    if(suffix == null || fmCheckExtension(suffix) == false)
                    {
                        return "Invalid file: '" + filename + "'";
                    }
                }
	
                return "";
            }

            function fmValidateFileNames()
            {
                var message = "";
	
                var form = document.forms["collateral_collection_form"];
                for(var i = 0; i < form.elements["file[]"].length; i++)
                {
                    message = fmAppendLine(message, fmValidateFileName(form.elements["file[]"][i].value));
                }
	
                if(message.length > 0)
                {
                    alert(message);
                }	
            }

            function fmOnSubmitUploadForm()
            {
                var form = document.forms["collateral_collection_form"];
                fmValidateFileNames();
                form.elements["action"].value = "upload_collateral";
                //alert('submitting upload');
                form.submit();
            }

            function fmOnCancelCollateralSetEditor()
            {
                var form = document.forms["collateral_collection_form"];
    
                form.elements["action"].value = "cancel_collateral_collection_editor";
                form.submit();
            }

            function fmOnFilterCollateral()
            {
                var form = document.forms["collateral_collection_form"];
    
                form.elements["action"].value = "filter_collateral";
                form.submit();
            }

</script>

<?php
if (isset($_SESSION['current_collateral_collection_type']))
{
    $collateral_collection_type = $_SESSION['current_collateral_collection_type'];
}
else
{
    $collateral_collection_type = "program_item";
}

if (isset($_SESSION['current_collateral_location_name']))
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

switch ($collateral_collection_type)
{

    case "program_item":
        if (isset($_SESSION['current_program_item']))
        {
            $collateral_collection = $_SESSION['current_program_item'];
            $collateral_collection_name = ProgramItem::getObjectClassDisplayName($collateral_collection->getObjectClass());
            $collateral_collection_second_name = $collateral_collection->getName();
        }
        break;
    case "related_person":
        if (isset($_SESSION['current_related_person']))
        {
            $collateral_collection = $_SESSION['current_related_person'];
            $collateral_collection_name = "Related Person";
            $collateral_collection_second_name = $collateral_collection->getName();
        }
        break;
    case "location":
        if (isset($_SESSION['current_location']))
        {
            $collateral_collection = $_SESSION['current_location'];
            $collateral_collection_name = "Location";
            $collateral_collection_second_name = $collateral_collection->getName();
        }
        break;
    case "event":
        if (isset($_SESSION['event']))
        {
            $collateral_collection = $_SESSION['event'];
            $collateral_collection_name = "Event";
            $collateral_collection_second_name = $collateral_collection->getName();
        }
        break;
}

$selected_collateral_ids = array();

if (isset($collateral_collection))
{
    $selected_collateral = $collateral_collection->getAllCollateral();
    $selected_collateral_count = count($selected_collateral);

    for ($i = 0; $i < $selected_collateral_count; $i++)
    {
        $selected_collateral_ids[$selected_collateral[$i]->getId()] = true;
    }
}
?>

<form name="collateral_collection_form" id="collateral_collection_form" method="post" action="admin.php?page=fm-collateral-page" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add_selected_collateral" /> 
    <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
    <input type="hidden" name="collateral_location_name" value="<?php echo esc_attr($collateral_location_name); ?>" /> 
    <input type="hidden" name="collateral_collection_type" value="<?php echo esc_attr($collateral_collection_type); ?>" />
    <input type="hidden" name="MAX_FILE_SIZE" value="24000000" />
                                
    <div class="wrap">
        <div id="icon-themes" class="icon32">
            <br>
        </div>
        <h2>Collateral</h2>
        <div class="tablenav top">
            <div class="alignleft actions">

                <select name="collateral_location_name" OnChange="fmOnFilterCollateral()">
                    <option value="all"<?php echo ($collateral_location_name == "all" ? " selected=\"true\"" : "") ?>>all</option>
                    <?php
                    // use the association to do a reverse lookup.
                    $collateral_location_to_name_assoc = array();
                    $collateral_location_count = count($collateral_locations);
                    for ($i = 0; $i < $collateral_location_count; $i++)
                    {
                        $collateral_location_to_name_assoc[$collateral_locations[$i]->getLocation()] = $collateral_locations[$i]->getName();
                        echo "<OPTION value=\"" . esc_attr($collateral_locations[$i]->getName()) . "\"" . ($collateral_location_name == $collateral_locations[$i]->getName() ? " selected=\"true\"" : "") . ">" . esc_html($collateral_locations[$i]->getName()) . "</OPTION>\n";
                    }
                    ?>
                </select>
                <button type="button" onClick="javascript:fmOnFilterCollateral(); return false;">filter</button>
            </div>
            <?php
            if (isset($_SESSION['error_message']))
            {
                echo '<div class="error">' . esc_html($_SESSION['error_message']) . '</div>';
            }
            if (isset($_SESSION['action_message']))
            {
                echo '<div class="message">' . esc_html($_SESSION['action_message']) . '</div>';
            }
            ?>
            <br class="clear" />
        </div>
        
        <div id="poststuff" class="metabox-holder has-right-sidebar">


            <div id="side-info-column" class="inner-sidebar">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <div id="linksubmitdiv" class="postbox ">
                        <div class="handlediv" title="Click to toggle">
                            <br>
                        </div>
                        <h3 class="hndle">
                            <span><?php echo sprintf(__('Save %s'), esc_html($object_class_display_name)); ?></span>
                        </h3>
                        <div class="inside">
                            <div id="submitlink" class="submitbox">
                                <div id="major-publishing-actions">
                                    <div id="publishing-action">

                                        <button onclick="fmOnSubmitCollateralSetForm(); return false;" id="publish" class="button-primary" accesskey="p" tabindex="4" name="add">Add</button>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="post-body">
                <div id="post-body-content">

                    <div class="fm-scroll-table">
                        <table class="fm-table wp-list-table-o narrowfat fixed pages" callspacing="0">
                            <thead>
                                <tr>
                                    <th scope='col' id='cb' class='manage-column column-cb sc-check-column'  style=""><input type="checkbox" /></th>
                                    <th class="manage-column lc-column">Location Name</th>
                                    <th class="manage-column lc-column">Collateral Count</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="fm-scrollable">
                            <table class="fm-scrollable-table wp-list-table narrowfat fixed pages" callspacing="0">
                                <tbody class="fm-scrolled-tbody" id="the-list">

                                    <?php
                                        $collateral_selection = Collateral::getCollateralByCollateralLocation($collateral_location_name);
                                        $collateral_selection_count = count($collateral_selection);

                                        if ($collateral_selection_count > 0)
                                        {
                                            for ($i = 0; $i < $collateral_selection_count; $i++)
                                            {
                                                $collateral_selection_collateral = $collateral_selection[$i];
                                                $collateral_selection_collateral_name = $collateral_selection_collateral->getName();
                                                if (isset($collateral_location_to_name_assoc[$collateral_selection_collateral->getLocation()]) == true)
                                                {
                                                    $collateral_selection_collateral_location = $collateral_location_to_name_assoc[$collateral_selection_collateral->getLocation()];
                                                }
                                                else
                                                {
                                                    $collateral_selection_collateral_location = "external";
                                                }

                                                $collateral_selection_is_selected = false;
                                                if (isset($selected_collateral_ids[$collateral_selection_collateral->getId()]))
                                                {
                                                    $collateral_selection_is_selected = true;
                                                }

                                                ?>
                                                <tr<?php if($i % 2 == 0) echo " class=\"stripe\""; ?>>
                                                    <th scope="row" class="sc-check-column">
                                                        <input type="checkbox" name="object_collateral_ids[]" value="<?php echo esc_attr($collateral_selection_collateral->getId()); ?>" <?php if($collateral_selection_is_selected == true) echo " checked=\"true\" disabled=\"true\""; ?>/>
                                                    </th>
                                                    <td class="column-name">
                                                        <strong>
                                                            <a class="row-title" title="View Collateral" onclick="javascript:window.open('<?php echo plugins_url(esc_attr($collateral_selection_collateral->getUrl()), dirname(__FILE__)); ?>', 'collateral'); return false;"><?php echo esc_html($collateral_selection_collateral_name); ?></a>
                                                        </strong>
                                                        <br>
                                                        <div class="row-actions">
                                                            <span class="delete">
                                                                <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url("admin.php?page=fm-collateral-page&amp;action=delete_collateral&amp;collateral_id=" . esc_attr($collateral_id), 'delete-collateral_' . esc_attr($location_id)) ?>">Delete</a>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo esc_html($collateral_selection_collateral_location); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                        ?>
                                            <tr class="no-items">
                                                <td class="colspanchange" colspan="2">No Locations found.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="fm-table wp-list-table narrowfat fixed pages" callspacing="0">
                            <tfoot>
                                <tr>
                                    <th scope='col' id='cb' class='manage-column column-cb sc-check-column'  style=""><input type="checkbox" /></th>
                                    <th class="manage-column lc-column" style="" scope="col">Location Name</th>
                                    <th class="manage-column lc-column" style="" scope="col">Collateral Count</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <br/><br/>
                    
              
                   
                            
                    <div id="namediv" class="stuffbox">
                        <h3>
                            <label for="name">Add Collateral</label>
                        </h3>
                        <div class="inside">
                            <TABLE width="80%" align="center">
                                <TR>
                                    <TD colspan="3" align="center" class="label">Upload <?php echo esc_html($collateral_collection_name) ?> Collateral</TD>
                                </TR>
                                <TR>
                                    <TD class="label">Collateral 1:</TD>
                                    <TD colspan="2"><INPUT class="collateral-file" type="file" name="file[]" size="40" /></TD>
                                </TR>
                                <TR>
                                    <TD class="label">Collateral 2:</TD>
                                    <TD colspan="2"><INPUT class="collateral-file" type="file" name="file[]" size="40" /></TD>
                                </TR>
                                <TR>
                                    <TD class="label">Collateral 3:</TD>
                                    <TD colspan="2"><INPUT class="collateral-file" type="file" name="file[]" size="40" /></TD>
                                </TR>
                                <TR>
                                    <TD></TD>
                                    <TD align="left">Overwrite Existing Collateral<INPUT type="checkbox" name="overwrite_existing_collateral" /> </TD>
                                    <TD align="right"><BUTTON type="button" onClick="javascript:fmOnSubmitUploadForm();">Upload</BUTTON></TD>
                                </TR>
                            </TABLE>
                        </div>
                    </div>
                    
                    <div id="postdiv" class="postarea"></div>
                    <div id="normal-sortables" class="meta-box-sortables"></div>
                    <input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url(stripslashes(wp_get_referer())); ?>" />
                </div>
            </div>
        </div>
    </div>
</form>



<!--

<div class="wrap">
    <div id="icon-themes" class="icon32">
        <br>
    </div>
    <h2>Collateral</h2>


    <div class="tablenav top">
        <div class="alignleft actions">


            <select name="collateral_location_name" OnChange="fmOnFilterCollateral()">
                <option value="all"<?php echo ($collateral_location_name == "all" ? " selected=\"true\"" : "") ?>>all</option>
                <?php
                // use the association to do a reverse lookup.
                $collateral_location_to_name_assoc = array();
                $collateral_location_count = count($collateral_locations);
                for ($i = 0; $i < $collateral_location_count; $i++)
                {
                    $collateral_location_to_name_assoc[$collateral_locations[$i]->getLocation()] = $collateral_locations[$i]->getName();
                    echo "<OPTION value=\"" . esc_attr($collateral_locations[$i]->getName()) . "\"" . ($collateral_location_name == $collateral_locations[$i]->getName() ? " selected=\"true\"" : "") . ">" . esc_html($collateral_locations[$i]->getName()) . "</OPTION>\n";
                }
                ?>
            </select>
            <button type="button" onClick="javascript:fmOnFilterCollateral();">filter</button>


        </div>
<?php
if (isset($_SESSION['error_message']))
{
    echo '<div class="error">' . esc_html($_SESSION['error_message']) . '</div>';
}
if (isset($_SESSION['action_message']))
{
    echo '<div class="message">' . esc_html($_SESSION['action_message']) . '</div>';
}
?>
        <br class="clear" />
    </div>


    <div id="poststuff" class="metabox-holder has-right-sidebar">

        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div id="linksubmitdiv" class="postbox ">
                    <div class="handlediv" title="Click to toggle">
                        <br>
                    </div>
                    <h3 class="hndle">
                        <span><?php echo __('Save Collateral'); ?></span>
                    </h3>
                    <div class="inside">
                        <div id="submitlink" class="submitbox">
                            <div id="major-publishing-actions">
                                <div id="publishing-action">

                                    <button onclick="javascript:fmOnSubmitProgramItemForm();" id="publish" class="button-primary" accesskey="p" tabindex="4" name="save">Submit <?php echo esc_html($object_class_display_name); ?></button>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="post-body">
            <div id="post-body-content">

                <div id="namediv" class="stuffbox">
                    <h3>
                        <label for="name"><?php echo __('Select Collateral for') . ' ' . $collateral_collection_name . ': ' . $collateral_collection_second_name ?></label>
                    </h3>

                    <div class="inside">

                        <form name="collateral_collection_form" id="collateral_collection_form" method="POST" action="admin.php?page=fm-collateral-page" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_selected_collateral" /> 
                            <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
                            <input type="hidden" name="collateral_location_name" value="<?php echo esc_attr($collateral_location_name); ?>" /> 
                            <input type="hidden" name="collateral_collection_type" value="<?php echo esc_attr($collateral_collection_type); ?>" />
                            <table width="80%" align="center">
                                <tr><yd colspan="2" align="right">

                                    </td></tr>
                                    <tr>
                                        <td colspan="2">
                                            <table width="100%" class="border">
                                                <thead class="h2">
                                                    <tr class="border">
                                                        <td width="30">&nbsp;</td>
                                                        <td>Name</td>
                                                        <td width="100">Store</td>
                                                    </tr>
                                                </thead>
                                                <tr><td colspan="3">
                                                        <div class="collateral-list" style="height: 400px; width: 100%; overflow: auto;">
                                                            <table width="100%">
                                                                <?php
                                                                $collateral_selection = Collateral::getCollateralByCollateralLocation($collateral_location_name);
                                                                $collateral_selection_count = count($collateral_selection);

                                                                if ($collateral_selection_count > 0)
                                                                {
                                                                    for ($i = 0; $i < $collateral_selection_count; $i++)
                                                                    {
                                                                        $collateral_selection_collateral = $collateral_selection[$i];
                                                                        $collateral_selection_collateral_name = $collateral_selection_collateral->getName();
                                                                        if (isset($collateral_location_to_name_assoc[$collateral_selection_collateral->getLocation()]) == true)
                                                                        {
                                                                            $collateral_selection_collateral_location = $collateral_location_to_name_assoc[$collateral_selection_collateral->getLocation()];
                                                                        }
                                                                        else
                                                                        {
                                                                            $collateral_selection_collateral_location = "external";
                                                                        }

                                                                        $collateral_selection_is_selected = false;
                                                                        if (isset($selected_collateral_ids[$collateral_selection_collateral->getId()]))
                                                                        {
                                                                            $collateral_selection_is_selected = true;
                                                                        }

                                                                        if ($i % 2 == 0)
                                                                            echo "          <TR class=\"stripe\">\n";
                                                                        else
                                                                            echo "          <TR>\n";

                                                                        echo "            <TD width=\"30\"><input type=\"checkbox\" name=\"object_collateral_ids[]\" value=\"" . esc_attr($collateral_selection_collateral->getId()) . "\"" . ($collateral_selection_is_selected == true ? " checked=\"true\" disabled=\"true\"" : "") . " /></TD>\n";
                                                                        echo "            <TD><A href=\"#\" OnClick=\"javascript:window.open('" . plugins_url(esc_attr($collateral_selection_collateral->getUrl()), dirname(__FILE__)) . "', 'collateral'); return false;\">" . esc_html($collateral_selection_collateral_name) . "</A></TD>\n";
                                                                        echo "            <TD width=\"100\">" . esc_html($collateral_selection_collateral_location) . "</TD>\n";
                                                                        echo "          </TR>\n";
                                                                    }
                                                                }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </td></TR>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right"><br>
                                            <button type="button" onClick="javascript:fmOnCancelCollateralSetEditor();">Cancel</BUTTON>
                                            <button type="button" onClick="javascript:fmOnSubmitCollateralSetForm();">OK</BUTTON>
                                        </td>
                                    </tr>
                            </table>
                        </form>


                        <FORM NAME="upload_form" ID="upload_form" METHOD="POST" ACTION="admin.php?page=fm-collateral-page" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload_collateral" /> 
                            <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" />
                            <input type="hidden" name="collateral_collection_type" value="<?php echo esc_attr($collateral_collection_type) ?>" />
                            <input type="hidden" name="MAX_FILE_SIZE" value="24000000" />
                            <TABLE width="80%" align="center">
                                <TR>
                                    <TD colspan="3" align="center" class="label">Upload <?php echo esc_html($collateral_collection_name) ?> Collateral</TD>
                                </TR>
                                <TR>
                                    <TD class="label">Collateral 1:</TD>
                                    <TD colspan="2"><INPUT class="collateral-file" type="file" name="file[]" size="40" /></TD>
                                </TR>
                                <TR>
                                    <TD class="label">Collateral 2:</TD>
                                    <TD colspan="2"><INPUT class="collateral-file" type="file" name="file[]" size="40" /></TD>
                                </TR>
                                <TR>
                                    <TD class="label">Collateral 3:</TD>
                                    <TD colspan="2"><INPUT class="collateral-file" type="file" name="file[]" size="40" /></TD>
                                </TR>
                                <TR>
                                    <TD></TD>
                                    <TD align="left">Overwrite Existing Collateral<INPUT type="checkbox" name="overwrite_existing_collateral" /> </TD>
                                    <TD align="right"><BUTTON type="button" onClick="javascript:fmOnSubmitUploadForm();">Upload</BUTTON></TD>
                                </TR>
                            </TABLE>
                        </FORM>
                    </div>
                </div>
                <div id="postdiv" class="postarea"></div>
                <div id="normal-sortables" class="meta-box-sortables"></div>
                <input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url(stripslashes(wp_get_referer())); ?>" />
            </div>
        </div>
    </div>
</div>
-->
<?php
fmClearMessages();
?>