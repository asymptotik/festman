<?php

require_once dirname(__FILE__).'/Collateral.php';
require_once dirname(__FILE__).'/ObjectTable.php';
require_once dirname(__FILE__).'/Location.php';
require_once dirname(__FILE__).'/Program.php';
require_once dirname(__FILE__).'/AbstractCollateralCollection.php';
require_once dirname(__FILE__).'/Utils.php';

class Event extends AbstractCollateralCollection
{
	private $Event_Id = NULL;
	private $Name = '';
	private $Description = '';
	private $StartTime = '';
	private $EndTime = '';
	private $Location_Id = NULL;
	private $Location = NULL;
	private $Program_Id = NULL;
	private $Program = NULL;
	private $IsDirty = false;

	public static function getEvent($event_id)
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getEventTableName() . " WHERE Event_Id=".fmQueryValue($event_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'Event'))
		{
			return $row;
		}

		return null;
	}

	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("Event");
	}
	
	public static function getAllEvents()
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getEventTableName();
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'Event'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}

	public static function getAllEventsSortedByDate()
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getEventTableName() . " ORDER BY StartTime";
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'Event'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
	
	public static function deleteEvent($event_id)
	{
		global $fm_db;
		Object_Collateral::deleteAllObject_Collateral(Event::getClassObjectTable(), $event_id);
		$query_string = "DELETE FROM " . $fm_db->getProgram_ProgramItemTableName() . " WHERE Program_Id IN (SELECT Program_Id FROM Event WHERE Event_Id=".fmQueryValue($event_id).")";
		mysql_query($query_string);
		$query_string = "DELETE FROM " . $fm_db->getProgramTableName() . " WHERE Program_Id IN (SELECT Program_Id FROM Event WHERE Event_Id=".fmQueryValue($event_id).")";
		mysql_query($query_string);
		$query_string = "DELETE FROM " . $fm_db->getEventTableName() . " WHERE Event_Id=".fmQueryValue($event_id);
		mysql_query($query_string);
	}

	public static function getCollateralCount($event_id)
	{
		return Object_Collateral::getCollateralCount("Event", $event_id);
	}

	public static function getCollateralInstanceCount($collateral_id)
	{
		return Object_Collateral::getCollateralInstanceCount("Event", $collateral_id);
	}

	public static function getEventsForProgramItem($program_item_id)
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getEventTableName() . " WHERE Program_Id in (
		SELECT Program_Id FROM " . $fm_db->getProgram_ProgramItemTableName() . " WHERE ProgramItem_Id=".$program_item_id.") ORDER BY StartTime";

		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'Event'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
	
	public function save()
	{
		$new_id = createOrUpdateEvent($this);
		
		if($new_id > 0)
		{
			$this->Event_Id = $new_id;
			$this->setIsDirty(false);
		}
	}

	public function isDirty()
	{
		return $this->IsDirty;
	}

	public function setIsDirty($value)
	{
		$this->IsDirty = $value;
	}

	public function getId()
	{
		return $this->Event_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Event_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Program_Id = $value;
		}
	}
	
	public function getName()
	{
		return $this->Name;
	}

	public function setName($value)
	{
		if($this->Name != $value)
		{
			$this->setIsDirty(true);
			$this->Name = $value;
		}
	}

	public function getDescription()
	{
		return $this->Description;
	}

	public function setDescription($value)
	{
		if($this->Description != $value)
		{
			$this->setIsDirty(true);
			$this->Description = $value;
		}
	}

	public function getStartTimeString()
	{
		return Utils::getTime($this->StartTime);
	}
	
	public function setStartTimeString($value)
	{
		$value = Utils::setTime($value);
		
		if($this->StartTime != $value)
		{
			$this->setIsDirty(true);
			$this->StartTime = $value;
		}
	}
	
	public function getStartTime()
	{
		return $this->StartTime;
	}

	public function setStartTime($value)
	{
		if($this->StartTime != $value)
		{
			$this->setIsDirty(true);
			$this->StartTime = $value;
		}
	}

	public function getEndTimeString()
	{
		return Utils::getTime($this->EndTime);
	}

	public function setEndTimeString($value)
	{
		$value = Utils::setTime($value);
		
		if($this->EndTime != $value)
		{
			$this->setIsDirty(true);
			$this->EndTime = $value;
		}
	}
	
	public function getEndTime()
	{
		return $this->EndTime;
	}

	public function setEndTime($value)
	{
		if($this->EndTime != $value)
		{
			$this->setIsDirty(true);
			$this->EndTime = $value;
		}
	}

	public function getLocation_Id()
	{
		return $this->Location_Id;
	}

	public function setLocation_Id($value)
	{
		if($this->Location_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Location_Id = $value;
		}
	}

	public function getLocation()
	{
		if($this->Location == NULL && $this->Location_Id != NULL)
		{
			return $this->Location = Location::getLocation($this->Location_Id);
		}
		return $this->Location;
	}

	public function setLocation($value)
	{
		if($this->Location != $value)
		{
			$this->setIsDirty(true);
			$this->Location = $value;
			$this->Location_Id = $value->Location_Id;
		}
	}

	public function getProgram_Id()
	{
		return $this->Program_Id;
	}

	public function setProgram_Id($value)
	{
		if($this->Program_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Program_Id = $value;
		}
	}

	public function getProgram()
	{
		if($this->Program == NULL && $this->Program_Id != NULL)
		{
			$this->Program = Program::getProgram($this->Program_Id);
		}
		return $this->Program;
	}

	public function setProgram($value)
	{
		if($this->Program != $value)
		{
			$this->setIsDirty(true);
			$this->Program = $value;
			$this->Program_Id = $value->getId();
		}
	}

	public function getCollateralDirectory()
	{
		return "event";
	}
	
	public function getObjectTable()
	{
		return Event::getClassObjectTable();
	}
	
	public function getDisplayName()
	{
		return "Event" . (strlen($this->getName()) > 0 ? ": " . $this->getName() : "");
	}
	
	public function getEditor()
	{
		return "event_editor.php";
	}
}

