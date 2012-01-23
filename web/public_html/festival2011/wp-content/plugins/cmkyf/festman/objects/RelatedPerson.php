<?php

require_once dirname(__FILE__).'/Collateral.php';
require_once dirname(__FILE__).'/ObjectTable.php';
require_once dirname(__FILE__).'/AbstractCollateralCollection.php';

class RelatedPerson extends AbstractCollateralCollection
{
	private $RelatedPerson_Id = NULL;
	private $Name = '';
	private $Description = '';
	private $Url = '';
	private $UrlText = NULL;
	private $Role = '';
	private $ProgramItem_Id = NULL;
	private $IsDirty = false;
	
	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("RelatedPerson");
	}
	
	public static function getRelatedPerson($related_person_id)
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getRelatedPersonTableName() . " WHERE RelatedPerson_Id=".queryValue($related_person_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'RelatedPerson'))
		{
			return $row;
		}

		return null;
	}

	public static function deleteRelatedPerson($related_person_id)
	{
		Object_Collateral::deleteAllObject_Collateral(RelatedPerson::getClassObjectTable(), $related_person_id);

		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getRelatedPersonTableName() . " WHERE RelatedPerson_Id=".queryValue($related_person_id);
		mysql_query($query_string);
	}

	public static function getCollateralCount($related_person_id)
	{
		return Object_Collateral::getCollateralCount("RelatedPerson", $related_person_id);
	}

	public static function getCollateralInstanceCount($collateral_id)
	{
		return Object_Collateral::getCollateralInstanceCount("RelatedPerson", $collateral_id);
	}
	
	public function save()
	{
		$new_id = createOrUpdateRelatedPerson($this);
		
		if($new_id > 0)
		{
			$this->RelatedPerson_Id = $new_id;
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
		return $this->RelatedPerson_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}
		
		if($this->RelatedPerson_Id != $value)
		{
			$this->setIsDirty(true);
			$this->RelatedPerson_Id = $value;
		}
	}
	
	public function getObjectTable()
	{
		return RelatedPerson::getClassObjectTable();
	}
	
	public function getCollateralDirectory()
	{
		return "person";
	}
	
	public function getDisplayName()
	{
		return "Related Person" . (strlen($this->getName()) > 0 ? ": " . $this->getName() : "");
	}
	
	public function getEditor()
	{
		return "related_person_editor.php";
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
	
	public function getRole()
	{
		return $this->Role;
	}

	public function setRole($value)
	{
		if($this->Role != $value)
		{
			$this->setIsDirty(true);
			$this->Role = $value;
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
		}
	}
}

//
// Create or update the Location. If the $location_id is less than or equal to 0
// then we insert, otherwise we update. The Location_Id is returned.
//
function createOrUpdateRelatedPerson(RelatedPerson $related_person)
{
	//echo "In createOrUpdateLocation <br/>". $location_id . "<BR>";

	global $fm_db;
	
	$ret = NULL;

	$id              = queryValue($related_person->getId());
	$name            = queryValue($related_person->getName());
	$description     = queryValue($related_person->getDescription());
	$url             = queryValue($related_person->getUrl());
	$url_text        = queryValue($related_person->getUrlText());
	$role            = queryValue($related_person->getRole());
	$program_item_id = queryValue($related_person->getProgramItem_Id());

	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getRelatedPersonTableName() . " Set Name='".$name.
		"',Description='".$description.
		"',Url='".$url.
		"',UrlText='".$url_text.
		"',Role='".$role.
		"',ProgramItem_Id='".$program_item_id.
		"' WHERE RelatedPerson_Id=".$id;

		//echo "Ececuting query: " . $query_string . "<br/>";

		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
		$query_string = "INSERT INTO " . $fm_db->getRelatedPersonTableName() . " (Name, Description, Url, UrlText, Role, ProgramItem_Id) VALUES ('".
		$name."','".
		$description."','".
		$url."','".
		$url_text."','".
		$role."','".
		$program_item_id."')";

		//echo "Executing query: " . $query_string . "<br/>";

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