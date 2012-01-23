<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/MimeType.php';

class FileExtension extends PersistentObject
{
	private $FileExtension_Id = NULL;
	private $Extension = NULL;
	private $MimeType_Id = NULL;
	private $IsDirty = false;
    
	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("FileExtension");
	}
	
	public static function getFileExtension($file_extension_id)
	{
		global $fm_db;
		$query_string = "SELECT * FROM " . $fm_db->getFileExtensionTableName() . " WHERE FileExtension_Id=".queryValue($file_extension_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'FileExtension'))
		{
			return $row;
		}

		return null;
	}
	
    public static function getFileExtensionByExtention($extension)
	{
		global $fm_db;
		$query_string = "SELECT * FROM " . $fm_db->getFileExtensionTableName() . " WHERE Extension=".getQuotedStringOrNull(queryValue($extension));
		
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'FileExtension'))
		{
			return $row;
		}

		return null;
	}
	
	public static function getAllFileExtensions()
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getFileExtensionTableName();
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'FileExtension'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
	
	public static function deleteFileExtension($file_extension_id)
	{
		global $fm_db;
		$query_string = "DELETE from " . $fm_db->getFileExtensionTableName() . " WHERE FileExtension_Id=".queryValue($file_extension_id);

		//echo $query_string . "<br/>";

		mysql_query($query_string);
	}

	
	public function save()
	{
		$new_id = createOrUpdateFileExtension($this);
		
		if($new_id > 0)
		{
			$this->FileExtension_Id = $new_id;
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
		return $this->FileExtension_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->FileExtension_Id != $value)
		{
			$this->setIsDirty(true);
			$this->FileExtension_Id = $value;
		}
	}
	
	public function getExtension()
	{
		return $this->Extension;
	}

	public function setExtension($value)
	{
		if($this->Extension != $value)
		{
			$this->setIsDirty(true);
			$this->Extension = $value;
		}
	}
	
	public function getMimeType_Id()
	{
		return $this->MimeType_Id;
	}

	public function setMimeType_Id($value)
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

	public function getObjectTable() 
	{
		return FileExtension::getClassObjectTable();
	}

    public function getDisplayName()
	{
		return $this->getExtension();
	}

    public function getEditor()
	{
		return NULL;
	}
}

function createOrUpdateFileExtension(FileExtension $file_extension)
{
	global $fm_db;
	
	$ret = 0;

	$id           = queryValue($collateral->getId());
	$extension    = queryValue($collateral->getExtension());
	$mime_type_id = queryValue($collateral->getMimeType());
	
	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getFileExtensionTableName() . " Set MimeType_Id=".$mime_type_id.",Extension='".$extension."' WHERE FileExtension_Id=".$id;
		
		//echo "Query: " . $query_string;
		
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		$query_string = "INSERT INTO " . $fm_db->getFileExtensionTableName() . " (MimeType_Id, Extension) VALUES ('".$mime_type_id."','".$extension."')";
		
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