//
// Create or update the Event. If the $event_id is less than or equal to 0
// then we insert, otherwise we update. The Event_Id is returned.
//
function createOrUpdateEvent(Event $event)
{
	//echo "In createOrUpdateEvent <br/>". $event_id . "<BR>";

	global $fm_db;
	
	$ret = NULL;

	$id          = fmQueryValue($event->getId());
	$name        = fmQueryValue($event->getName());
	$location_id = fmQueryValue($event->getLocation_Id());
	$description = fmQueryValue($event->getDescription());
	$start_time  = fmQueryValue($event->getStartTime());
	$end_time    = fmQueryValue($event->getEndTime());
	$program_id  = fmQueryValue($event->getProgram_Id());

	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getEventTableName() . " Set Name='".$name.
		"',Location_Id='".$location_id.
		"',Description='".$description.
		"',StartTime='".$start_time.
		"',EndTime='".$end_time.
		"',Program_Id='".$program_id.
		"' WHERE Event_Id=".$id;

		//echo "Ececuting query: " . $query_string . "<br/>";

		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
		$query_string = "INSERT INTO " . $fm_db->getEventTableName() . " (Name, Location_Id, Description, StartTime, EndTime, Program_Id) VALUES ('".
		$name."','".
		$location_id."','".
		$description."','".
		$start_time."','".
		$end_time."','" .
		$program_id."')";

		//echo "Ececuting query: " . $query_string . "<br/>";

		mysql_query($query_string);

		// now go get the id

		$query_string = "SELECT LAST_INSERT_ID()";
		$result = mysql_query($query_string);

		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			$ret = $row[0];
		}
	}

	return $ret;
}

?>