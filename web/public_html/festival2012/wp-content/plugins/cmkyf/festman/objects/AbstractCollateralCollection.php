<?php

require_once dirname(__FILE__).'/PersistentObject.php';
require_once dirname(__FILE__).'/Collateral.php';
require_once dirname(__FILE__).'/Object_Collateral.php';
require_once dirname(__FILE__).'/Messaging.php';

abstract class AbstractCollateralCollection extends PersistentObject
{
	public $Object_Collateral = NULL;
 
  abstract public function getName();
 
	public static function getSessionCollateralCollection($collateral_collection_type)
	{
		$collateral_collection = NULL;

		switch($collateral_collection_type)
		{
			case 'program_item':
				if(isset($_SESSION['current_program_item']))
				{
					$collateral_collection = $_SESSION['current_program_item'];
				}
				break;
			case 'related_person':
				if(isset($_SESSION['current_related_person']))
				{
					$collateral_collection = $_SESSION['current_related_person'];
				}
				break;
			case 'event':
				if(isset($_SESSION['current_event']))
				{
					$collateral_collection = $_SESSION['current_event'];
				}
				break;
			case 'location':
				if(isset($_SESSION['current_location']))
				{
					$collateral_collection = $_SESSION['current_location'];
				}
				break;
		}

		return $collateral_collection;
	}
	
  public static function setSessionCollateralCollection($collateral_collection_type, $collateral_collection)
  {
    switch($collateral_collection_type)
    {
      case 'program_item':
          $_SESSION['current_program_item'] = $collateral_collection;
        break;
      case 'related_person':
          $_SESSION['current_related_person'] = $collateral_collection;
        break;
      case 'event':
          $_SESSION['current_event'] = $collateral_collection;
        break;
      case 'location':
          $_SESSION['current_location'] = $collateral_collection;
        break;
    }
  }

	public function saveAllObject_Collateral(Messaging $messaging)
	{
		//
		// Brute force, find all the object_collateral that are related to this
		// object and not in this collection ans delete them.
		//
		global $fm_db;
		$object_table = $this->getObjectTable();
		$object_collateral = &$this->getAllObject_Collateral();

		$query_string = "SELECT Object_Collateral_Id FROM " . $fm_db->getObject_CollateralTableName() . " WHERE ObjectTable_Id=". 
		fmQueryValue($object_table->getId()) . " AND Object_Id=". fmQueryValue($this->getId());
		
		$result = mysql_query($query_string);
		$delete_object_collateral_ids = array();

		while($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			$messaging->addActionMessage("Fetched ID: " . $row[0]);
			$found = false;
			for($i = 0; $i < count($object_collateral); $i++)
			{
				if($object_collateral[$i]->getId() == $row[0])
				{
					$found = true;
				}
			}
			if($found == false)
			{
				array_push($delete_object_collateral_ids, $row[0]);
			}
		}
			
		for($i = 0; $i < count($delete_object_collateral_ids); $i++)
		{
			Object_Collateral::deleteObject_Collateral($delete_object_collateral_ids[$i]);
			$messaging->addActionMessage("Deleted Object Collateral " . $delete_object_collateral_ids[$i]);
		}
			
		//
		// Now save the object_collateral that are in this collection.
		//
		for($i = 0; $i < count($object_collateral); $i++)
		{
			$save_object_collateral = $object_collateral[$i];
			$save_object_collateral->setObject_Id($this->getId());
			$save_object_collateral->save();

			$messaging->addActionMessage("Saved Object Collateral ". $save_object_collateral->getCollateral()->getName() . ".");
		}
	}

	public function removeCollateral(Collateral $collateral)
	{
		$this->removeCollateralById($collateral->Collateral_Id);
	}

	public function removeCollateralById($collateral_id)
	{
		for($i = 0; $i < count($this->Object_Collateral); $i++)
		{
			if($this->Object_Collateral[$i]->getCollateral_Id() == $collateral_id)
			{
				$this->removeObject_CollateralByIndex($i);
				break;
			}
		}
	}

	public function getAllCollateral()
	{
		$object_collateral = &$this->getAllObject_Collateral();

		$ret = array();

		for($i = 0; $i < count($object_collateral); $i++)
		{
			array_push($ret, $object_collateral[$i]->getCollateral());
		}

		return $ret;
	}

	public function getDefaultImage()
	{
		$object_collateral = &$this->getAllObject_Collateral();

		$ret = NULL;
		
		for($i = 0; $i < count($object_collateral); $i++)
		{
			$collateral = $object_collateral[$i]->getCollateral();
		    $mime_type = $collateral->getMimeType();
		   
		    if($mime_type != NULL && $mime_type->getIsImage() == true)
		    {
		    	if($object_collateral[$i]->getIsDefault() == true || $ret == NULL)
		    	{
		    		$ret = $collateral;
		    	}
		    }
		}

		return $ret;
	}
	
