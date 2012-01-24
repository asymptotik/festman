<?php
require_once (dirname(__FILE__).'/../objects/Location.php');
require_once (dirname(__FILE__).'/../objects/Collateral.php');

require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');
?>

<script type="text/javascript">

</script>

<?php

if(isset($_SESSION['error_message']))
{
  echo "<TABLE align=\"center\" width=\"400\" class=\"border\"><TR><TD class=\"error\">".esc_html($_SESSION['error_message'])."</TD></TR></TABLE><BR/>";
}
if(isset($_SESSION['action_message']))
{
  echo "<TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".esc_html($_SESSION['action_message'])."</TD></TR></TABLE>";
}

?>

<div class="wrap">
  <h2>Location Selector<a class="add-new-h2" href="admin.php?page=fm-location-page&action=create_location">Add New</a></h2>
  <table class="wp-list-table widefat fixed pages" callspacing="0">
  <thead>
    <tr>
      <th class="manage-column lc-column">Location Name</th>
      <th class="manage-column lc-column">Collateral Count</th>
    </tr>
  </thead>
  <tfoot>
  <tr>
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
       <td class="column-name">
        <strong>
          <a class="row-title" title="Edit ÒLocationÓ" href="admin.php?page=fm-location-page&action=edit_location&location_id=<?php echo esc_attr($location_id); ?>"><?php echo esc_html($location_name);?></a>
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

<?php
fmClearMessages();
?>