<?php
require_once dirname(__FILE__).'/ProgramItem.php';

class Klass extends ProgramItem
{
	public static $CLASS = "Klass";

	function __construct() {
		parent::__construct();
		parent::setObjectClass(self::$CLASS);
	}
	
	public static function getClass($class_id)
	{
		return ProgramItem::getTypedProgramItem($class_id, self::$CLASS);
	}

	public static function getAllClasss()
	{
		return ProgramItem::getAllTypedProgramItems(self::$CLASS);
	}
}
?>