	public function getAllAudio()
	{
		$object_collateral = &$this->getAllObject_Collateral();

		$ret = array();
		
		for($i = 0; $i < count($object_collateral); $i++)
		{
			$collateral = $object_collateral[$i]->getCollateral();
		    $mime_type = $collateral->getMimeType();
		   
		    if($mime_type->getIsAudio() == true)
		    {
		    	array_push($ret, $object_collateral[$i]->getCollateral());
		    	
		    }
		}

		return $ret;
	}
	
	public function addCollateral(Collateral $collateral)
	{
		if($this->Object_Collateral == NULL)
		{
			$this->Object_Collateral = array();
		}

		addCollateralById($collateral->getId());
	}

	public function addCollateralById($collateral_id)
	{
		if($this->Object_Collateral == NULL)
		{
			$this->Object_Collateral = array();
		}

		$object_table = $this->getObjectTable();

		$object_collateral = new Object_Collateral();
		$object_collateral->setCollateral_Id($collateral_id);
		$object_collateral->setObject_Id($this->getId());
		$object_collateral->setObjectTable_Id($object_table->getid());

		array_push($this->Object_Collateral, $object_collateral);

		return $object_collateral;
	}

	public function removeObject_Collateral(Object_Collateral $object_collateral)
	{
		$this->removeObject_CollateralById($object_collateral->getId());
	}

	public function removeObject_CollateralById($object_collateral_id)
	{
		for($i = 0; $i < count($this->Object_Collateral); $i++)
		{
			if($this->Object_Collateral[$i]->getId() == $object_collateral_id)
			{
				$this->removeObject_CollateralByIndex($i);
				break;
			}
		}
	}

	public function removeObject_CollateralByIndex($object_collateral_index)
	{
		unset($this->Object_Collateral[$object_collateral_index]);
		$this->Object_Collateral = array_values($this->Object_Collateral);
	}

	public function &getAllObject_Collateral()
	{
		if(isset($this->Object_Collateral) == false)
		{
			$this->fillObject_Collateral();
		}

		return $this->Object_Collateral;
	}

	public function addObject_Collateral(Object_Collateral $object_collateral)
	{
		if(isset($this->Object_Collateral) == false)
		{
			$this->Object_Collateral = array();
		}

		array_push($this->Object_Collateral, $object_collateral);
	}

	public function addAndInsertCollateralById($collateral_id)
	{
		$object_collateral = addCollateralById($collateral_id);
		$object_collateral->save();
	}

	public function addAndInsertCollateral(Collateral $collateral)
	{
		$this->addAndInsertCollateralById($collateral->getId());
	}

	public function getObject_CollateralByCollateralId($collateral_id)
	{
		$object_collateral = &$this->getAllObject_Collateral();
		for($i = 0; $i < count($object_collateral); $i++)
		{
			$collateral = $object_collateral[$i]->getCollateral();
			if($collateral->getId() == $collateral_id)
			{
				return $object_collateral[$i];
			}
		}

		return NULL;
	}

	protected function fillObject_Collateral()
	{
		global $fm_db;
		$object_table = $this->getObjectTable();
		$id = $this->getId();

		if($id != NULL)
		{
			$query_string = "SELECT * FROM " . $fm_db->getObject_CollateralTableName() . " WHERE ObjectTable_Id=". 
				fmQueryValue($object_table->getId()) . " AND Object_Id=". fmQueryValue($id);
		
			$this->Object_Collateral = array();
			$result = mysql_query($query_string);
				
			while($row = mysql_fetch_object($result, 'Object_Collateral'))
			{
				array_push($this->Object_Collateral, $row);
			}
		}
	}

	public function sortObject_Collateral()
	{
		if($this->Object_Collateral != NULL)
		{
			usort($this->Object_Collateral, "cmpObject_Collateral");
			//			for($i = 0; $i < count($this->Program_ProgramItems); $i++)
			//			{
			//				$this->Program_ProgramItems[$i]->setPosition($i + 1);
			//			}
		}
	}

	abstract public function getCollateralDirectory();
}

function cmpObject_Collateral($a, $b)
{
	if($a->getSortOrder() == $b->getSortOrder())
	{
		if($a->getCollateral_Id() == $b->getCollateral_Id())
		{
			return 0;
		}
		else if($b->getCollateral_Id() == NULL)
		{
			return -1;
		}
		else
		{
			return 1;
		}
	}
	else if($a->getSortOrder() == '')
	{
		return 1;
	}
	else if($b->getSortOrder() == '')
	{
		return -1;
	}
	else if($a->getSortOrder() > $b->getSortOrder())
	{
		//echo "position " . $a->getPosition() . " > " . $b->getPosition(). "<BR>";
		return 1;
	}
	else
	{
		//echo "position " . $a->getPosition() . " < " . $b->getPosition(). "<BR>";
		return -1;
	}
}
?>