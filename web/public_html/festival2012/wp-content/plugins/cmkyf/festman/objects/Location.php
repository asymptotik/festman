<?php

require_once dirname(__FILE__).'/Collateral.php';
require_once dirname(__FILE__).'/ObjectTable.php';
require_once dirname(__FILE__).'/AbstractCollateralCollection.php';

class Location extends AbstractCollateralCollection
{
	private $Location_Id = NULL;
	private $Name = '';
	private $Description = '';
	private $Url = '';
	private $UrlText = NULL;
	private $MapUrl = NULL;
	private $MapUrlText = NULL;
	private $Address = '';
	private $City = '';
	private $State = '';
	private $ZipCode = '';
	private $PhoneNumber = '';
	private $IsDirty = false;

	public static function getClassObjectTable()
	{
		return ObjectTable::getObjectTableByClassName("Location");
	}
	
	public static function getLocation($location_id)
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getLocationTableName() . " WHERE Location_Id=".fmQueryValue($location_id);
		$result = mysql_query($query_string);

		while($row = mysql_fetch_object($result, 'Location'))
		{
			return $row;
		}

		return null;
	}

	public static function getAllLocations()
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getLocationTableName();
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'Location'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
	
	public static function deleteLocation($location_id)
	{
		global $fm_db;
		
		Object_Collateral::deleteAllObject_Collateral(Location::getClassObjectTable(), $location_id);

		$query_string = "DELETE from " . $fm_db->getLocationTableName() . " WHERE Location_Id=".fmQueryValue($location_id);
		mysql_query($query_string);
		$quety_string = "UPDATE " . $fm_db->getEventTableName() . " SET Location_Id=NULL WHERE Location_Id=".fmQueryValue($location_id);
		mysql_query($query_string);
	}

	public static function getLocationSelectOptions($selected_location_id)
	{
		global $fm_db;
		
		$query_string = "SELECT Name, Location_Id from " . $fm_db->getLocationTableName();
		$result = mysql_query($query_string);
		$has_selected = false;
		$ret = '';
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if($selected_location_id == $row['Location_Id'])
			{
				$ret = $ret . "<OPTION value=\"" . $row['Location_Id'] . "\" selected=\"true\">" . $row['Name'] . "</OPTION>\n";
				$has_selected = true;
			}
			else
			{
				$ret = $ret . "<OPTION value=\"" . $row['Location_Id'] . "\">" . $row['Name'] . "</OPTION>\n";
			}
		}

		if($has_selected == false)
		{
			$ret = "<OPTION value=\"\" selected=\"true\">&lt;Select Location&gt;</OPTION>\n" . $ret;
		}
		
		return $ret;
	}

	public static function getCollateralCount($location_id)
	{
		global $fm_db;
		
		return Object_Collateral::getCollateralCount('Location', $location_id);
	}

	public static function getCollateralInstanceCount($collateral_id)
	{
		global $fm_db;
		
		Object_Collateral::getCollateralInstanceCount('Location', $collateral_id);
	}

	public function save()
	{
		$new_id = createOrUpdateLocation($this);
		
		if($new_id > 0)
		{
			$this->Location_Id = $new_id;
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
		return $this->Location_Id;
	}

	public function setId($value)
	{
		if(isset($value) == false || empty($value) == true)
		{
			$value = NULL;
		}

		if($this->Location_Id != $value)
		{
			$this->setIsDirty(true);
			$this->Location_Id = $value;
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
	
	public function getMapUrl()
	{
		return $this->MapUrl;
	}

	public function setMapUrl($value)
	{
		if($this->MapUrl != $value)
		{
			$this->setIsDirty(true);
			$this->MapUrl = $value;
		}
	}
	
	public function getMapUrlText()
	{
		return $this->MapUrlText;
	}

	public function setMapUrlText($value)
	{
		if($this->MapUrlText != $value)
		{
			$this->setIsDirty(true);
			$this->MapUrlText = (empty($value) || isset($value) == false ? NULL : $value);
		}
	}
	
	public function getMapUrlDisplayText()
	{
		if($this->MapUrlText != NULL)
		{
			$ret = $this->MapUrlText;
		}
		else
		{
			if(strncasecmp("http://", $this->MapUrl, 7) == 0)
			{
				$ret = substr($this->MapUrl, 7);
				$ret = rtrim($ret, "/");	
			}
			else
			{
				$ret = $this->MapUrl;
			}
		}
		
		return $ret;
	}
	
	public function getAddress()
	{
		return $this->Address;
	}

	public function setAddress($value)
	{
		if($this->Address != $value)
		{
			$this->setIsDirty(true);
			$this->Address = $value;
		}
	}

	public function getCity()
	{
		return $this->City;
	}

	public function setCity($value)
	{
		if($this->City != $value)
		{
			$this->setIsDirty(true);
			$this->City = $value;
		}
	}

	public function getState()
	{
		return $this->State;
	}

	public function setState($value)
	{
		if($this->State != $value)
		{
			$this->setIsDirty(true);
			$this->State = $value;
		}
	}

	public function getZipCode()
	{
		return $this->ZipCode;
	}

	public function setZipCode($value)
	{
		if($this->ZipCode != $value)
		{
			$this->setIsDirty(true);
			$this->ZipCode = $value;
		}
	}
	
	public function getPhoneNumber()
	{
		return $this->PhoneNumber;
	}

	public function setPhoneNumber($value)
	{
		if($this->PhoneNumber != $value)
		{
			$this->setIsDirty(true);
			$this->PhoneNumber = $value;
		}
	}
	
	public function getObjectTable()
	{
		return Location::getClassObjectTable();
	}
	
	public function getCollateralDirectory()
	{
		return "location";
	}
	
	public function getDisplayName()
	{
		return "Location" . (strlen($this->getName()) > 0 ? ": " . $this->getName() : "");
	}
	
	public function getEditor()
	{
		return "location_editor.php";
	}

	public function getAllEvents()
	{
		global $fm_db;
		
		$query_string = "SELECT * from " . $fm_db->getEventTableName() . " WHERE Location_Id = ".fmQueryValue($this->getId()) . " ORDER BY StartTime";
		$result = mysql_query($query_string);
		$ret = array();

		while($row = mysql_fetch_object($result, 'Event'))
		{
			array_push($ret, $row);
		}

		return $ret;
	}
}

//
// Create or update the Location. If the $location_id is less than or equal to 0
// then we insert, otherwise we update. The Location_Id is returned.
//
function createOrUpdateLocation(Location $location)
{
	//echo "In createOrUpdateLocation <br/>". $location_id . "<BR>";

	global $fm_db;
	
	$ret = NULL;

	$id           = fmQueryValue($location->getId());
	$name         = fmQueryValue($location->getName());
	$url          = fmQueryValue($location->getUrl());
	$url_text     = fmQueryValue($location->getUrlText());
	$map_url      = fmQueryValue($location->getMapUrl());
	$map_url_text = fmQueryValue($location->getMapUrlText());
	$address      = fmQueryValue($location->getAddress());
	$city         = fmQueryValue($location->getCity());
	$state        = fmQueryValue($location->getState());
	$zip_code     = fmQueryValue($location->getZipCode());
	$description  = fmQueryValue($location->getDescription());

	if($id != NULL)
	{
		// update
		$query_string = "UPDATE " . $fm_db->getLocationTableName() . " Set Name='".$name.
		"',Url='".$url.
		"',UrlText='".$url_text.
		"',MapUrl='".$map_url.
		"',MapUrlText='".$map_url_text.
		"',Address='".$address.
		"',City='".$city.
		"',State='".$state.
		"',ZipCode='".$zip_code.
		"',Description='".$description.
		"' WHERE Location_Id=".$id;

		//echo "Executing query: " . $query_string . "<br/>";

		mysql_query($query_string);
		$ret = $id;
	}
	else
	{
		// insert
		$query_string = "INSERT INTO " . $fm_db->getLocationTableName() . " (Name, Url, UrlText, MapUrl, MapUrlText, Address, City, State, ZipCode, Description) VALUES ('".
		$name."','".
		$url."','".
		$url_text."','".
		$map_url."','".
		$map_url_text."','".
		$address."','".
		$city."','".
		$state."','".
		$zip_code."','".
		$description."')";

		// echo "Executing query: " . $query_string . "<br/>";

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