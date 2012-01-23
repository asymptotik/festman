<?php
require_once (dirname(__FILE__).'/../objects/ProgramItem.php');
require_once (dirname(__FILE__).'/../objects/Act.php');
require_once (dirname(__FILE__).'/../objects/Klass.php');
require_once (dirname(__FILE__).'/../objects/Film.php');
require_once (dirname(__FILE__).'/../objects/Panel.php');
require_once (dirname(__FILE__).'/../objects/Film.php');
require_once (dirname(__FILE__).'/../objects/Workshop.php');
require_once (dirname(__FILE__).'/../objects/Installation.php');
require_once (dirname(__FILE__).'/../objects/Collateral.php');

require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');


if(isset($_SESSION['error_message']))
{
  echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".$_SESSION['error_message']."</TD></TR></TABLE><BR/>";
}
if(isset($_SESSION['action_message']))
{
  echo "<TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
}

$program_item_class = $_SESSION['current_program_item_class'];
if(isset($program_item_class) == false)
	$program_item_class = "Act";
$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item_class);
$program_item_class_page = 'fm-' . strtolower($program_item_class) . '-page';
?>

<div class="wrap">
  <h2><?php echo $object_class_display_name; ?> Selector<a class="add-new-h2" href="admin.php?page=<?php echo $program_item_class_page; ?>&action=create_program_item&program_item_class=<?php echo $program_item_class; ?>">Add New</a></h2>
  <table class="wp-list-table widefat fixed pages" callspacing="0">
  <thead>
    <tr>
      <th class="manage-column lc-column"><?php echo $object_class_display_name; ?> Name</th>
      <th class="manage-column lc-column">Origin</th>
      <th class="manage-column lc-column">Collateral Count</th>
    </tr>
  </thead>
  <tfoot>
  <tr>
    <th class="manage-column lc-column" style="" scope="col"><?php echo $object_class_display_name; ?> Name</th>
    <th class="manage-column lc-column" style="" scope="col">Origin</th>
    <th class="manage-column lc-column" style="" scope="col">Collateral Count</th>
  </tr>
  </tfoot>
  <tbody id="the-list">
      
      <?php
      $plugin_url = plugin_dir_url( __FILE__ ) . "../../";
      $program_items = ProgramItem::getAllTypedProgramItems($program_item_class);
      $num_rows = count($program_items);

      if($num_rows > 0)
      {
        for($i = 0; $i < $num_rows; $i++)
        {
          $program_item_id = $program_items[$i]->getId();
          $program_item_name = $program_items[$i]->getName();
          $program_item_origin = $program_items[$i]->getOrigin();
          $collateral_count = ProgramItem::getCollateralCount($program_item_id);
          $action_id = uniqid("delete");
      ?>

     <tr>
       <td class="column-name">
        <strong>
          <a class="row-title" title="Edit ÒDocumentationÓ" href="admin.php?page=<?php echo $program_item_class_page; ?>&action=edit_program_item&program_item_id=<?php echo $program_item_id ?>&program_item_class=<?php echo $program_item_class; ?>"><?php echo stripslashes($program_item_name);?></a>
        </strong>
        <br>
        <div class="row-actions">
          <span class="edit">
            <a href="admin.php?page=<?php echo $program_item_class_page; ?>&action=edit_program_item&program_item_id=<?php echo $program_item_id ?>&program_item_class=<?php echo $program_item_class; ?>">Edit</a> |
          </span>
          <span class="delete">
            <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this Concert.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( "admin.php?page=$program_item_class_page&amp;action=delete_program_item&amp;program_item_id=$program_item_id&program_item_class=$program_item_class", 'delete-program-item_' . $program_item_id )  ?>">Delete</a>
          </span>
        </div>
      </td>
      <td><?php echo $program_item_origin;?></td>
      <td><?php echo $collateral_count;?></td>
    </tr>
    
      
    <?php   } // endforeach ?>
  <?php } else { // endif ?>
    <tr class="no-items">
      <td class="colspanchange" colspan="2">No Acts found.</td>
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

<?php
    fmClearMessages();
?>