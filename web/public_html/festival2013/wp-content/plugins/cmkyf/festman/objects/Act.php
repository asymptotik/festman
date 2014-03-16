<?php
require_once dirname(__FILE__).'/ProgramItem.php';

class Act extends ProgramItem
{
	public static $CLASS = "Act";

	function __construct() {
		parent::__construct();
		parent::setObjectClass(self::$CLASS);
	}
	
	public static function getAct($act_id)
	{
		return ProgramItem::getTypedProgramItem($act_id, self::$CLASS);
	}

	public static function getAllActs()
	{
		return ProgramItem::getAllTypedProgramItems(self::$CLASS);
	}
}

?>