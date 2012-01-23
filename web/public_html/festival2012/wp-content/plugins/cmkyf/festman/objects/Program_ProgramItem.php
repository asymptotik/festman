<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/Program.php';
require_once dirname(__FILE__).'/ProgramItem.php';
require_once dirname(__FILE__).'/Validator.php';
require_once dirname(__FILE__).'/Utils.php';

class Program_ProgramItem extends PersistentObject
{
	private $Program_ProgramItem_Id = '';
	private $Position = '';
	private $StartTime = '';
	private $Program_Id = NULL;
	private $ProgramItem_Id = NULL;
	private $ProgramItem = NULL;
	private $IsDirty = false;

	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("Program_ProgramItem");
	}
	
	public static function deleteProgram_ProgramItem($ppi_id)
	{
		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getProgram_ProgramItemTableName() . " WHERE Program_ProgramItem_Id=".fmQueryValue($ppi_id);
		mysql_query($query_string);
	}
	
	public static function deleteProgram_ProgramItemByProgramItemId($program_item_id)
	{
		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getProgram_ProgramItemTableName() . " WHERE ProgramItem_Id=".fmQueryValue($program_item_id);
		mysql_query($query_string);
	}
	
	public function validate()
	{
		$error_message = '';
		
		$start_time_validator = new Validator();
		$start_time_validator->addValidator(new DateTimeValidator("Start Time should have a format of yyyy/mm/dd hh:mm."));
		$error_message = fmAppendLine($error_message, $start_time_validator->validate($this->StartTime));
		
		$program_id_validator = new Validator();
		$program_id_validator->addValidator(new RequiredValidator("A Program is required."));
		$error_message = fmAppendLine($error_message, $program_id_validator->validate($this->Program_Id));
		
		$program_item_validator = new Validator();
		$program_item_validator->addValidator(new RequiredValidator("A Program Item is required."));
		$error_message = fmAppendLine($error_message, $program_item_validator->validate($this->ProgramItem_Id));

//		echo "<br><br>StartTime: " . $this->StartTime . "<br>";
//		echo "Program_Id: " . $this->Program_Id . "<br>";
//		echo "ProgramItem_Id: " . $this->ProgramItem_Id . "<br>";
//		echo "error_message: " . $error_message . "<br>";
		
		return $error_message;
	}
	
	public function isBlank()
	{
	    return ((strlen($this->Position) > 0 || strlen($this->StartTime) > 0 || $this->ProgramItem_Id != NULL) == false);
	}
	
	public function save()
	{
		$new_id = createOrUpdateProgram_ProgramItem($this);
		
		if($new_id > 0)
		{
			$this->Program_ProgramItem_Id = $new_id;
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
		return $this->Program_ProgramItem_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Program_ProgramItem_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Program_ProgramItem_Id = $value;
		}
	}

	public function getPosition()
	{
		return $this->Position;
	}

	public function setPosition($value)
	{
		if($this->Position != $value)
		{
			$this->setIsDirty(true);
			$this->Position = $value;
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
		return $this->Program;
	}

	public function setProgram($value)
	{
		if($this->Program != $value)
		{
			$this->setIsDirty(true);
			$this->Program = $value;
		}
	}

	public function getProgramItem_Id()
	{
		return $this->ProgramItem_Id;
	}

	public function setProgramItem_Id($value)
	{
		if($this->ProgramItem_Id != $value)
		{
			$this->setIsDirty(true);
			$this->ProgramItem_Id = $value;
			unset($this->ProgramItem);
		}
	}

	public function getProgramItem()
	{
		if($this->ProgramItem == NULL && $this->ProgramItem_Id != NULL)
		{
			$this->ProgramItem = ProgramItem::getProgramItem($this->ProgramItem_Id);
		}
		
		return $this->ProgramItem;
	}

	public function setProgramItem($value)
	{
		if($this->ProgramItem != $value)
		{
			$this->setIsDirty(true);
			$this->ProgramItem = $value;
			$this->ProgramItem_Id = $value->getId();
		}
	}

	public function getObjectTable()
	{
		return Program_ProgramItem::getClassObjectTable();
	}
	
	public function getDisplayName()
	{
		return "Program ProgramItem";
	}
	
	public function getEditor()
	{
		return "program_item_editor.php";
	}
}

//
// Create or update the Event. If the $event_id is less than or equal to 0
// then we insert, otherwise we update. The Event_Id is returned.
//
function createOrUpdateProgram_ProgramItem(Program_ProgramItem $ppi)
{
	//echo "In createOrUpdateEvent ". $ppi_id . "<BR>";

	global $fm_db;
	
	$ret = NULL;
	
	$id              = fmQueryValue($ppi->getId());
	$position        = fmQueryValue($ppi->getPosition());
	$start_time      = fmQueryValue($ppi->getStartTime());
	$program_id      = fmQueryValue($ppi->getProgram_Id());
	$program_item_id = fmQueryValue($ppi->getProgramItem_Id());

	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getProgram_ProgramItemTableName() . " Set" .
		" Position=".fmGetQuotedStringOrNull($position).
		",StartTime=".fmGetQuotedStringOrNull($start_time).
		",Program_Id='".$program_id.
		"',ProgramItem_Id='".$program_item_id.
		"' WHERE Program_ProgramItem_Id=".$id;

		//echo "Executing query: " . $query_string . "<br>";
		
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
		$query_string = "INSERT INTO " . $fm_db->getProgram_ProgramItemTableName() . " (Position, StartTime, Program_Id, ProgramItem_Id) VALUES (".
		fmGetQuotedStringOrNull($position).",".
		fmGetQuotedStringOrNull($start_time).",'".
		$program_id."','".
		$program_item_id."')";

		//echo "Executing query: " . $query_string . "<br>";
		
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