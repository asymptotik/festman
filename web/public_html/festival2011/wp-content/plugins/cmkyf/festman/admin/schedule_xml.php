<?php
	require_once dirname(__FILE__).'/../objects/ProgramItem.php';
	require_once dirname(__FILE__).'/../objects/Act.php';
	require_once dirname(__FILE__).'/../objects/Event.php';
	require_once dirname(__FILE__).'/../objects/Collateral.php';
	require_once dirname(__FILE__).'/../objects/Klass.php';
	require_once dirname(__FILE__).'/../objects/Film.php';
	require_once dirname(__FILE__).'/../objects/Location.php';
	require_once dirname(__FILE__).'/../objects/Panel.php';
	require_once dirname(__FILE__).'/../objects/Film.php';
	require_once dirname(__FILE__).'/../objects/Program_ProgramItem.php';
	require_once dirname(__FILE__).'/../objects/Program.php';
	require_once dirname(__FILE__).'/../objects/Workshop.php';
	
	require_once dirname(__FILE__).'/../library/config.php';
	require_once dirname(__FILE__).'/../library/opendb.php';
	require_once dirname(__FILE__).'/../library/utils.php';
	    
    header("Content-Type: application/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<?php
    $events = Event::getAllEventsSortedByDate();
    
    if(count($events) > 0)
    {
    	$currentDayDate = new DateTime($events[0]->getStartTime());
    	$currentDayStr = date_format($currentDayDate, "m/d/Y");
    	
    	echo "<Schedule>";
        echo "  <Day>\n";
        echo "    <Date>" . $currentDayStr . "</Date>\n";
    
	    foreach($events as $event)
	    {
	    	$startTime = new DateTime($event->getStartTime());
	    	$endTime = new DateTime($event->getEndTime());
	    	$location = $event->getLocation();
	    	$program = $event->getProgram();
	    	
	    	$startTimeStr = date_format($startTime, "m/d/Y");
	    	
	    	if($startTimeStr != $currentDayStr)
	    	{
	    	    echo "</Day>\n";
	    	    echo "<Day>\n";
	    	    echo "  <Date>" . $startTimeStr . "</Date>\n";
	    	    $currentDayStr = $startTimeStr;
	    	}
	    	
			echo "    <Event>\n";
			echo "      <Event_Id>" . $event->getId() . "</Event_Id>\n";
			echo "      <StartTime>" . date_format($startTime, "m/d/Y H:i") . "</StartTime>\n";
			echo "      <EndTime>" . date_format($endTime, "m/d/Y H:i") . "</EndTime>\n";
			echo "      <Location>\n";
			echo "        <Location_Id>" . $location->getId() . "</Location_Id>\n";
			echo "        <Name>" . $location->getName() . "</Name>\n";
			echo "        <Address>" . $location->getAddress() . "</Address>\n";
			echo "        <City>" . $location->getCity() . "</City>\n";
			echo "        <State>" . $location->getState() . "</State>\n";
			echo "        <ZipCode>" . $location->getZipCode() . "</ZipCode>\n";
			echo "      </Location>\n";
			echo "      <Program>\n";
			echo "        <Program_Id>" . $program->getId() . "</Program_Id>\n";
			
			$program->sortProgram_ProgramItems();
			$program_program_items = &$program->getProgram_ProgramItems();
			
			foreach($program_program_items as $program_program_item)
			{
				$program_item = $program_program_item->getProgramItem();

				echo "        <ProgramItem>\n";
				echo "          <ProgramItem_Id>" . $program_program_item->getId() . "</ProgramItem_Id>\n";
				echo "          <Type>" . $program_item->getObjectClass() . "</Type>\n";
				echo "          <Name>" . $program_item->getName() . "</Name>\n";
				echo "        </ProgramItem>\n";
			}
			
			echo "      </Program>\n";
			echo "    </Event>\n";
	    }
	    
	    echo "  </Day>\n";
	    echo "</Schedule>";
    }
    
    include dirname(__FILE__).'/../library/closedb.php';
?>
