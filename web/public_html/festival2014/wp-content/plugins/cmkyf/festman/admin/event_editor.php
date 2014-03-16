<?php
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Event.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');
require_once (dirname(__FILE__) . '/../objects/Collateral.php');
require_once (dirname(__FILE__) . '/../objects/Location.php');
require_once (dirname(__FILE__) . '/../objects/Program.php');
require_once (dirname(__FILE__) . '/../objects/Program_ProgramItem.php');

require_once (dirname(__FILE__) . '/../library/config.php');
require_once (dirname(__FILE__) . '/../library/utils.php');
require_once (dirname(__FILE__) . '/admin_utils.php');
?>

<script type="text/javascript">

    var locationValidator = new fmValidator();
    locationValidator.addValidator(new fmRequiredValidator("Please select a Location."));
    
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

    function fmOnSubmitEventForm()
    {
        fm_ignoreChanges = true;
        
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

    function fmAddCollateral()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["event_form"];
        form.action = "admin.php?page=fm-collateral-page";
        form.elements["action"].value = "add_collateral";
        form.submit();
    }
    
    function fmRemoveProgramItem(ppi_id)
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["event_form"];
        form.elements["action"].value = "remove_program_item";
        form.elements["program_program_item_id"].value = ppi_id;
        return confirm( 'You are about to delete the selected Events.\n \'Cancel\' to stop, \'OK\' to delete.' );
    }

    function fmHandleProgramItemsBulkAction()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["event_form"];
    
        var elements = form.elements;
        var action = form.elements['action2'].value;
        if(action == 'remove_program_items')
        {
            form.elements["action"].value = "remove_program_items";
            return confirm( 'You are about to remove the selected Program Items.\n \'Cancel\' to stop, \'OK\' to delete.' );
        }
    
        return false;
    }

    function fmRemoveCollateral(collateral_id)
    {
        fm_ignoreChanges = true;
        
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["event_form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral";
            form.elements["object_collateral_id"].value = collateral_id;

            form.submit();
        }
    }

    function fmRemoveCollateralList()
    {
        fm_ignoreChanges = true;
        
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["event_form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral_list";

            form.submit();
        }
    }
    
    // Handle the ObjectType (ProgramItem Type) option changes.
    // we use php to predefine some javascript functions to help us set the
    // ObjectType Instance option mennus to the available options from the db.
    // Updated the UI in real time, saving the round trip.
    function fmHandleItemTypeOptionChange(index)
    {
        var form = document.forms["event_form"];
        var object_class;
        var object_instance_parent;
    
        object_class = form.elements["object_classes[]"][index].value;
        object_instance_parent = document.getElementById("object_instance_" + index);
    
        //alert("Type: object_class: " + object_class + ", " + object_instance_parent.nodeName);
    
        var option_html = '';
    
        //alert("OptionClass: " + object_class);
        if(object_class == "Act")
        {
            option_html = fmGetActOptions();
        }
        else if(object_class == "Workshop")
        {
            option_html = fmGetWorkshopOptions();
        }
        else if(object_class == "Panel")
        {
            option_html = fmGetPanelOptions();
        }
        else if(object_class == "Film")
        {
            option_html = fmGetFilmOptions();
        }
        else if(object_class == "Klass")
        {
            option_html = fmGetKlassOptions();
        }
        else if(object_class == "Film")
        {
            option_html = fmGetFilmOptions();
        }
        else if(object_class == "Installation")
        {
            option_html = fmGetInstallationOptions();
        }
    
        if(option_html == '')
        {
            option_html = "<input type=\"hidden\" name=\"instance_ids[]\" value=\"\" />Select a Type";
        }
        else
        {
            option_html = "<select name=\"instance_ids[]\">" + option_html + "</select>";
        }
        object_instance_parent.innerHTML = option_html;
    }

    function fmGetActOptions()
    {
        <?php echo "    return '" . str_replace("\n", "", ProgramItem::getProgramItemSelectOptions("Act", "")) . "';"; ?>  
    }

    function fmGetWorkshopOptions()
    {
        <?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Workshop", "") . "';"; ?>  
    }

    function fmGetPanelOptions()
    {
        <?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Panel", "") . "';"; ?>  
    }

    function fmGetFilmOptions()
    {
        <?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Film", "") . "';"; ?>  
    }

    function fmGetKlassOptions()
    {
        <?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Klass", "") . "';"; ?>  
    }

    function fmGetFilmOptions()
    {
        <?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Film", "") . "';"; ?>  
    }

    function fmGetInstallationOptions()
    {
        <?php echo "    return '" . ProgramItem::getProgramItemSelectOptions("Installation", "") . "';"; ?>  
    }
