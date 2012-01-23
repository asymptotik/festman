<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/Location.php';
require_once dirname(__FILE__).'/ProgramItem.php';
require_once dirname(__FILE__).'/Event.php';
require_once dirname(__FILE__).'/RelatedPerson.php';

class MimeType extends PersistentObject
{
	private $MimeType_Id = NULL;
	private $Type = '';
	private $SubType = '';
	private $Description = NULL;
	private $IsDirty = false;
    
	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("MimeType");
	}

	public static function getMimeType($mime_type_id)
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getMimeTypeTableName() . " WHERE MimeType_Id=".queryValue($mime_type_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'MimeType'))
		{
			return $row;
		}

		return NULL;
	}
	
	public static function deleteMimeType($mime_type_id)
	{
		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getMimeTypeTableName() . " WHERE MimeType_Id=".$mime_type_id;

		//echo $query_string . "<br/>";

		mysql_query($query_string);
	}
	
	public function save()
	{
		$new_id = createOrUpdateMimeType($this);
		
		if($new_id > 0)
		{
			$this->MimeType_Id = $new_id;
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
		return $this->Collateral_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->MimeType_Id != $value)
		{
			$this->setIsDirty(true);
			$this->MimeType_Id = $value;
		}
	}

	public function getType()
	{
		return $this->Type;
	}

	public function setType($value)
	{
		if($this->Type != $value)
		{
			$this->setIsDirty(true);
			$this->Type = $value;
		}
	}

	public function getSubType()
	{
		return $this->SubType;
	}

	public function setSubType($value)
	{
		if($this->SubType != $value)
		{
			$this->setIsDirty(true);
			$this->SubType = $value;
		}
	}
	
	public function getDescription()
	{
		return $this->Description;
	}

	public function setDescription($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}
		
		if($this->Description != $value)
		{
			$this->setIsDirty(true);
			$this->Description = $value;
		}
	}

	public function getObjectTable() 
	{
		return MimeType::getClassObjectTable();
	}

    public function getDisplayName()
	{
		return $this->getType()."/".$this->getSubType();
	}

	public function getIsImage()
	{
		if($this->Type == "image")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getIsAudio()
	{
		if($this->Type == "audio")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
    public function getEditor()
	{
		return NULL;
	}
}

function createOrUpdateMimeType(MimeType $mime_type)
{
	$ret = 0;

	global $fm_db;
	
	$id          = queryValue($mime_type->getId());
	$type        = queryValue($mime_type->getType());
	$sub_type    = queryValue($mime_type->getSubType());
	$description = queryValue($mime_type->getDescription());
	
	$description_value = ($description == NULL ? "NULL" : "'". $description .",");
	
	if($id != NULL)
	{
		// update
		
		$query_string = "UPDATE" . $fm_db->getMimeTypeTableName() . " Set Type='".$type."',SubType='".$sub_type."',Description=".$description_value." WHERE MimeType_Id=".$id;
		
		//echo "Query: " . $query_string;
		
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		$query_string = "INSERT INTO" . $fm_db->getMimeTypeTableName() . " (Type, SubType, Description) VALUES ('".$type."','".$sub_type."',".$description_value.")";
		
		//echo "Query: " . $query_string;
				
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