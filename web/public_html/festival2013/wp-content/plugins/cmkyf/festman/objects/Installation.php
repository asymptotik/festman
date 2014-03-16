<?php

require_once dirname(__FILE__) . '/ProgramItem.php';

class Installation extends ProgramItem
{

    public static $CLASS = "Installation";

    function __construct()
    {
        parent::__construct();
        parent::setObjectClass(self::$CLASS);
    }

    public static function getInstallation($installation_id)
    {
        return ProgramItem::getTypedProgramItem($installation_id, self::$CLASS);
    }

    public static function getAllInstallations()
    {
        return ProgramItem::getAllTypedProgramItems(self::$CLASS);
    }
}

?>