</script>

<?php
if (isset($_SESSION['current_event']))
{
    $event = $_SESSION['current_event'];
    $fm_is_new = false;
}
else
{
    $event = new Event();
    $fm_is_new = true;
}

$location_select_options = Location::getLocationSelectOptions($event->getLocation_Id());

// Creates a table row for a Program_ProgramItem.
// ppi_id[], positions[], start_times[], object_classes[], instance_ids[].
function fmGenerateProgram_ProgramItemTableRow($num, $ppi)
{
    if ($ppi == NULL)
    {
        $ppi = new Program_ProgramItem();
    }

    $program_item = $ppi->getProgramItem();
    $program_item_type_name = '';

    if ($program_item != NULL)
    {
        $program_item_type_name = $program_item->getObjectClass();
    }

    echo '<tr>' . "\n";

    if ($ppi->getId() != NULL)
    {
        echo '<th scope="row" class="check-column"><input type="checkbox" name="program_program_item_ids[]" value="' . esc_attr($ppi->getId()) . '"/></th>';
    }
    else
    {
        echo '<th scope="row" class="check-column">&nbsp;</th>';
    }
    echo '<td><input type="hidden" name="ppi_ids[]" value="' . esc_attr($ppi->getId()) . '" /><input type="text" name="positions[]" size="3" value="' . esc_attr($ppi->getPosition()) . '" /></td>' . "\n";
    echo '<td><input type="text" name="start_times[]" size="20" value="' . esc_attr($ppi->getStartTimeString()) . '" /></td>' . "\n";
    echo '<td><select name="object_classes[]" onChange="fmHandleItemTypeOptionChange(' . $num . ')">' . "\n";
    if ($program_item_type_name == '')
        echo '<option value="" selected="true">&lt;Select One&gt;</option>' . "\n";
    echo '<option value="Act"' . ($program_item_type_name == "Act" ? ' selected="true"' : '') . '>Act</option>' . "\n";
    echo '<option value="Workshop"' . ($program_item_type_name == "Workshop" ? ' selected="true"' : '') . '>Workshop</option>' . "\n";
    echo '<option value="Installation"' . ($program_item_type_name == "Installation" ? ' selected="true"' : '') . '>Installation</option>' . "\n";
    echo '<option value="Panel"' . ($program_item_type_name == "Panel" ? ' selected="true"' : '') . '>Panel</option>' . "\n";
    echo '<option value="Film"' . ($program_item_type_name == "Film" ? ' selected="true"' : '') . '>Film</option>' . "\n";
    echo '<option value="Klass"' . ($program_item_type_name == "Klass" ? ' selected="true"' : '') . '>Class</option>' . "\n";
    echo '</select></td>' . "\n";
    echo '<td class="label_sm" id="object_instance_' . $num . '">' . "\n";
    if ($program_item != NULL)
    {
        echo "<select name=\"instance_ids[]\">\n" .
        $program_item->getTypedSelectOptions() .
        "</select>\n";
    }
    else
    {
        echo "<input type=\"hidden\" name=\"instance_ids[]\" value=\"\" />Please select a Type.";
    }
    echo '</td>' . "\n";
    echo '<td>' . "\n";
    if ($ppi->getId() != NULL)
    {
        echo '    <button onclick="return fmRemoveProgramItem(' . $ppi->getId() . ');">remove</button>' . "\n";
        //echo '    <INPUT type="checkbox" name="checked_ppi_ids_'.$num.'" value="' . $ppi->getId() . '" /><FONT size="-3">delete</FONT>' . "\n";
    }
    echo '</td>' . "\n";
    echo '</tr>' . "\n";




    /*
     * 
      <tr>
      <td class="column-name">
      <input type="hidden" name="object_collateral_ids[]" value="<?php echo esc_attr($cc_collateral_id); ?>">
      <strong>
      <a class="row-title" title="Edit �Collateral�" href="admin.php?page=fm-collateral-page&action=edit_collateral&collateral_id=<?php echo esc_attr($cc_collateral_id); ?>"><?php echo esc_html($cc_collateral_name);?></a>
      </strong>
      <br>
      <div class="row-actions">
      <span class="edit">
      <a href="admin.php?page=fm-collateral-page&action=edit_collateral&collateral_id=<?php echo esc_attr($cc_collateral_id); ?>">Edit</a> |
      </span>
      <span class="remove">
      <a class="submitdelete" onclick="if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( "admin.php?page=fm-collateral-page&amp;action=remove_collateral&amp;object_collateral_id=" . esc_attr($cc_collateral_id), 'remove-collateral_' . esc_attr($cc_collateral_id) )  ?>">Remove</a>
      </span>
      </div>
      </td>
      <td><input type="text" name="object_collateral_sort_order[]" size="3" value="<?php echo $cc_object_collateral_sort_order; ?>"></td>
      <td><input type="radio" name="object_collateral_default" value="<?php echo $cc_collateral_id; ?>" <?php echo ($cc_object_collateral_is_default == true ? "checked=\"true\"" : ""); ?>"></td>
      </tr>

     */
}
$fm_page = 'fm-event-page';

