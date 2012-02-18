<?php

require_once dirname(__FILE__) . '/PersistentObject.php';
require_once dirname(__FILE__) . '/Location.php';
require_once dirname(__FILE__) . '/MimeType.php';

class Collateral extends PersistentObject
{

    private $Collateral_Id = NULL;
    private $Caption = NULL;
    private $Location = '';
    private $Name = '';
    private $MimeType_Id = NULL;
    private $MimeType = NULL;

    public static function getClassObjectTable()
    {
        return ObjectTable::getObjectTableByClassName("Collateral");
    }

    public static function getCollateral($collateral_id)
    {
        global $fm_db;

        $query_string = "SELECT * from " . $fm_db->getCollateralTableName() . " WHERE Collateral_Id=" . fmQueryValue($collateral_id);
        $result = mysql_query($query_string);

        while ($row = mysql_fetch_object($result, 'Collateral'))
        {
            return $row;
        }

        return null;
    }

    public static function getCollateralByMediaType($media_type)
    {
        global $fm_db;
        $query_string = "SELECT * from " . $fm_db->getCollateralTableName() . " WHERE MedaiType=" . fmQueryValue($media_type);
        $result = mysql_query($query_string);
        $ret = array();

        while ($row = mysql_fetch_object($result, 'Collateral'))
        {
            array_push($ret, $row);
        }

        return $ret;
    }

    public static function getCollateralByCollateralLocation($collateral_location_name)
    {
        global $fm_db;
        if ($collateral_location_name == 'all')
        {
            $query_string = "SELECT * from " . $fm_db->getCollateralTableName() . " ORDER BY Name";
        }
        else
        {
            $query_string = "SELECT * from " . $fm_db->getCollateralTableName() . " WHERE Location IN (SELECT Location FROM CollateralLocation WHERE Name='" . fmQueryValue($collateral_location_name) . "') ORDER BY Name";
        }

        $result = mysql_query($query_string);
        $ret = array();

        while ($row = mysql_fetch_object($result, 'Collateral'))
        {
            array_push($ret, $row);
        }

        return $ret;
    }

    public static function getAllCollateral()
    {
        global $fm_db;
        $query_string = "SELECT * from " . $fm_db->getCollateralTableName();
        $result = mysql_query($query_string);
        $ret = array();

        while ($row = mysql_fetch_object($result, 'Collateral'))
        {
            array_push($ret, $row);
        }

        return $ret;
    }

    public static function deleteCollateral($collateral_id, $url_prefix)
    {
        global $fm_db;
        $query_string = "SELECT Url FROM " . $fm_db->getCollateralTableName() . " WHERE Collateral_Id=" . fmQueryValue($collateral_id);
        $result = mysql_query($query_string);
        $collateral_url = "";

        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
            $collateral_url = $row[0];
        }

        $query_string = "DELETE from " . $fm_db->getCollateralTableName() . " WHERE Collateral_Id=" . $collateral_id;

        //echo $query_string . "<br/>";

        mysql_query($query_string);

        $filename = $url_prefix . $collateral_url;

        //echo $filename . "<br/>";

        if (file_exists($filename) == true)
        {
            unlink($filename);
        }
    }

    public static function isInUse($collateral_id)
    {
        return ($collateral_id != NULL && (Object_Collateral::getAllCollateralInstanceCount($collateral_id) > 0));
    }

    public function save()
    {
        $new_id = createOrUpdateCollateral($this);

        if ($new_id > 0)
        {
            $this->Collateral_Id = $new_id;
            $this->setIsDirty(false);
        }
    }

    public function getId()
    {
        return $this->Collateral_Id;
    }

    public function setId($value)
    {
        if (isset($value) == false || empty($value) == true)
        {
            $value = NULL;
        }

        if ($this->Collateral_Id != $value)
        {
            $this->setIsDirty(true);
            $this->Collateral_Id = $value;
        }
    }

    public function getMimeType_Id()
    {
        return $this->MimeType_Id;
    }

    public function setMimeType_Id($value)
    {
        if (isset($value) == false || empty($value) == true)
        {
            $value = NULL;
        }

        if ($this->MimeType_Id != $value)
        {
            $this->setIsDirty(true);
            $this->MimeType_Id = $value;
        }
    }

    public function getMimeType()
    {
        if ($this->MimeType == NULL && $this->MimeType_Id != NULL)
        {
            $this->MimeType = MimeType::getMimeType($this->MimeType_Id);
        }

        return $this->MimeType;
    }

    public function getCaption()
    {
        return $this->Caption;
    }

    public function setCaption($value)
    {
        if (isset($value) == false || empty($value) == true)
        {
            $value = NULL;
        }

        if ($this->Caption != $value)
        {
            $this->setIsDirty(true);
            $this->Caption = $value;
        }
    }

    public function getLocation()
    {
        return $this->Location;
    }

    public function setLocation($value)
    {
        if ($this->Location != $value)
        {
            $this->setIsDirty(true);
            $this->Location = $value;
        }
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($value)
    {
        if ($this->Name != $value)
        {
            $this->setIsDirty(true);
            $this->Name = $value;
        }
    }

    public function getUrl()
    {
        if (isset($this->Location) == true && empty($this->Location) == false)
        {
            return $this->Location . "/" . $this->Name;
        }
        else
        {
            return $this->Name;
        }
    }

    public function getObjectTable()
    {
        return Collateral::getClassObjectTable();
    }

    public function getDisplayName()
    {
        return $this->getName();
    }

    public function getEditor()
    {
        return "collateral_collection_editor.php";
    }
}

function createOrUpdateCollateral(Collateral $collateral)
{
    global $fm_db;

    $ret = 0;

    $id = fmQueryValue($collateral->getId());
    $mime_type_id = fmQueryValue($collateral->getMimeType_Id());
    $caption = fmQueryValue($collateral->getCaption());
    $location = fmQueryValue($collateral->getLocation());
    $name = fmQueryValue($collateral->getName());

    $caption_value = ($caption == NULL ? "NULL" : "'" . $caption . "'");

    if ($id != NULL)
    {
        // update
        $query_string = "UPDATE " . $fm_db->getCollateralTableName() . " Set MimeType_Id=" . $mime_type_id . ",Caption=" . $caption_value . ",Location='" . $location . "',Name='" . $name . "' WHERE Collateral_Id=" . $id;

        //echo "Query: " . $query_string;

        mysql_query($query_string);
        $ret = $id;
    }
    else
    {
        $query_string = "INSERT INTO " . $fm_db->getCollateralTableName() . " (MimeType_Id, Caption, Location, Name) VALUES ('" . $mime_type_id . "'," . $caption_value . ",'" . $location . "','" . $name . "')";

        //echo "Query: " . $query_string;

        mysql_query($query_string);

        // now go get the id

        $query_string = "SELECT LAST_INSERT_ID()";
        $result = mysql_query($query_string);

        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
            $ret = $row[0];
        }
    }
    return $ret;
}
?>