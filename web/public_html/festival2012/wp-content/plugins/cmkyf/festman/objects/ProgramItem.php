<?php

require_once dirname(__FILE__).'/RelatedPerson.php';
require_once dirname(__FILE__).'/ObjectTable.php';
require_once dirname(__FILE__).'/Collateral.php';
require_once dirname(__FILE__).'/Object_Collateral.php';
require_once dirname(__FILE__).'/AbstractCollateralCollection.php';

class ProgramItem extends AbstractCollateralCollection
{
	protected $ProgramItem_Id = NULL;
	protected $Name = '';
	protected $Origin = NULL;
	protected $Description = '';
	protected $Url = '';
	protected $UrlText = NULL;
	protected $ObjectClass = '';
	protected $IsDirty = false;
	protected $RelatedPersons = NULL;

	function __construct() {

	}

	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("ProgramItem");
	}
	
	public static function getTypedProgramItem($program_item_id, $class)
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getProgramItemTableName() . " WHERE ObjectClass=\"".fmQueryValue($class)."\" AND ProgramItem_Id=".fmQueryValue($program_item_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, $class))
		{
			return $row;
		}

		return null;
	}

	public static function getAllTypedProgramItems($class)
	{
		global $fm_db;

		$query_string = "SELECT * FROM " . $fm_db->getProgramItemTableName() . " WHERE ObjectClass=\"".fmQueryValue($class)."\" ORDER BY Name";
		$result = mysql_query($query_string);
		$ret = array();
		
		while($row = mysql_fetch_object($result, $class))
		{
			array_push($ret, $row);
		}

		return $ret;
	}

	private static function queryObjectClass($program_item_id)
	{
		global $fm_db;
		$query_string = "SELECT ObjectClass from " . $fm_db->getProgramItemTableName() . " WHERE ProgramItem_Id=".fmQueryValue($program_item_id);

		$result = mysql_query($query_string);

		while($row = mysql_fetch_row($result))
		{
			return $row[0];
		}

		return '';
	}

	public static function getProgramItem($program_item_id)
	{
		global $fm_db;
		
		$class = ProgramItem::queryObjectClass($program_item_id);
		$query_string = "SELECT * from " . $fm_db->getProgramItemTableName() . " WHERE ProgramItem_Id=\"".fmQueryValue($program_item_id)."\"";
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, $class))
		{
			return $row;
		}

		return NULL;
	}

	//
	// Deletes the ProgramItem and the ProgramItem_Collaterals associated with it.
    //
	public static function deleteProgramItem($program_item_id)
	{
		global $fm_db;
		
		Object_Collateral::deleteAllObject_Collateral(ProgramItem::getClassObjectTable(), $program_item_id);
		ProgramItem::deleteProgramItemRelatedPersons($program_item_id);
		Program_ProgramItem::deleteProgram_ProgramItemByProgramItemId($program_item_id);
		
		$query_string = "DELETE from " . $fm_db->getProgramItemTableName() . " WHERE ProgramItem_Id=".fmQueryValue($program_item_id);
		mysql_query($query_string);
	}
	
	public static function deleteProgramItemRelatedPersons($program_item_id)
	{
		global $fm_db;
		
		$query_string = "SELECT RelatedPerson_Id FROM " . $fm_db->getRelatedPersonTableName() . " WHERE ProgramItem_Id=".fmQueryValue($program_item_id);

	    $result = mysql_query($query_string);

		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			RelatedPerson::deleteRelatedPerson($row['RelatedPerson_Id']);
		}
	}

	public static function getProgramItemSelectOptions($class, $selected_program_item_id)
	{
		global $fm_db;
		
		$query_string = "SELECT Name, ProgramItem_Id from " . $fm_db->getProgramItemTableName() . " WHERE ObjectClass=\"".fmQueryValue($class)."\"";
		$result = mysql_query($query_string);
		$ret = "";

		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			// if($ret != '') $ret .= "\n";
			
			if($selected_program_item_id == $row['ProgramItem_Id'])
			{
				$ret = $ret . "<OPTION value=\"" . $row['ProgramItem_Id'] . "\" selected=\"true\">" . addcslashes($row['Name'], '\'') . "</OPTION>";
			}
			else
			{
				$ret = $ret . "<OPTION value=\"" . $row['ProgramItem_Id'] . "\">" . addcslashes($row['Name'], '\'') . "</OPTION>";
			}
		}

		return $ret;
	}

	public static function getCollateralCount($program_item_id)
	{
		global $fm_db;
		
		return Object_Collateral::getCollateralCount('ProgramItem', $program_item_id);
	}

	public static function getCollateralInstanceCount($collateral_id)
	{
		global $fm_db;
		return Object_Collateral::getCollateralInstanceCount('ProgramItem', $collateral_id);
	}

	public static function getObjectClassDisplayName($object_class)
	{
		$ret = $object_class;
		
		if($object_class == "Klass")
		{
			$ret = "Class";
		}
		
		return $ret;
	}
	
	public function getTypedSelectOptions()
	{
		return ProgramItem::getProgramItemSelectOptions($this->ObjectClass, $this->ProgramItem_Id);
	}

	public function save()
	{
		$new_id = createOrUpdateProgramItem($this);
		
		if($new_id > 0)
		{
			if($new_id != $this->ProgramItem_Id)
			{
				$this->ProgramItem_Id = $new_id;
				
				for($i = 0; $i < count($this->RelatedPersons); $i++)
				{
					$this->RelatedPersons[$i]->setProgramItem_Id($new_id);
				}
			}
			
			$this->setIsDirty(false);
		}
	}
	
	public function addRelatedPerson($related_person)
	{
		if($this->RelatedPersons == NULL)
		{
			$this->RelatedPersons = array();
		}
		
		$related_person->setProgramItem_Id($this->ProgramItem_Id);
		
		array_push($this->RelatedPersons, $related_person);
	}

	public function removeRelatedPerson($related_person)
	{
	    $this->removeRelatedPersonById($related_person->getId());
	}
	
	public function removeRelatedPersonById($related_person_id)
	{
	    for($i = 0; $i < count($this->RelatedPersons); $i++)
	    {
	        if($this->RelatedPersons[$i]->getId() == $related_person_id)
	        {
	        	unset($this->RelatedPersons[$i]);
	        	$this->RelatedPersons = array_values($this->RelatedPersons);
	        	if($this->RelatedPersons == NULL)
	        	{
	        		$this->RelatedPersons = array();
	        	}
	        	break;
	        }
	    }
	}
	
	public function removeRelatedPersonByIndex($related_person_index)
	{
		unset($this->RelatedPersons[$related_person_index]);
	    $this->RelatedPersons = array_values($this->RelatedPersons);
	}
	
  public function replaceRelatedPerson($related_person)
  {
    $related_person_id = $related_person->getId();
    for($i = 0; $i < count($this->RelatedPersons); $i++)
      {
          if($this->RelatedPersons[$i]->getId() == $related_person_id)
          {
            $this->RelatedPersons[$i] = $related_person;
            break;
          }
      }
  }
  
	protected function fillRelatedPersons()
	{
		global $fm_db;
		
		if($this->ProgramItem_Id != NULL)
		{
			$query_string = "SELECT * FROM " . $fm_db->getRelatedPersonTableName() . " WHERE ProgramItem_Id=" . fmQueryValue($this->ProgramItem_Id);
			$result = mysql_query($query_string);
			$this->Program_ProgramItems = array();
				
			while($row = mysql_fetch_object($result, 'RelatedPerson'))
			{
				$this->addRelatedPerson($row);
			}
		}
	}
	
	public function &getRelatedPersons()
	{
		if(isset($this->RelatedPersons) == false && $this->ProgramItem_Id != NULL)
		{
			$this->fillRelatedPersons();
		}
		return $this->RelatedPersons;
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
		return $this->ProgramItem_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}
			
		if($this->ProgramItem_Id != $value)
		{
			$this->setIsDirty(true);

			$this->ProgramItem_Id = $value;
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

	public function getOrigin()
	{
		return $this->Origin;
	}

	public function setOrigin($value)
	{
		if($this->Origin != $value)
		{
			$this->setIsDirty(true);
			$this->Origin = (empty($value) || isset($value) == false ? NULL : $value);
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

	public function getObjectClass()
	{
		return $this->ObjectClass;
	}

	public function setObjectClass($value)
	{
		if($this->ObjectClass != $value)
		{
			$this->setIsDirty(true);
			$this->ObjectClass = $value;
		}
	}

	public function getUrl()
	{
		return $this->Url;
	}

	public function setUrl($value)
	{
		if($this->Url != $value)
		{
			$this->setIsDirty(true);
			$this->Url = $value;
		}
	}
	
	public function getUrlText()
	{
		return $this->UrlText;
	}

	public function setUrlText($value)
	{
		if($this->UrlText != $value)
		{
			$this->setIsDirty(true);
			$this->UrlText = (empty($value) || isset($value) == false ? NULL : $value);
		}
	}
	
	public function getUrlDisplayText()
	{
		if($this->UrlText != NULL)
		{
			$ret = $this->UrlText;
		}
		else
		{
			if(strncasecmp("http://", $this->Url, 7) == 0)
			{
				$ret = substr($this->Url, 7);
				$ret = rtrim($ret, "/");	
			}
			else
			{
				$ret = $this->Url;
			}
		}
		
		return $ret;
	}
	
	public function getCollateralDirectory()
	{
		return strtolower(ProgramItem::getObjectClassDisplayName($this->getObjectClass()));
	}
	
	public function getObjectTable() 
	{
		return ProgramItem::getClassObjectTable();
	}

    public function getDisplayName()
	{
		return $this->getName();
	}

    public function getEditor()
	{
		return "program_item_editor.php";
	}

	public function getEvents()
	{
		return Event::getEventsForProgramItem($this->ProgramItem_Id);
	}
}

//
// Create or update the ProgramItem. If the $program_item_id is less than or equal to 0
// then we insert, otherwise we update. The ProgramItem_Id is returned.
//
function createOrUpdateProgramItem(ProgramItem $program_item)
{
	global $fm_db;
	
	$ret = NULL;

	$id           = fmQueryValue($program_item->getId());
	$name         = fmQueryValue($program_item->getName());
	$url          = fmQueryValue($program_item->getUrl());
	$url_text     = fmQueryValue($program_item->getUrlText());
	$origin       = fmQueryValue($program_item->getOrigin());
	$object_class = fmQueryValue($program_item->getObjectClass());
	$description  = fmQueryValue($program_item->getDescription());
	
	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getProgramItemTableName() . " Set Name='".$name."',Url='".$url."',UrlText='".$url_text."',Origin='".$origin."',ObjectClass='".$object_class."',Description='".$description."' WHERE ProgramItem_Id=".$id;
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
		$query_string = "INSERT INTO " . $fm_db->getProgramItemTableName() . " (Name, Url, UrlText, Origin, ObjectClass, Description) VALUES ('".$name."','".$url."','".$url_text."','".$origin."','".$object_class."','".$description."')";
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