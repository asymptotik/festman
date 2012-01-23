<?php

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', false);

/** Loads the WordPress Environment and Template */
require('../../../../wp-blog-header.php');

require_once CMKYF_PLUGIN_BASE_DIR.'/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/opendb.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR.'/objects/ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Event.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Collateral.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Location.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Program_ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Program.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Workshop.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Installation.php';
	
require_once CMKYF_PLUGIN_BASE_DIR.'/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/opendb.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/utils.php';
	    
?>


<?php
    $events = Event::getAllEventsSortedByDate();
    
    if(count($events) > 0)
    {
    	$currentDayDate = new DateTime($events[0]->getStartTime());
    	$currentDayStr = date_format($currentDayDate, "m/d/Y");
    	$itemNum = 1;
    	
    	echo '<div class="schedule">' . "\n";
    	echo '  <div class="schedule-column">' . "\n";
        echo '    <div class="day c' . (($itemNum % 5) + 1) . '-style">' . "\n";
        echo '      <div class="page-panel-header c-border c-bg corners-top">' ."\n";
		echo '        <table class="panel-content">' . "\n";
		echo '          <tr>' . "\n";
		echo '    	      <td class="page-title"><h2>' . date_format($currentDayDate, "l") . '</h2></td>' . "\n";
		echo '          </tr>' . "\n";
	    echo '        </table>' . "\n";
	    echo '      </div>' . "\n";
    	echo '      <div class="c-border page-panel-body corners-bottom">' . "\n";
    	
	    foreach($events as $event)
	    {
	    	$startTime = new DateTime($event->getStartTime());
	    	$endTime = new DateTime($event->getEndTime());
	    	$location = $event->getLocation();
	    	$program = $event->getProgram();
	    	
	    	$startTimeStr = date_format($startTime, "m/d/Y");
	    	$startHour = date_format($startTime, "G");
	    	
	    	if($startTimeStr != $currentDayStr && $startHour >= 6)
	    	{
	    		$itemNum += 1;
	    		echo "      </div>\n"; // end page-panel-body for events
	    	    echo "    </div>\n"; // end day start a new one
	    	    
	    	    if(($itemNum % 2) == 1)
		    	{
		    		echo '  </div>' . "\n"; // end schedule-column
		    		echo '  <div class="schedule-column schedule-column-margin">' . "\n";
		    	} 
		    	
	    	    echo '    <div class="day c' . (($itemNum % 5) + 1) . '-style">' . "\n";
	    	    echo '      <div class="page-panel-header c-border c-bg corners-top">' ."\n";
				echo '        <table class=" panel-content">' . "\n";
				echo '          <tr>' . "\n";
				echo '           <td class="page-title"><h2>' . date_format($startTime, "l") . '</h2></td>' . "\n";
				echo '          </tr>' . "\n";
			    echo '        </table>' . "\n";
				echo '      </div>' . "\n";
				echo '    <div class="c-border page-panel-body corners-bottom">' . "\n";
				
	    	    $currentDayStr = $startTimeStr;
	    	}
	    	
			echo '    <div class="event panel-content">' . "\n";
			echo '      <h3 class="name"><a href="#event-' . $event->getId() . '">' . $event->getName() . '</a></h3>' . "\n";

			if(isset($location))
			{
				echo '      <div class="location">' . "\n";
				echo '        <div class="name"><a href="#venue-' . $location->getId() . '">' . $location->getName() . '</a></div>' . "\n";
				echo '        <div class="address">' . $location->getAddress() . '</div>' . "\n";
				//echo '        <span class="city">' . $location->getCity() . '</span>';
				//echo '        <span class="state">' . $location->getState() . '</span>';
				//echo '        <span class="zip-code">' . $location->getZipCode() . '</span>' . "\n";
				echo '      </div>' . "\n";
			}
			echo '      <div class="time">' . date_format($startTime, "g:i A") . ' - ' . date_format($endTime, "g:i A") . '</div>' . "\n";
			echo '      <ul class="program">' . "\n";
			//echo '        <div class="program-id">' . $program->getId() . '</div>' . "\n";
			
			$program->sortProgram_ProgramItems();
			$program_program_items = &$program->getProgram_ProgramItems();
			
			foreach($program_program_items as $program_program_item)
			{
				$program_item = $program_program_item->getProgramItem();

				// echo '          <div class="type">' . $program_item->getObjectClass() . '</div>' . "\n";
				echo '          <li><a href="#' . strtolower($program_item->getObjectClass()) . '-' . $program_item->getId() . '">' . $program_item->getName() . '</a></li>' . "\n";
			}
			
			echo '      </ul>' . "\n"; // program
			echo '    </div>' . "\n"; // event
	    }
	    
	    echo '   </div>' . "\n"; // end panel-content for events
	    echo '  </div>' . "\n"; // day
	    echo '  </div>' . "\n"; // day
		echo '</div>' . "\n"; // schedule
		echo '<div class="clear"></div>';
    }
    
    if(isset($_SESSION['action_message']))
	{
		echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
	}

    include CMKYF_PLUGIN_BASE_DIR.'/library/closedb.php';
?>
