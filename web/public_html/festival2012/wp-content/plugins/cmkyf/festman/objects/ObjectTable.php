<?php

require_once dirname(__FILE__).'/PersistentObject.php';

class ObjectTable extends PersistentObject
{
	private $ObjectTable_Id = NULL;
	private $ClassName = '';
	private $TableName = '';
	private $IdName = '';
	private $IsDirty = false;
    
	public static function getClassObjectTable()
	{
		ObjectTable::getObjectTableByClassName("ObjectTable");
	}
	
	public static function getObjectTableById($object_table_id)
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getObjectTableTableName() . " WHERE ObjectTable_Id=".fmQueryValue($object_table_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'ObjectTable'))
		{
			return $row;
		}

		return null;
	}

	public static function getObjectTableByTableName($object_table_name)
	{
		global $fm_db;
		
		$query_string = "SELECT * FROM " . $fm_db->getObjectTableTableName() . " WHERE TableName='".fmQueryValue($object_table_name)."'";
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'ObjectTable'))
		{
			return $row;
		}

		return null;
	}
	
	public static function getObjectTableByClassName($object_class_name)
	{
		global $fm_db;
		
		$query_string = "SELECT * FROM " . $fm_db->getObjectTableTableName() . " WHERE ClassName='".fmQueryValue($object_class_name)."'";
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'ObjectTable'))
		{
			return $row;
		}

		return null;
	}
	
	public static function getAllObjectTables()
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getObjectTableTableName();
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'ObjectTable'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
	
	public static function deleteObjectTable($object_table_id)
	{
		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getObjectTableTableName() . " WHERE ObjectTable_Id=".fmQueryValue($object_table_id);

		//echo $query_string . "<br/>";

		mysql_query($query_string);
	}

	public function save()
	{
		$new_id = createOrUpdateObjectTable($this);
		
		if($new_id > 0)
		{
			$this->ObjectTable_Id = $new_id;
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
		return $this->ObjectTable_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->ObjectTable_Id != $value)
		{
			$this->setIsDirty(true);
			$this->ObjectTable_Id = $value;
		}
	}

	public function getClassName()
	{
		return $this->ClassName;
	}

	public function setClassName($value)
	{
		if($this->ClassName != $value)
		{
			$this->setIsDirty(true);
			$this->ClassName = $value;
		}
	}
	
	public function getTableName()
	{
		return $this->TableName;
	}

	public function setTableName($value)
	{
		if($this->TableName != $value)
		{
			$this->setIsDirty(true);
			$this->TableName = $value;
		}
	}

	public function getIdName()
	{
		return $this->IdName;
	}

	public function setIdName($value)
	{
		if($this->IdName != $value)
		{
			$this->setIsDirty(true);
			$this->IdName = $value;
		}
	}

	public function getObjectTable()
	{
		return ObjectTable::getClassObjectTable();
	}
	
	public function getDisplayName()
	{
		return "Object Table";
	}
	
	public function getEditor()
	{
		return NULL;
	}
}

function createOrUpdateObjectTable(ObjectTable $object_table)
{
	global $fm_db;
	
	$ret = 0;

	$id         = fmQueryValue($object_table->getId());
	$class_name = fmQueryValue($object_table->getClassName());
	$table_name = fmQueryValue($object_table->getTableName());
	$id_name    = fmQueryValue($object_table->getIdName());
	
	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getObjectTableTableName() . " Set ClassName='".$class_name."',TableName='".$table_name."',IdName='".$id_name."' WHERE ObjectTable_Id=".$id;
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		$query_string = "INSERT INTO " . $fm_db->getObjectTableTableName() . " (ClassName, TableName, IdName) VALUES ('".$class_name."','".$table_name."','".$id_name."')";
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