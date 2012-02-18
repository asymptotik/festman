<?php
require_once (dirname(__FILE__) . '/../objects/ProgramItem.php');
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');
require_once (dirname(__FILE__) . '/../objects/Collateral.php');

require_once (dirname(__FILE__) . '/../library/config.php');
require_once (dirname(__FILE__) . '/../library/utils.php');
require_once (dirname(__FILE__) . '/admin_utils.php');
?>
<script type="text/javascript">

    fm_ignoreChanges = true;

    function fmHandleBulkAction(name)
    {
        fm_ignoreChanges = true;
    
        var form = document.forms["bulk-action-form"];
        var elements = form.elements;
        var action = form.elements["action"].value;
        var displayname = '';
    
        if(name == 'Class')
            displayname = name + 'es';
        else
            displayname = name + 's';
      
        if(action == 'delete_program_items')
            return confirm( 'You are about to delete the selected ' + displayname + '\n \'Cancel\' to stop, \'OK\' to delete.' );
        return false;
    }

</script>

<?php
$program_item_class = $_SESSION['current_program_item_class'];
if (isset($program_item_class) == false)
    $program_item_class = "Act";
$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item_class);
$program_item_class_page = 'fm-' . strtolower(esc_attr($program_item_class)) . '-page';
$nonce_action = 'modify-' . $program_item_class;
?>

<form name="bulk-action-form" method="post" action="admin.php?page=<?php echo esc_attr($program_item_class_page) ?>">
    <input type="hidden" name="program_item_class" value="<?php echo esc_attr($program_item_class); ?>"/>
    <?php wp_nonce_field(esc_attr($nonce_action)); ?>
    <div class="wrap">
        <h2><?php echo esc_html($object_class_display_name); ?> Selector<a class="add-new-h2" href="admin.php?page=<?php echo esc_attr($program_item_class_page); ?>&action=create_program_item&program_item_class=<?php echo esc_attr($program_item_class); ?>">Add New</a></h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <select name='action'>
                    <option value='-1' selected='selected'>Bulk Actions</option>
                    <option value='delete_program_items'>Delete</option>
                </select>
                <input onclick="return fmHandleBulkAction('<?php echo esc_attr($object_class_display_name); ?>');" type="submit" name="" id="doaction2" class="button-secondary action" value="Apply"  />
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

        <table class="wp-list-table widefat fixed pages" callspacing="0">
            <thead>
                <tr>
                    <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
                    <th class="manage-column lc-column"><?php echo esc_html($object_class_display_name); ?> Name</th>
                    <th class="manage-column lc-column">Origin</th>
                    <th class="manage-column lc-column">Collateral Count</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
                    <th class="manage-column lc-column" style="" scope="col"><?php echo esc_html($object_class_display_name); ?> Name</th>
                    <th class="manage-column lc-column" style="" scope="col">Origin</th>
                    <th class="manage-column lc-column" style="" scope="col">Collateral Count</th>
                </tr>
            </tfoot>
            <tbody id="the-list">

                <?php
                $plugin_url = plugin_dir_url(__FILE__) . "../../";
                $program_items = ProgramItem::getAllTypedProgramItems($program_item_class);
                $num_rows = count($program_items);

                if ($num_rows > 0)
                {
                    for ($i = 0; $i < $num_rows; $i++)
                    {
                        $program_item_id = $program_items[$i]->getId();
                        $program_item_name = $program_items[$i]->getName();
                        $program_item_origin = $program_items[$i]->getOrigin();
                        $collateral_count = ProgramItem::getCollateralCount($program_item_id);
                        $action_id = uniqid("delete");
                        ?>

                        <tr>
                            <th scope="row" class="check-column"><input type="checkbox" name="program_item_ids[]" value="<?php echo esc_attr($program_item_id); ?>" /></th>
                            <td class="column-name">
                                <strong>
                                    <a class="row-title" title="Edit �Documentation�" href="admin.php?page=<?php echo esc_attr($program_item_class_page); ?>&action=edit_program_item&program_item_id=<?php echo esc_attr($program_item_id); ?>&program_item_class=<?php echo esc_attr($program_item_class); ?>"><?php echo esc_html($program_item_name); ?></a>
                                </strong>
                                <br>
                                <div class="row-actions">
                                    <span class="edit">
                                        <a href="admin.php?page=<?php echo esc_attr($program_item_class_page); ?>&action=edit_program_item&program_item_id=<?php echo esc_attr($program_item_id); ?>&program_item_class=<?php echo esc_attr($program_item_class); ?>">Edit</a> |
                                    </span>
                                    <span class="delete">
                                        <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this \'<?php echo esc_html($object_class_display_name); ?>\'.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url("admin.php?page=" . esc_attr($program_item_class_page) . "&amp;action=delete_program_item&amp;program_item_id=" . esc_attr($program_item_id) . "&program_item_class=" . esc_attr($program_item_class), 'delete-program-item_' . esc_attr($program_item_id)) ?>">Delete</a>
                                    </span>
                                </div>
                            </td>
                            <td><?php echo esc_html($program_item_origin); ?></td>
                            <td><?php echo esc_html($collateral_count); ?></td>
                        </tr>


    <?php } // endforeach  ?>
                <?php }
                else
                { // endif ?>
                    <tr class="no-items">
                        <td class="colspanchange" colspan="2">No <?php echo esc_html($object_class_display_name); ?> found.</td>
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
</form>
<?php
fmClearMessages();
?>