<?php
require_once (dirname(__FILE__) . '/../objects/ProgramItem.php');
require_once (dirname(__FILE__) . '/../objects/RelatedPerson.php');
require_once (dirname(__FILE__) . '/../objects/Collateral.php');
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');

require_once (dirname(__FILE__) . '/../library/config.php');
require_once (dirname(__FILE__) . '/../library/utils.php');
require_once (dirname(__FILE__) . '/admin_utils.php');

if (isset($_SESSION['current_location']))
{
    $location = $_SESSION['current_location'];
    $fm_is_new = false;
}
else
{
    $location = new Location();
    $fm_is_new = true;
}
?>

<script type="text/javascript">

    <?php if($location->isDirty()) { ?>
        fm_hasChanged = true;
    <?php }  ?>
        
    var nameValidator = new fmValidator();
    nameValidator.addValidator(new fmRequiredValidator("Please enter a Name."));
    nameValidator.addValidator(new fmMaxLengthValidator(256, "Name must have a length less that or equal to 256."));
    
    var urlValidator = new fmValidator();
    urlValidator.addValidator(new fmRequiredValidator("Please enter a Url."));
    urlValidator.addValidator(new fmMaxLengthValidator(256, "Url must have a length less that or equal to 256."));
    
    var urlTextValidator = new fmValidator();
    urlTextValidator.addValidator(new fmMaxLengthValidator(128, "Url Text must have a text length less that or equal to 128."));
    
    var mapUrlValidator = new fmValidator();
    mapUrlValidator.addValidator(new fmMaxLengthValidator(1024, "Map Url must have a length less that or equal to 1024."));
    
    var mapUrlTextValidator = new fmValidator();
    mapUrlTextValidator.addValidator(new fmMaxLengthValidator(128, "Map Url Text must have a text length less that or equal to 128."));
    
    var addressValidator = new fmValidator();
    addressValidator.addValidator(new fmRequiredValidator("Please enter an Address."));
    addressValidator.addValidator(new fmMaxLengthValidator(64, "Address must have a length less that or equal to 64."));
    
    var cityValidator = new fmValidator();
    cityValidator.addValidator(new fmRequiredValidator("Please enter a City."));
    cityValidator.addValidator(new fmMaxLengthValidator(32, "City must have a length less that or equal to 32."));
    
    var stateValidator = new fmValidator();
    stateValidator.addValidator(new fmRequiredValidator("Please enter a State."));
    stateValidator.addValidator(new fmMaxLengthValidator(32, "State must have a length less that or equal to 32."));
    
    var zipcodeValidator = new fmValidator();
    zipcodeValidator.addValidator(new fmRequiredValidator("Please enter a Zip Code."));
    zipcodeValidator.addValidator(new fmMaxLengthValidator(16, "Zip Code must have a length less that or equal to 16."));
    
    var descriptionValidator = new fmValidator();
    descriptionValidator.addValidator(new fmRequiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new fmMaxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
    function fmOnSubmitLocationForm()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["location-form"];
    
        var name = form.elements["location_name"].value;
        var url = form.elements["location_url"].value;
        var url_text = form.elements["location_url_text"].value;
        var map_url = form.elements["location_map_url"].value;
        var map_url_text = form.elements["location_map_url_text"].value;
        var address = form.elements["location_address"].value;
        var city = form.elements["location_city"].value;
        var state = form.elements["location_state"].value;
        var zipcode = form.elements["location_zipcode"].value;
        var editor = tinyMCE.get("location_description");
        var description = editor.getContent();

        var errorString = nameValidator.validate(name);
        errorString = fmAppendLine(errorString, urlValidator.validate(url));
        errorString = fmAppendLine(errorString, urlTextValidator.validate(url_text));
        errorString = fmAppendLine(errorString, mapUrlValidator.validate(map_url));
        errorString = fmAppendLine(errorString, mapUrlTextValidator.validate(map_url_text));
        errorString = fmAppendLine(errorString, addressValidator.validate(address));
        errorString = fmAppendLine(errorString, cityValidator.validate(city));
        errorString = fmAppendLine(errorString, stateValidator.validate(state));
        errorString = fmAppendLine(errorString, zipcodeValidator.validate(zipcode));
        errorString = fmAppendLine(errorString, descriptionValidator.validate(description));
    
        if(errorString.length > 0)
        {
            alert(errorString);
        }
        else
        {
            form.submit();
        }
    }

    function fmOnCancelLocationEditor()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["location-form"];
    
        form.elements["action"].value = "cancel_location_editor";
        form.submit();
    }

    function fmAddCollateral()
    {
        fm_ignoreChanges = true;
        
        var form = document.forms["location-form"];
        form.action = "admin.php?page=fm-collateral-page";
        form.elements["action"].value = "add_collateral";
        form.submit();
    }

    function fmRemoveCollateral(collateral_id)
    {
        fm_ignoreChanges = true;
        
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["location-form"];

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
            var form = document.forms["location-form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral_list";

            form.submit();
        }
    }
    
