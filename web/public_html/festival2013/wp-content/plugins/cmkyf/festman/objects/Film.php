<?php

require_once dirname(__FILE__) . '/ProgramItem.php';

class Film extends ProgramItem
{
    public static $CLASS = "Film";

    function __construct()
    {
        parent::__construct();
        parent::setObjectClass(self::$CLASS);
    }

    public static function getFilm($panel_id)
    {
        return ProgramItem::getTypedProgramItem($panel_id, self::$CLASS);
    }

    public static function getAllFilms()
    {
        return ProgramItem::getAllTypedProgramItems(self::$CLASS);
    }
}

?>