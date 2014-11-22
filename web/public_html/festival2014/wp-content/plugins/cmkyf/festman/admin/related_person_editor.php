<?php
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');
require_once (dirname(__FILE__) . '/../objects/RelatedPerson.php');
require_once (dirname(__FILE__) . '/../objects/Collateral.php');
require_once (dirname(__FILE__) . '/../library/config.php');
require_once (dirname(__FILE__) . '/../library/utils.php');
require_once (dirname(__FILE__) . '/admin_utils.php');
?>

<script type="text/javascript">

    fm_ignoreChanges = true;

    var nameValidator = new fmValidator();
    nameValidator.addValidator(new fmRequiredValidator("Please enter a Name."));
    nameValidator.addValidator(new fmMaxLengthValidator(256, "Name must have a length less that or equal to 256."));
    
    var urlValidator = new fmValidator();
    urlValidator.addValidator(new fmRequiredValidator("Please enter a Url."));
    urlValidator.addValidator(new fmMaxLengthValidator(256, "Url must have a length less that or equal to 256."));

    var urlTextValidator = new fmValidator();
    urlTextValidator.addValidator(new fmMaxLengthValidator(128, "Url must have a text length less that or equal to 128."));
    
    var descriptionValidator = new fmValidator();
    descriptionValidator.addValidator(new fmRequiredValidator("Please enter a Description."));
    descriptionValidator.addValidator(new fmMaxLengthValidator(16384, "Description must have a length less that or equal to 16384."));
    
    var roleValidator = new fmValidator();
    roleValidator.addValidator(new fmRequiredValidator("Please select a Role."));
    
    function fmOnSubmitRelatedPersonForm()
    {
        var form = document.forms["related_person_form"];
        var elements = form.elements;
    
        var name = form.elements["related_person_name"].value;
        var url = form.elements["related_person_url"].value;
        var url_text = form.elements["related_person_url_text"].value;
        var editor = tinyMCE.get("related_person_description");
        var description = editor.getContent();
        var related_person_role = form.elements["related_person_role"].value;
    
        var errorString = nameValidator.validate(name);
        errorString = fmAppendLine(errorString, urlValidator.validate(url));
        errorString = fmAppendLine(errorString, urlTextValidator.validate(url_text));
        errorString = fmAppendLine(errorString, roleValidator.validate(related_person_role));
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

    function fmOnCancelRelatedPersonEditor()
    {
        var form = document.forms["related_person_form"];
    
        form.elements["action"].value = "cancel_related_person_editor";
        form.submit();
    }

    function fmAddCollateral()
    {
        var form = document.forms["related_person_form"];
        form.action = "admin.php?page=fm-collateral-page";
        form.elements["action"].value = "add_collateral";
        form.submit();
    }
    
    function fmRemoveCollateral(collateral_id)
    {
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["related_person_form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral";
            form.elements["object_collateral_id"].value = collateral_id;

            form.submit();
        }
    }

    function fmRemoveCollateralList()
    {
        if ( confirm( 'You are about to remove this Collateral.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) 
        { 
            var form = document.forms["related_person_form"];

            form.action = "admin.php?page=fm-collateral-page";
            form.elements["action"].value = "remove_collateral_list";

            form.submit();
        }
    }
    
</script>

<?php
if (isset($_SESSION['current_related_person']))
{
    $related_person = $_SESSION['current_related_person'];
    $fm_is_new = false;
}
else
{
    $related_person = new RelatedPerson();
    $fm_is_new = true;
}

$program_item = $_SESSION['current_program_item'];

$program_item_class = $program_item->getObjectClass();
$program_item_name = $program_item->getName();
$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item_class);

$fm_page = "fm-" . strtolower($program_item_class) . "-page";

$related_person_role = $related_person->getRole();

if (!$fm_is_new)
{
    $heading = sprintf(__('Related Person / Update for %s: %s'), esc_html($object_class_display_name), esc_html($program_item_name));
    $submit_text = __('Update Person');
    $form = '<form name="related_person_form" id="related_person_form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'update-person_' . $related_person->getId();
}
else
{
    $heading = sprintf(__('Related Person / Add for %s: %s'), esc_html($object_class_display_name), esc_html($program_item_name));
    $submit_text = __('Add Person');
    $form = '<form name="related_person_form" id="related_person_form" method="post" action="admin.php?page=' . $fm_page . '">';
    $nonce_action = 'add-person';
}

require_once(ABSPATH . 'wp-admin/includes/meta-boxes.php');

add_screen_option('layout_columns', array('max' => 2));

$user_ID = isset($user_ID) ? (int) $user_ID : 0;

if (isset($_SESSION['error_message']))
{
    echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">" . esc_html($_SESSION['error_message']) . "</TD></TR></TABLE><BR/>";
}
if (isset($_SESSION['action_message']))
{
    echo "<TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>" . esc_html($_SESSION['action_message']) . "</TD></TR></TABLE>";
}

if (!empty($form))
    echo $form;

echo "\n";
wp_nonce_field($nonce_action);
echo "\n";
?>

<div class="wrap">
    <div id="icon-themes" class="icon32">
        <br>
    </div>
    <h2><?php echo $heading; ?></h2>
    <div id="poststuff" class="metabox-holder has-right-sidebar">

        <input type="hidden" name="action" value="store_related_person" /> 
        <input type="hidden" name="action_id" value="<?php echo uniqid("delete"); ?>" /> 
        <input type="hidden" name="related_person_id" value="<?php echo esc_attr($related_person->getId()); ?>" /> 
        <input type="hidden" name="collateral_collection_type" value="related_person" />
        <input type="hidden" name="object_collateral_id" value="" /> 
        <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" /> 

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
                                <?php if (!$fm_is_new)
                                { ?>
                                    <div id="delete-action">
                                        <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this concert \'<?php echo esc_attr($program_item->getName()) ?>\'\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url("admin.php?page=$fm_page&amp;action=delete_related_person&amp;related_person_id=$related_person->getId()", 'delete-related-person_' . $related_person->getId()) ?>">Delete</a>
                                    </div>
<?php } ?>
                                <div id="publishing-action">
                                    <button onclick="javascript:fmOnSubmitRelatedPersonForm(); return false;" id="publish" class="button-primary" accesskey="p" tabindex="4" name="save"><?php echo $submit_text; ?></button>
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
                        <label for="name"><?php esc_html($object_class_display_name) ?></label>
                    </h3>
                    <div class="inside">
                        <table class="form-table edit-concert concert-form-table">

                            <tr valign="top">
                                <td class="first">Name:</td>
                                <td><input name="related_person_name" type="text" size="50" value="<?php echo esc_attr($related_person->getName()) ?>" alt="name" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Url:</td>
                                <td><input name="related_person_url" type="text" size="50" value="<?php echo esc_attr($related_person->getUrl()) ?>" alt="url" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Url Text:</td>
                                <td><input name="related_person_url_text" type="text" size="50" value="<?php echo esc_attr($related_person->getUrlText()) ?>" alt="url" /></td>
                            </tr>
                            <tr valign="top">
                                <td class="first">Role:</td>
                                <td>
                                    <select name="related_person_role">
                                        <option value=""<?php echo ($related_person_role == "" ? " selected=\"true\"" : ""); ?>>&lt;Select&gt;</option>
                                        <option value="artist"<?php echo ($related_person_role == "artist" ? " selected=\"true\"" : ""); ?>>Artist</option>
                                        <option value="video"<?php echo ($related_person_role == "video" ? " selected=\"true\"" : ""); ?>>Video</option>
                                        <option value="lead"<?php echo ($related_person_role == "lead" ? " selected=\"true\"" : ""); ?>>Lead</option>
                                        <option value="member"<?php echo ($related_person_role == "member" ? " selected=\"true\"" : ""); ?>>Member</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </div>

                <div id="postdivrich">
                    <h3><label for="content">Related Person Description</label></h3>
                    <?php the_editor($related_person->getDescription(), "related_person_description", "program_item_url_text", false); ?>
                </div>

                <br/>

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
                            $cc_object_collateral_list = &$related_person->getAllObject_Collateral();
                            $related_person->sortObject_Collateral();
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


                                <?php } // endforeach   ?>
                            <?php
                            }
                            else
                            { // endif  
                                ?>
                                    <tr class="no-items">
                                    <td class="colspanchange" colspan="2">No Collateral found.</td>
                                </tr>
                            <?php } ?>

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