</script>

<?php

$fm_page = 'fm-location-page';

if (!$fm_is_new)
{
    $heading = sprintf(__('<a href="%s">Location</a> / Edit Location'), 'admin.php?page=' . $fm_page);
    $submit_text = __('Update Location');
    $form = '<form name="location-form" id="location-form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'update-location_' . $location->getId();
}
else
{
    $heading = sprintf(__('<a href="%s">Location</a> / Add New Location'), 'admin.php?page=' . $fm_page);
    $submit_text = __('Add Location');
    $form = '<form name="location-form" id="location-form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'add-location';
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

echo "\n";
wp_nonce_field(esc_attr($nonce_action));
echo "\n";
?>

<div class="wrap">
    <div id="icon-themes" class="icon32">
        <br>
    </div>
    <h2><?php echo $heading; ?> <a href="admin.php?page=<?php echo $fm_page; ?>&action=create_location" class="add-new-h2"><?php echo esc_html_x('Add New', 'location'); ?></a></h2>
    <div id="poststuff" class="metabox-holder has-right-sidebar">

        <input type="hidden" name="action" value="save_location"/>
        <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>"/>
        <input type="hidden" name="location_id" value="<?php echo esc_attr($location->getId()); ?>"/>
        <input type="hidden" name="collateral_collection_type" value="location" />
        <input type="hidden" name="object_collateral_id" value="" />
        <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" /> 

        <div id="side-info-column" class="inner-sidebar">
            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <div id="linksubmitdiv" class="postbox ">
                    <div class="handlediv" title="Click to toggle">
                        <br>
                    </div>
                    <h3 class="hndle">
                        <span><?php echo __('Save Location'); ?></span>
                    </h3>
                    <div class="inside">
                        <div id="submitlink" class="submitbox">
                            <div id="major-publishing-actions">
                                <?php if (!$fm_is_new) { ?>
                                    <div id="delete-action">
                                        <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this Location \'<?php echo esc_attr($location->getName()) ?>\'\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url("admin.php?page=$fm_page&amp;action=delete_location&amp;location_id=" . esc_attr($location->getId()), 'delete-location_' . esc_attr($location->getId())) ?>">Delete</a>
                                    </div>
                                <?php } ?>
                                <div id="publishing-action">
                                    <button onclick="javascript:fmOnSubmitLocationForm();" id="publish" class="button-primary" accesskey="p" tabindex="4" name="save"><?php echo $submit_text; ?></button>
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
                        <label for="name"><?php _e('Location') ?></label>
                    </h3>
                    <div class="inside">
                        <table class="form-table edit-concert concert-form-table">
                            <tr valign="top">
                                <td class="first">Name:</td>
                                <td><input name="location_name" type="text" size="50" value="<?php echo esc_attr($location->getName()); ?>" alt="name" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Url:</td>
                                <td><input name="location_url" type="text" size="50" value="<?php echo esc_attr($location->getUrl()) ?>" alt="url" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Url Text:</td>
                                <td><input name="location_url_text" type="text" size="50" value="<?php echo esc_attr($location->getUrlText()) ?>" alt="url text" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Map Url:</td>
                                <td><input name="location_map_url" type="text" size="50" value="<?php echo esc_attr($location->getMapUrl()) ?>" alt="map url" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Map Url Text:</td>
                                <td><input name="location_map_url_text" type="text" size="50" value="<?php echo esc_attr($location->getMapUrlText()) ?>" alt="map url text" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Address:</td>
                                <td><input name="location_address" type="text" size="50" value="<?php echo esc_attr($location->getAddress()) ?>" alt="address" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">City:</td>
                                <td><input name="location_city" type="text" size="50" value="<?php echo esc_attr($location->getCity()) ?>" alt="city" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">State:</td>
                                <td><input name="location_state" type="text" size="50" value="<?php echo esc_attr($location->getState()) ?>" alt="state" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Zip Code:</td>
                                <td><input name="location_zipcode" type="text" size="50" value="<?php echo esc_attr($location->getZipCode()) ?>" alt="zip code" /></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>

                <div id="postdivrich">
                    <h3><label for="content">Location Description</label></h3>
                    <?php the_editor($location->getDescription(), "location_description", "location_zipcode", false); ?>
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
                            $cc_object_collateral_list = &$location->getAllObject_Collateral();
                            $location->sortObject_Collateral();
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