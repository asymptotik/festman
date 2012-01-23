<?php
require_once dirname(__FILE__).'/ProgramItem.php';

class Workshop extends ProgramItem
{
	public static $CLASS = "Workshop";

	function __construct() {
		parent::__construct();
		parent::setObjectClass(self::$CLASS);
	}
	
	public static function getWorkshop($workshop_id)
	{
		return ProgramItem::getTypedProgramItem($workshop_id, self::$CLASS);
	}

	public static function getAllWorkshops()
	{
		return ProgramItem::getAllTypedProgramItems(self::$CLASS);
	}

}

?>