if (!$fm_is_new)
{
    $heading = sprintf(__('<a href="%s">Event</a> / Edit Event'), 'admin.php?page=' . $fm_page);
    $submit_text = __('Update Event');
    $form = '<form name="event_form" id="event_form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'update-event_' . $event->getId();
}
else
{
    $heading = sprintf(__('<a href="%s">Event</a> / Add New Event'), 'admin.php?page=' . $fm_page);
    $submit_text = __('Add Event');
    $form = '<form name="event_form" id="event_form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'add-event';
}

require_once(ABSPATH . 'wp-admin/includes/meta-boxes.php');

add_screen_option('layout_columns', array('max' => 2));

$user_ID = isset($user_ID) ? (int) $user_ID : 0;

if (isset($_SESSION['error_message']))
{
    echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">" . $_SESSION['error_message'] . "</TD></TR></TABLE><BR/>";
}
if (isset($_SESSION['action_message']))
{
    echo "<TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>" . $_SESSION['action_message'] . "</TD></TR></TABLE>";
}

if (!empty($form))
    echo $form;
if (!empty($link_added))
    echo $link_added;
echo "\n";
wp_nonce_field(esc_attr($nonce_action));
echo "\n";
?>

<div class="wrap">
    <div id="icon-themes" class="icon32">
        <br>
    </div>
    <h2><?php echo $heading; ?> <a href="admin.php?page=<?php echo $fm_page; ?>&action=create_event" class="add-new-h2"><?php echo esc_html_x('Add New', 'event'); ?></a></h2>
    <div id="poststuff" class="metabox-holder has-right-sidebar">

        <input type="hidden" name="action" value="save_event" /> 
        <input type="hidden" name="program_program_item_id" value="-1" />
        <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" />
        <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>" /> 
        <input type="hidden" name="collateral_collection_type" value="event" />
        <input type="hidden" name="object_collateral_id" value="" /> 
        <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" /> 

        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div id="linksubmitdiv" class="postbox ">
                    <div class="handlediv" title="Click to toggle">
                        <br>
                    </div>
                    <h3 class="hndle">
                        <span><?php echo __('Save Event'); ?></span>
                    </h3>
                    <div class="inside">
                        <div id="submitlink" class="submitbox">
                            <div id="major-publishing-actions">
                                <?php if (!$fm_is_new)
                                { ?>
                                    <div id="delete-action">
                                        <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this Event \'<?php echo esc_attr($event->getName()) ?>\'\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url("admin.php?page=$fm_page&amp;action=delete_event&amp;event_id=" . esc_attr($event->getId()), 'delete-event_' . esc_attr($event->getId())) ?>">Delete</a>
                                    </div>
<?php } ?>
                                <div id="publishing-action">
                                    <button onclick="javascript:fmOnSubmitEventForm();" id="publish" class="button-primary" accesskey="p" tabindex="4" name="save"><?php echo $submit_text; ?></button>
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
                        <label for="name"><?php _e('Event') ?></label>
                    </h3>
                    <div class="inside">
                        <table class="form-table edit-concert concert-form-table">
                            <tr valign="top">
                                <td class="first">Name:</td>
                                <td><input name="event_name" type="text" size="50" value="<?php echo esc_attr($event->getName()); ?>" alt="name" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Location:</td>
                                <td><select name="event_location_id"><?php echo $location_select_options; ?></select></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Start Time:</td>
                                <td><input name="event_start_time" type="text" size="30" value="<?php echo esc_attr($event->getStartTimeString()) ?>" alt="url text" /><span class="label_md">(mm/dd/yyyy hh:mm)</span></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">End Time:</td>
                                <td><input name="event_end_time" type="text" size="30" value="<?php echo esc_attr($event->getEndTimeString()) ?>" alt="map url" /><span class="label_md">(mm/dd/yyyy hh:mm)</span></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>

                <div id="postdivrich">
                    <h3><label for="content">Event Description</label></h3>
                    <?php wp_editor( $event->getDescription(), "event_description", array( 'media_buttons' => false ) ); ?>
                </div>
                
                <div id="relateddiv" class="">
                    <h2>Program<a class="add-new-h2" href="admin.php?page=fm-collateral-page&action=add_collateral&collateral_collection_type=related_person">Add New</a></h2>

                    <div class="tablenav top">
                        <div class="alignleft actions">
                            <select name='action2'>
                                <option value='-1' selected='selected'>Bulk Actions</option>
                                <option value='remove_program_items'>Delete</option>
                            </select>
                            <input onclick="return fmHandleProgramItemsBulkAction();" type="submit" name="" id="doaction2" class="button-secondary action" value="Apply"  />
                        </div>
                        <br class="clear" />
                    </div>

                    <table class="wp-list-table widefat pages" callspacing="0">
                        <thead>
                            <tr>
                                <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
                                <th class="manage-column fm-post-column lc-column">Pos</th>
                                <th class="manage-column fm-start-time-column lc-column">Start Time</th>
                                <th class="manage-column fm-program-item-type-column">Type</th>
                                <th class="manage-column fm-program-item-column">Act/Film/Class/etc</th>
                                <th class="manage-column fm-delete-column"></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
                                <th class="manage-column lc-column" style="" scope="col">Pos</th>
                                <th class="manage-column lc-column" style="" scope="col">Start Time</th>
                                <th class="manage-column lc-column" style="" scope="col">Type</th>
                                <th class="manage-column lc-column" style="" scope="col">Act/Film/Class/etc</th>
                                <th class="manage-column lc-column" style="" scope="col"></th>
                            </tr>
                        </tfoot>
                        <tbody id="the-list">

                            <?php
                            $program = $event->getProgram();
                            $count = 0;
                            $num_old_rows = 0;

                            if ($program != NULL)
                            {
                                $ppis = &$program->getProgram_ProgramItems();

                                while ($count < count($ppis))
                                {
                                    if ($ppis[$count]->getId() != null)
                                        $num_old_rows++;

                                    fmGenerateProgram_ProgramItemTableRow($count, $ppis[$count]);
                                    $count++;
                                }
                            }

                            //
                            // We want to leave at lease 2 un-saved rows to edit.
                            // 4 if there are no rows at all.
                            //
                      if ($count == 0)
                                $num_extra_rows = 4;
                            else
                                $num_extra_rows = 2;

                            $max = $num_extra_rows - $count + $num_old_rows;

                            for ($i = 0; $i < $max; $i++)
                            {
                                fmGenerateProgram_ProgramItemTableRow($count++, NULL);
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

                <div class="collateraldiv">
                    <h3 class="hndle"><span>Collateral <a class="add-new-h2" onclick="fmAddCollateral(); return false;" href="#">Add</a></span></h3>
                    <table class="wp-list-table widefat fixed pages" callspacing="0">
                        <thead>
                            <tr>
                                <th class="manage-column lc-column">Name</th>
                                <th class="manage-column lc-column">Sort Order</th>
                                <th class="manage-column lc-column">Default</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="manage-column lc-column" style="" scope="col">Name</th>
                                <th class="manage-column lc-column" style="" scope="col">Sort Order</th>
                                <th class="manage-column lc-column" style="" scope="col">Default</th>
                            </tr>
                        </tfoot>
                        <tbody id="the-list">

                            <?php
                            $cc_object_collateral_list = &$event->getAllObject_Collateral();
                            $event->sortObject_Collateral();
                            $cc_object_collateral_count = count($cc_object_collateral_list);
                            if ($cc_object_collateral_count > 0)
                            {
                                for ($i = 0; $i < $cc_object_collateral_count; $i++)
                                {
                                    $cc_object_collateral = $cc_object_collateral_list[$i];
                                    $cc_collateral = $cc_object_collateral->getCollateral();
                                    $cc_collateral_id = $cc_collateral->getId();
                                    $cc_collateral_name = $cc_collateral->getName();
                                    $cc_object_collateral_sort_order = $cc_object_collateral->getSortOrder();
                                    $cc_object_collateral_is_default = $cc_object_collateral->getIsDefault();

                                    $action_id = uniqid("remove");

                                    //echo "<tr class=\"border\">".
                                    //"  <td><input type=\"hidden\" name=\"object_collateral_ids[]\" value=\"".$cc_collateral_id."\">\n".
                                    //"    <input type=\"checkbox\" name=\"object_collateral_checked_ids[]\" value=\"".$cc_collateral_id."\"></td>\n".
                                    //"  <td><a href=\"javascript:void(0)\" OnClick=\"javascript:window.open('../" . $cc_collateral->getUrl() . "', 'collateral')\">".$cc_collateral_name."</td>\n".
                                    //"  <td><input type=\"text\" name=\"object_collateral_sort_order[]\" size=\"3\" value=\"".$cc_object_collateral_sort_order."\"></td>\n".
                                    //"  <td><input type=\"radio\" name=\"object_collateral_default\" value=\"".$cc_collateral_id."\" ". ($cc_object_collateral_is_default == true ? "checked=\"true\"" : "") ."></td>\n".
                                    //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:removeCollateral('".$this->form_name."', '".$cc_collateral_id."');\">remove</a></td>\n".
                                    //"</tr>\n";
                                    ?>

                                    <tr>
                                        <td class="column-name">
                                            <input type="hidden" name="object_collateral_ids[]" value="<?php echo esc_attr($cc_collateral_id); ?>">
                                            <strong>
                                                <a class="row-title" title="Edit �Collateral�" href="admin.php?page=fm-collateral-page&action=edit_collateral&collateral_id=<?php echo esc_attr($cc_collateral_id) ?>"><?php echo esc_html($cc_collateral_name); ?></a>
                                            </strong>
                                            <br>
                                            <div class="row-actions">
                                                <span class="remove">
                                                    <a class="submitdelete" onclick="fmRemoveCollateral('<?php echo esc_attr($cc_collateral_id); ?>'); return false;" href="#">Remove</a>
                                                </span>
                                            </div>
                                        </td>
                                        <td><input type="text" name="object_collateral_sort_order[]" size="3" value="<?php echo esc_attr($cc_object_collateral_sort_order); ?>"></td>
                                        <td><input type="radio" name="object_collateral_default" value="<?php echo esc_attr($cc_collateral_id); ?>" <?php echo ($cc_object_collateral_is_default == true ? "checked=\"true\"" : ""); ?>"></td>
                                    </tr>


    <?php } // endforeach  ?>
                            <?php }
                            else
                            { // endif  ?>
                                <tr class="no-items">
                                    <td class="colspanchange" colspan="2">No Collateral found.</td>
                                </tr>
                            <?php } ?>


                            <?php
                            //echo "<tr class=\"border\">".
                            //"  <td><input type=\"checkbox\" name=\"program_item_ids[]\" value=\"".$program_item_id."\"</td>\n".
                            //"  <td>".$program_item_name."&nbsp;(".$collateral_count.")</td>\n".
                            //"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editProgramItem('".$program_item_id."');\">edit</a></td>\n".
                            //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteProgramItem('".$program_item_id."');\">delete</a></td>\n".
                            //"</tr>\n";
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="postdiv" class="postarea"></div>
                <div id="normal-sortables" class="meta-box-sortables"></div>
                <input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url(stripslashes(wp_get_referer())); ?>" />
            </div>
        </div>
    </div>
</div>
</form>

<?php
fmClearMessages();
?>

