<?php

class FestmanDb
{

    private $prefix = "fm_";

    function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public static function getInstance($prefix)
    {
        $ret = NULL;

        if (isset($GLOBALS["FestmanDb"]) == false)
        {
            $ret = new FestmanDb($prefix);
            $GLOBALS["FestmanDb"] = $ret;
        }
        else
        {
            $ret = $GLOBALS["FestmanDb"];
        }

        return $ret;
    }

    public function getEventTableName()
    {
        return $this->prefix . "event";
    }

    public function getCollateralTableName()
    {
        return $this->prefix . "collateral";
    }

    public function getLocationTableName()
    {
        return $this->prefix . "location";
    }

    public function getCollateralLocationTableName()
    {
        return $this->prefix . "collaterallocation";
    }

    public function getObject_CollateralTableName()
    {
        return $this->prefix . "object_collateral";
    }

    public function getProgramTableName()
    {
        return $this->prefix . "program";
    }

    public function getProgramItemTableName()
    {
        return $this->prefix . "programitem";
    }

    public function getProgram_ProgramItemTableName()
    {
        return $this->prefix . "program_programitem";
    }

    public function getRelatedPersonTableName()
    {
        return $this->prefix . "relatedperson";
    }

    public function getMimeTypeTableName()
    {
        return $this->prefix . "mimetype";
    }

    public function getFileExtensionTableName()
    {
        return $this->prefix . "fileextension";
    }

    public function getObjectTableTableName()
    {
        return $this->prefix . "objecttable";
    }
}

?>