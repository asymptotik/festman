<?php
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
    var action = form.elements["action"].value;
    if(action == 'delete_events')
      return confirm( 'You are about to delete the selected Events.\n \'Cancel\' to stop, \'OK\' to delete.' );
    return false;
}

</script>

<?php
require_once dirname(__FILE__).'/../objects/Event.php';
$nonce_action = 'modify-events';
?>

<form name="bulk-action-form" method="post" action="admin.php?page=fm-event-page">
  <?php wp_nonce_field( esc_attr($nonce_action) ); ?>
  <div class="wrap">
    <h2>Event Selector<a class="add-new-h2" href="admin.php?page=fm-event-page&action=create_event">Add New</a></h2>
    
    <div class="tablenav top">
      <div class="alignleft actions">
        <select name='action'>
          <option value='-1' selected='selected'>Bulk Actions</option>
          <option value='delete_events'>Delete</option>
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
        <th class="manage-column lc-column">Starts</th>
        <th class="manage-column lc-column">Ends</th>
        <th class="manage-column lc-column">Collateral Count</th>
      </tr>
    </thead>
    <tfoot>
    <tr>
      <th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
      <th class="manage-column lc-column" style="" scope="col">Location Name</th>
      <th class="manage-column lc-column" style="" scope="col">Starts</th>
      <th class="manage-column lc-column" style="" scope="col">Ends</th>
      <th class="manage-column lc-column" style="" scope="col">Collateral Count</th>
    </tr>
    </tfoot>
    <tbody id="the-list">
        
        <?php
        $events = Event::getAllEvents();
              $num_rows = count($events);
              
              if($num_rows > 0)
        {
          for($i = 0; $i < $num_rows; $i++)
          {
            $event = $events[$i];
            $event_id = $event->getId();
            $event_name = $event->getName();
            $collateral_count = Event::getCollateralCount($event_id);
            $action_id = uniqid("delete");
    
            $date = strtotime($event->getStartTime());
            $start_time = date('M d, Y h:i a', $date);
            $date = strtotime($event->getEndTime());
            $end_time = date('M d, Y h:i a', $date);
    
            //echo "<tr class=\"border\">".
            //"  <td><input type=\"checkbox\" name=\"event_ids[]\" value=\"".$event_id."\"></td>\n".
            //"  <td>".$event_name."&nbsp;(".$collateral_count.")</td>\n".
            //"  <td>&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:editEvent('".$event_id."');\">edit</a></td>\n".
            //"  <td>&nbsp;<a href=\"javascript:void(0);\" onClick=\"javascript:deleteEvent('".$event_id."');\">delete</a></td>\n".
            //"</tr>\n";
        ?>
  
       <tr>
         <th scope="row" class="check-column"><input type="checkbox" name="event_ids[]" value="<?php echo esc_attr($event_id); ?>" /></th>
         <td class="column-name">
          <strong>
            <a class="row-title" title="Edit Event" href="admin.php?page=fm-event-page&action=edit_event&event_id=<?php echo esc_attr($event_id); ?>"><?php echo esc_html($event_name);?></a>
          </strong>
          <br>
          <div class="row-actions">
            <span class="edit">
              <a href="admin.php?page=fm-event-page&action=edit_event&event_id=<?php echo esc_attr($event_id); ?>">Edit</a> |
            </span>
            <span class="delete">
              <a class="submitdelete" onclick="if ( confirm( 'You are about to delete this Event.\n \'Cancel\' to stop, \'OK\' to delete.' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( "admin.php?page=fm-event-page&amp;action=delete_event&amp;event_id=" . esc_attr($event_id), 'delete-event_' . esc_attr($event_id) )  ?>">Delete</a>
            </span>
          </div>
        </td>
        <td><?php echo esc_html($start_time);?></td>
        <td><?php echo esc_html($end_time);?></td>
        <td><?php echo esc_html($collateral_count);?></td>
      </tr>
      
        
      <?php   } // endforeach ?>
      <?php } else { // endif ?>
        <tr class="no-items">
          <td class="colspanchange" colspan="2">No Events found.</td>
        </tr>
      <?php } ?>
  
      </tbody>
    </table>
  </div>
</form>
<?php
fmClearMessages();
?>