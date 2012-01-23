<?php

function storeProgramItem()
{
	if(isset($_SESSION['current_program_item']) == false)
	{
		$_SESSION['current_program_item'] = new ProgramItem();
	}

	$program_item = $_SESSION['current_program_item'];
	$program_item->setObjectClass(getPost($_POST['program_item_class']));
	//$program_item->setId(getPost($_POST['program_item_id']));
	$program_item->setName(getPost($_POST['program_item_name']));
	$program_item->setOrigin(getPost($_POST['program_item_origin']));
	$program_item->setUrl(getPost($_POST['program_item_url']));
	$program_item->setUrlText(getPost($_POST['program_item_url_text']));
	$program_item->setDescription(getPost($_POST['program_item_description']));

	return $program_item;
}

function storeRelatedPerson()
{
	if(isset($_SESSION['current_related_person']) == false)
	{
		$_SESSION['current_related_person'] = new RelatedPerson();
	}

	$related_person = $_SESSION['current_related_person'];
	//$related_person->setId(getPost($_POST['related_person_id']));
	$related_person->setName(getPost($_POST['related_person_name']));
	$related_person->setUrl(getPost($_POST['related_person_url']));
	$related_person->setUrlText(getPost($_POST['related_person_url_text']));
	$related_person->setRole(getPost($_POST['related_person_role']));
	$related_person->setDescription(getPost($_POST['related_person_description']));

	return $related_person;
}

function storeEvent()
{
	if(isset($_SESSION['current_event']) == false)
	{
		$_SESSION['current_event'] = new Event();
	}

	$event = $_SESSION['current_event'];
	//$event->setId(getPost($_POST['event_id']));
	$event->setName(getPost($_POST['event_name']));
	$event->setLocation_Id(getPost($_POST['event_location_id']));
	$event->setDescription(getPost($_POST['event_description']));
	$event->setStartTimeString(getPost($_POST['event_start_time']));
	$event->setEndTimeString(getPost($_POST['event_end_time']));
	
	return $event;
}

//
// Fired from the event_editor.php page.
//
function storeCompleteEvent(EventContext $context)
{
	$event = storeEvent();
	$program = $event->getProgram();
	if($program == NULL)
	{
		$program = new Program();
		$event->setProgram($program);
	}

	$ppis = &$program->getProgram_ProgramItems();
	$num_ppis = count($ppis);
		
	// loop through the Program_ProgramItem (ppi_id) ids stored in the form.

	for($i = 0; $i < count($_POST['ppi_ids']); $i++)
	{
		//
		// Extract the Program_ProgramItem data.
		//

		$ppi_id = getPost($_POST['ppi_ids'][$i]);
		$is_checked = isset($_POST['checked_ppi_ids_'.$i]); // not currently used
		$position = getPost($_POST['positions'][$i]);
		$start_time = getPost($_POST['start_times'][$i]);
		$instance_id = (isset($_POST['instance_ids'][$i]) == true ? getPost($_POST['instance_ids'][$i]) : NULL);

//		$context->addActionMessage("<BR>INDEX: " . $i . "<BR>");
//		$context->addActionMessage("ppi_id: " . $ppi_id . "<BR>");
//		$context->addActionMessage("is_checked: " . $is_checked . "<BR>");
//		$context->addActionMessage("position: " . $position . "<BR>");
//		$context->addActionMessage("start_time: " . $start_time . "<BR>");
//		$context->addActionMessage("instance_id: " . ($instance_id == NULL ? "NULL" : $instance_id) . "<BR>");
//		$context->addActionMessage("There are " . $num_ppis . " existing ppis and" . count($_POST['ppi_ids']) . "ppis on the page");
			
		if($is_checked == true)
		{
			if($i < $num_ppis)
			{
				$ppi = $ppis[$i];
				$ppi->setDoDelete(true);
//				$context->addActionMessage("checked: ppi to delete" . $ppi->getId());
			}
		}
		else
		{

			//
			// If there is not a ppi in the program, add one.
			//
			if($i < $num_ppis)
			{
				$ppi = $ppis[$i];
			}
			else
			{
				$ppi = new Program_ProgramItem();
				$program->addProgram_ProgramItem($ppi);
			}
	
//			$context->addActionMessage("ppi -> program id" . $ppi->getProgram_Id());
//			$context->addActionMessage("program -> program id" . $program->getId());
			
			$ppi->setId($ppi_id);
			$ppi->setPosition($position);
			$ppi->setStartTimeString($start_time);
			$ppi->setProgramItem_Id($instance_id);
		}
	}

	return $event;
}

function storeLocation()
{
	if(isset($_SESSION['current_location']) == false)
	{
		$_SESSION['current_location'] = new Location();
	}

	$location = $_SESSION['current_location'];
	//$location->seId(getPost($_POST['location_id']));
	$location->setName(getPost($_POST['location_name']));
	$location->setUrl(getPost($_POST['location_url']));
	$location->setUrlText(getPost($_POST['location_url_text']));
	$location->setMapUrl(getPost($_POST['location_map_url']));
	$location->setMapUrlText(getPost($_POST['location_map_url_text']));
	$location->setAddress(getPost($_POST['location_address']));
	$location->setCity(getPost($_POST['location_city']));
	$location->setState(getPost($_POST['location_state']));
	$location->setZipCode(getPost($_POST['location_zipcode']));
	$location->setDescription(getPost($_POST['location_description']));
	
	return $location;
}

function storeCollateralCollection($collateral_collection_type)
{
	$collateral_collection = NULL;
	
	switch($collateral_collection_type)
	{
		case "program_item":
			$collateral_collection = storeProgramItem();
			break;
		case "related_person":
			$collateral_collection = storeRelatedPerson();
			break;
		case "event":
			$collateral_collection = storeEvent();
			break;
		case "location":
			$collateral_collection = storeLocation();
			break;
	}
	
	return $collateral_collection;
}

function saveAllObject_Collateral(AbstractCollateralCollection $collateral_collection, Messaging $messaging)
{
	$default_collateral_id = '';
	if(isset($_POST['object_collateral_default']))
	{
		$default_collateral_id = getPost($_POST['object_collateral_default']);
	}

	if(isset($_POST['object_collateral_ids']))
	{
		for($i = 0; $i < count($_POST['object_collateral_ids']); $i++)
		{
			$object_collateral = $collateral_collection->getObject_CollateralByCollateralId(getPost($_POST['object_collateral_ids'][$i]));
			if(isset($object_collateral))
			{
				$object_collateral->setSortOrder(getPost($_POST['object_collateral_sort_order'][$i]));
				if(getPost($_POST['object_collateral_ids'][$i]) == $default_collateral_id)
				{
					$object_collateral->setIsDefault(true);
				}
				else
				{
					$object_collateral->setIsDefault(false);
				}
			}
		}
			
		$collateral_collection->sortObject_Collateral();
	}

	
	$collateral_collection->saveAllObject_Collateral($messaging);
}

?>