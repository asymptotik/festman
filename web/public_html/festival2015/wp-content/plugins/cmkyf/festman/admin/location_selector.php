<?php
require_once (dirname(__FILE__).'/../objects/Location.php');
require_once (dirname(__FILE__).'/../objects/Collateral.php');

require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');
?>

<script type="text/javascript">

fm_ignoreChanges = true;

function fmHandleBulkAction(name)
{
    fm_ignoreChanges = true;
    
    var form = document.forms["bulk-action-form"];
    var elements = form.elements;
    var action = form.elements["action"].value;
    if(action == 'delete_locations')
      return confirm( 'You are about to delete the selected Locations.\n \'Cancel\' to stop, \'OK\' to delete.' );
    return false;
}

</script>

<?php
$nonce_action = 'modify-locations';
?>

<form name="bulk-action-form" method="post" action="admin.php?page=fm-location-page">
  <div class="wrap">
    <h2>Location Selector<a class="add-new-h2" href="admin.php?page=fm-location-page&action=create_location">Add New</a></h2>
    
    <div class="tablenav top">
      <div class="alignleft actions">
        <select name='action'>
          <option value='-1' selected='selected'>Bulk Actions</option>
          <option value='delete_locations'>Delete</option>
        </select>
        <input onclick="return fmHandleBulkAction();" type="submit" name="" id="doaction2" class="button-secondary action" value="Apply"  />
      </div>
      <?php
        if(isset($_SESSION['error_message'])) { echo '<div class="error">'  .esc_html($_SESSION['error_message']) . '</div>'; }
        if(isset($_SESSION['action_message'])) { echo '<div class="message">' . esc_html($_SESSION['action_message']) . '</div>'; }
      ?>
      <br class="clear" />
    </div>
    
    <table class="wp-list-table widefat fixed pages" callspacing="0">
    <thead>
      <tr>
        <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
        <th class="manage-column lc-column">Location Name</th>
        <th class="manage-column lc-column">Collateral Count</th>
      </tr>
    </thead>
    <tfoot>
    <tr>
      <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
      <th class="manage-column lc-column" style="" scope="col">Location Name</th>
      <th class="manage-column lc-column" style="" scope="col">Collateral Count</th>
    </tr>
    </tfoot>
    <tbody id="the-list">
        
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
    
            //echo "<tr class=\"border\">".
            //"  <td><input type=\"checkbox\" name=\"location_ids[]\" value=\"".$location_id."\"</td>\n".
            //"  <td>".$location_name."&nbsp;(".$collateral_count.")</td>\n".
            //"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:fmEditLocation('".$location_id."');\">edit</a></td>\n".
            //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:fmDeleteLocation('".$location_id."');\">delete</a></td>\n".
            //"</tr>\n";
        ?>
  
       <tr>
         <th scope="row" class="check-column"><input type="checkbox" name="location_ids[]" value="<?php echo esc_attr($location_id); ?>" /></th>
         <td class="column-name">
          <strong>
            <a class="row-title" title="Edit �Location�" href="admin.php?page=fm-location-page&action=edit_location&location_id=<?php echo esc_attr($location_id); ?>"><?php echo esc_html($location_name);?></a>
          </strong>
          <br>
          <div class="row-actions">
            <span class="edit">
              <a href="admin.php?page=fm-location-page&action=edit_location&location_id=<?php echo esc_attr($location_id); ?>">Edit</a> |
            </span>
            <span class="delete">
              <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this Location.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( "admin.php?page=fm-location-page&amp;action=delete_location&amp;location_id=" . esc_attr($location_id), 'delete-location_' . esc_attr($location_id) )  ?>">Delete</a>
            </span>
          </div>
        </td>
        <td><?php echo esc_html($collateral_count);?></td>
      </tr>
      
        
      <?php   } // endforeach ?>
    <?php } else { // endif ?>
      <tr class="no-items">
        <td class="colspanchange" colspan="2">No Locations found.</td>
      </tr>
    <?php } ?>
  
    </tbody>
  </table>
</form>

<?php
fmClearMessages();
?>