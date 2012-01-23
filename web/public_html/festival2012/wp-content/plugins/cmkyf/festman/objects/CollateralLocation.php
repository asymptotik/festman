<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/Location.php';
require_once dirname(__FILE__).'/Event.php';

class CollateralLocation extends PersistentObject
{
	private $CollateralLocation_Id = NULL;
	private $Name = '';
	private $Location = '';
	private $IsDirty = false;
    
	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("CollateralLocation");
	}
	
	public static function getCollateralLocation($collateral_location_id)
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getCollateralLocationTableName() . " WHERE CollateralLocation_Id=".fmQueryValue($collateral_location_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'CollateralLocation'))
		{
			return $row;
		}

		return null;
	}
	
	public static function getAllCollateralLocations()
	{
		global $fm_db;
		$query_string = "SELECT * from " . $fm_db->getCollateralLocationTableName();
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'CollateralLocation'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
	
	public static function deleteCollateralLocation($collateral_location_id)
	{
		global $fm_db;
		$query_string = "SELECT Url FROM " . $fm_db->getCollateralLocationTableName() . " WHERE CollateralLocation_Id=".fmQueryValue($collateral_id);
		$result = mysql_query($query_string);
		$collateral_location_url = "";

		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			$collateral_location_url = $row[0];
		}

		$query_string = "DELETE from " . $fm_db->getCollateralLocationTableName() . " WHERE CollateralLocation_Id=".fmQueryValue($collateral_location_id);

		//echo $query_string . "<br/>";

		mysql_query($query_string);

		$filename = $url_prefix . $collateral_url;

		//echo $filename . "<br/>";

		if(file_exists($filename) == true)
		{
			unlink($filename);
		}
	}

	public function save()
	{
		$new_id = createOrUpdateCollateralLocation($this);
		
		if($new_id > 0)
		{
			$this->CollateralLocation_Id = $new_id;
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
		return $this->CollateralLocation_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Collateral_Id != $value)
		{
			$this->setIsDirty(true);
			$this->CollateralLocation_Id = $value;
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

	public function getLocation()
	{
		return $this->Location;
	}

	public function setLocation($value)
	{
		if($this->Location != $value)
		{
			$this->setIsDirty(true);
			$this->Location = $value;
		}
	}

	public function getObjectTable() 
	{
		return CollateralLocation::getClassObjectTable();
	}

    public function getDisplayName()
	{
		return $this->getName();
	}

    public function getEditor()
	{
		return NULL;
	}
}

function createOrUpdateCollateralLocation(CollateralLocation $collateral_location)
{
	global $fm_db;
	
	$ret = 0;

	$id       = fmQueryValue($collateral->getId());
	$name     = fmQueryValue($collateral->getName());
	$location = fmQueryValue($collateral->geLocation());
	
	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getCollateralLocationTableName() . " Set Name='".$name."',Location='".$location."' WHERE CollateralLocation_Id=".$id;
		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		$query_string = "INSERT INTO " . $fm_db->getCollateralLocationTableName() . " (Name, Location) VALUES ('".$name."','".$location."')";
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