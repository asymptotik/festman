<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/Program_ProgramItem.php';

class Program extends PersistentObject
{
	private $Program_Id = NULL;
	private $Name = '';
	private $Description = '';
	private $Program_ProgramItems = NULL;

	private $IsDirty = false;
	
	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("Program");
	}
	
	public static function getProgram($program_id)
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getProgramTableName() . " WHERE Program_Id=".queryValue($program_id);
		
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'Program'))
		{
			return $row;
		}

		return null;
	}
	
	public static function deleteProgram($program_id)
	{
		global $fm_db;
		
		$quety_string = "UPDATE " . $fm_db->getEventTableName() . " SET Program_Id=NULL WHERE Program_Id=".queryValue($program_id);
		mysql_query($query_string);
		$quety_string = "DELETE FROM " . $fm_db->getProgramTableName() . " WHERE Program_Id=".queryValue($program_id);
		mysql_query($query_string);
	}
	
	public function save()
	{
		$new_id = createOrUpdateProgram($this);
		
		if($new_id > 0)
		{
			if($new_id != $this->ProgramItem_Id)
			{
				$this->Program_Id = $new_id;
				
				if(isset($this->$Program_ProgramItems))
				{
					for($i = 0; $i < count($this->$Program_ProgramItems); $i++)
					{
						$this->$Program_ProgramItems[$i]->setProgram_Id($new_id);
					}
				}
			}
			
			$this->setIsDirty(false);
		}
	}
	
	public function addProgram_ProgramItem($program_program_item)
	{
		if($this->Program_ProgramItems == NULL)
		{
			$this->Program_ProgramItems = array();
		}
		
		$program_program_item->setProgram_Id($this->Program_Id);
		array_push($this->Program_ProgramItems, $program_program_item);
	}

	public function removeProgram_ProgramItem($program_program_item)
	{
	    $this->removeProgram_ProgramItemById($program_program_item->getId());
	}
	
	public function removeProgram_ProgramItemById($program_program_item_id)
	{
	    for($i = 0; $i < count($this->Program_ProgramItems); $i++)
	    {
	        if($this->Program_ProgramItems[$i]->getId() == $program_program_item_id)
	        {
	        	array_splice($this->Program_ProgramItems, $i, 1);
	        	break;
	        }
	    }
	}
	
	public function removeProgram_ProgramItemByIndex($programItemIndex)
	{
		unset($this->Program_ProgramItems[$programItemIndex]);
	    $this->ProgramItems = array_values($this->Program_ProgramItems);
	}
	
	protected function fillProgram_ProgramItems()
	{
		global $fm_db;
		
		if($this->Program_Id != NULL)
		{
			$query_string = "SELECT * FROM " . $fm_db->getProgram_ProgramItemTableName() . " WHERE Program_Id=" . queryValue($this->Program_Id) . " ORDER BY Position";
			$result = mysql_query($query_string);
			$this->Program_ProgramItems = array();
				
			while($row = mysql_fetch_object($result, 'Program_ProgramItem'))
			{
				$this->addProgram_ProgramItem($row);
			}
		}
	}
	
	public function &getProgram_ProgramItems()
	{
		if(isset($this->Program_ProgramItems) == false && $this->Program_Id != NULL)
		{
			$this->fillProgram_ProgramItems();
		}
		return $this->Program_ProgramItems;
	}
	
	public function sortProgram_ProgramItems()
	{
		if($this->Program_ProgramItems != NULL)
		{
			usort($this->Program_ProgramItems, "cmpProgram_ProgramItems");
//			for($i = 0; $i < count($this->Program_ProgramItems); $i++)
//			{
//				$this->Program_ProgramItems[$i]->setPosition($i + 1);
//			}
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
		return $this->Program_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Program_Id != $value)
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

	public function getObjectTable()
	{
		return Program::getClassObjectTable();
	}
	
	public function getDisplayName()
	{
		return "Program" . (strlen($this->getName()) > 0 ? ": " . $this->getName() : "");
	}
	
	public function getEditor()
	{
		return "event_editor.php";
	}
}

//
// Create or update the Program. If the $program_id is less than or equal to 0
// then we insert, otherwise we update. The Program_Id is returned.
//
function createOrUpdateProgram(Program $program)
{
	global $fm_db;
	
	$ret = NULL;
	
	$id          = queryValue($program->getId());
	$name        = queryValue($program->getName());
	$description = queryValue($program->getDescription());
	
	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getProgramTableName() . " SET Name='".$name."',,Description='".$description."' WHERE Act_Id=".$id;
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
        $query_string = "INSERT INTO " . $fm_db->getProgramTableName() . " (Name, Description) VALUES ('".$name."','".$description."')";
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

function cmpProgram_ProgramItems($a, $b)
{
	if($a->getPosition() == $b->getPosition())
	{
		if($a->getProgramItem_Id() == $b->getProgramItem_Id())
		{
			return 0;
		}
		else if($b->getProgramItem_Id() == NULL)
		{ 
			return -1;
		}
		else
		{
			return 1;
		}
	}
	else if($a->getPosition() == '')
	{
		return 1;
	}
	else if($b->getPosition() == '')
	{
		return -1;
	}
	else if($a->getPosition() > $b->getPosition())
	{
		//echo "position " . $a->getPosition() . " > " . $b->getPosition(). "<BR>";
		return 1;
	}
	else
	{
		//echo "position " . $a->getPosition() . " < " . $b->getPosition(). "<BR>";
		return -1;
	}
}
?>