<?php
abstract class PersistentObject
{
	private $DoDelete = false;
	
	public function getDoDelete()
	{
		return $this->DoDelete;
	}
	
	public function setDoDelete($value)
	{
		$this->DoDelete = $value;
	}
	
	abstract public function getId();
	
	abstract public function getObjectTable();

	abstract public function getDisplayName();

	abstract public function getEditor();
}
?>