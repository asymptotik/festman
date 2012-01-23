<?php
require_once dirname(__FILE__).'/ProgramItem.php';

class Panel extends ProgramItem
{
	public static $CLASS = "Panel";

	function __construct() {
		parent::__construct();
		parent::setObjectClass(self::$CLASS);
	}
	
	public static function getPanel($panel_id)
	{
		return ProgramItem::getTypedProgramItem($panel_id, self::$CLASS);
	}

	public static function getAllPanels()
	{
		return ProgramItem::getAllTypedProgramItems(self::$CLASS);
	}
}

?>