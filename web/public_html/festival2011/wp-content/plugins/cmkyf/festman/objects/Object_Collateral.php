<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/Collateral.php';
require_once dirname(__FILE__).'/Validator.php';
require_once dirname(__FILE__).'/EventContext.php';

class Object_Collateral extends PersistentObject
{
	private $Object_Collateral_Id =NULL;
	private $ObjectTable_Id = NULL;
	private $Object_Id = NULL;
	private $Collateral_Id = NULL;
	private $Collateral = NULL;
	private $SortOrder = NULL;
	private $IsDefault = NULL;

	private $IsDirty = false;
	
	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("Object_Collateral");
	}
	
	public static function getCollateralCount($object_name, $object_id)
	{
		global $fm_db;
		
		$object_table = ObjectTable::getObjectTableByClassName($object_name);
		$query_string = "SELECT COUNT(*) FROM " . $fm_db->getObject_CollateralTableName() . " WHERE ObjectTable_Id=" . queryValue($object_table->getId()) . " AND Object_Id=" . queryValue($object_id);
		$result = mysql_query($query_string);
		
		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			return $row[0];
		}

		return 0;
	}
	
	public static function getCollateralInstanceCount($object_name, $collateral_id)
	{
		global $fm_db;
		
		$object_table = ObjectTable::getObjectTableByClassName($object_name);
		$query_string = "SELECT COUNT(*) FROM " . $fm_db->getObject_CollateralTableName() . " WHERE ObjectTable_Id=".queryValue($object_table->getId())." AND Collateral_Id=" . queryValue($collateral_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			return $row[0];
		}

		return 0;
	}
	
	public static function getAllCollateralInstanceCount($collateral_id)
	{
		global $fm_db;
		
		$query_string = "SELECT COUNT(*) FROM " . $fm_db->getObject_CollateralTableName() . " WHERE Collateral_Id=" . queryValue($collateral_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			return $row[0];
		}

		return 0;
	}
	
	public static function deleteObject_Collateral($object_collateral_id)
	{
		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getObject_CollateralTableName() . " WHERE Object_Collateral_Id=".queryValue($object_collateral_id);
		mysql_query($query_string);
	}
	
	public static function deleteAllObject_Collateral(ObjectTable $object_table, $object_id)
	{
		global $fm_db;
		
		$query_string = "DELETE from " . $fm_db->getObject_CollateralTableName() . " WHERE ObjectTable_Id=".queryValue($object_table->getId())." AND Object_Id=".queryValue($object_id);
		mysql_query($query_string);
	}
	
	public function save()
	{
		$new_id = createOrUpdateObject_Collateral($this);
		
		if($new_id > 0)
		{
			$this->Object_Collateral_Id = $new_id;
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
		return $this->Object_Collateral_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Object_Collateral_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Object_Collateral_Id = $value;
		}
	}
	
	public function getObjectTable_Id()
	{
		return $this->ObjectTable_Id;
	}

	public function setObjectTable_Id($value)
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

	public function getObject_Id()
	{
		return $this->Object_Id;
	}

	public function setObject_Id($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Object_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Object_Id = $value;
		}
	}

	public function getCollateral_Id()
	{
		return $this->Collateral_Id;
	}

	public function setCollateral_Id($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Collateral_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Collateral_Id = $value;
		}
	}
	
	public function getCollateral()
	{
		if($this->Collateral == NULL && $this->Collateral_Id != NULL)
		{
			return $this->Collateral = Collateral::getCollateral($this->Collateral_Id);
		}
		return $this->Collateral;
	}

	public function setCollateral($value)
	{
		if($this->Collateral != $value)
		{
			$this->setIsDirty(true);
			$this->Collateral = $value;
			$this->Collateraln_Id = $value->Collateral_Id;
		}
	}
	
	public function getSortOrder()
	{
		return $this->SortOrder;
	}

	public function setSortOrder($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->SortOrder != $value)
		{
			$this->setIsDirty(true);
			$this->SortOrder = $value;
		}
	}
	
    public function getIsDefault()
	{
		return ($this->IsDefault == true ? true : false);
	}

	public function setIsDefault($value)
	{
		if($value != true)
		{
			$value = false;
		}
		
		if($this->IsDefault != $value)
		{
			$this->setIsDirty(true);
			$this->IsDefault = $value;
		}
	}
	
	public function getObjectTable()
	{
		return Object_Collateral::getClassObjectTable();
	}
	
	public function getDisplayName()
	{
		return "Object Collateral";
	}
	
	public function getEditor()
	{
		return NULL;
	}
}

//
// Create or update the Event. If the $event_id is less than or equal to 0
// then we insert, otherwise we update. The Event_Id is returned.
//
function createOrUpdateObject_Collateral(Object_Collateral $object_collateral)
{
	//echo "In createOrUpdateEvent <BR>";
    global $fm_db;

	$ret = NULL;
	
	$id                      = queryValue($object_collateral->getId());
	$object_table_id         = queryValue($object_collateral->getObjectTable_Id());
	$object_id               = queryValue($object_collateral->getObject_Id());
	$collateral_id           = queryValue($object_collateral->getCollateral_Id());
	$sort_order              = queryValue($object_collateral->getSortOrder());
	$is_default              = queryValue($object_collateral->getIsDefault());

	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getObject_CollateralTableName() . " Set" .
		" ObjectTable_Id=".$object_table_id.
		",Object_Id=".$object_id.
		",Collateral_Id=".$collateral_id.
		",SortOrder=".($sort_order == NULL ? "NULL" : "\"".$sort_order."\"").
		",IsDefault=".($is_default == true ? "1" : "NULL").
		" WHERE Object_Collateral_Id=".$id;

		//echo "Executing query: " . $query_string . "<br>";

		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
		$query_string = "INSERT INTO " . $fm_db->getObject_CollateralTableName() . " (ObjectTable_Id, Object_Id, Collateral_Id, SortOrder, IsDefault) VALUES (".
		$object_table_id.",".
		$object_id.",".
		$collateral_id.",".
		($sort_order == NULL ? "NULL" : "\"".$sort_order."\"").",".
		($is_default == true ? "1" : "NULL").")";

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