<?php
require_once (dirname(__FILE__).'/../library/config.php');
require_once (dirname(__FILE__).'/../library/utils.php');
require_once (dirname(__FILE__).'/admin_utils.php');
require_once (dirname(__FILE__).'/../objects/EventContext.php');

abstract class FmEventHandler
{
  function __construct() {

  }
  
  abstract function handleEvent(EventContext $context);
  abstract function getFallbackLocation();
  
  function shouldPerformAction($action_id)
  {
    //if(array_key_exists($action_id, $_SESSION) == true)
    //{
    //  return false;
    //}
    
    // $_SESSION[$action_id] = true;
    
    return true;
  }
  
  function getActionResults()
  {
    unset($_SESSION['error_message']);
    unset($_SESSION['action_message']);
    unset($_SESSION['progress_message']);
    
    $fm_context = new EventContext();
    $fm_action = fmGetVar('action');
    $fm_forward = NULL;
    
    /*
    echo "getActionResults";
      foreach($_POST as $n=>$v)
      {
         echo " Name: " . $n . " Value: " . $v . "<br/>";
      }
            
      foreach($_GET as $n=>$v)
      {
         echo " Name: " . $n . " Value: " . $v . "<br/>";
      }
   */
    if(!empty($fm_action))
    {
    	$fm_action_id = fmGetVar('action_id');

      //echo "Action: " . $fm_action. "<br/>";
      
    	if($this->shouldPerformAction($fm_action_id) == true)
    	{
    		$fm_context->setEventName($fm_action);
    		
        $this->handleEvent($fm_context);
    		
    		if(strlen($fm_context->getErrorMessage()) > 0)
    		{
    			$_SESSION['error_message'] = $fm_context->getErrorMessage();
    		}
    
    		if(strlen($fm_context->getActionMessage()) > 0)
    		{
    			$_SESSION['action_message'] = $fm_context->getActionMessage();
    		}
    		
    		//
    		// see if the handler set the forward.
    		//
    		if(strlen($fm_context->getForward()) > 0)
    		{
    			$fm_forward = $fm_context->getForward();
    		}
    		
    		//echo 'forward: ' . $forward;
    	}
    }
    else
    {
    	$fm_context->addErrorMessage("No event was found.");
    }
    
    if($fm_forward == NULL)
    {
    	$fm_forward = $this->getFallbackLocation();
    }
    
    return $fm_forward;
  }

  function storeProgramItem()
  {
    //echo var_dump($_SESSION);
    if(isset($_SESSION['current_program_item']) == false)
    {
      $_SESSION['current_program_item'] = new ProgramItem();
    }
  
    $program_item = $_SESSION['current_program_item'];
    $program_item->setObjectClass(fmGetPost($_POST['program_item_class']));
    //$program_item->setId(fmGetPost($_POST['program_item_id']));
    $program_item->setName(fmGetPost($_POST['program_item_name']));
    $program_item->setOrigin(fmGetPost($_POST['program_item_origin']));
    $program_item->setUrl(fmGetPost($_POST['program_item_url']));
    $program_item->setUrlText(fmGetPost($_POST['program_item_url_text']));
    $program_item->setEmbed(fmGetPost($_POST['program_item_embed']));
    $program_item->setEmbedText(fmGetPost($_POST['program_item_embed_text']));
    $program_item->setDescription(fmGetPost($_POST['program_item_description']));
  
    //echo "program item: " . serialize($program_item);
    return $program_item;
  }
  
  function storeRelatedPerson()
  {
    if(isset($_SESSION['current_related_person']) == false)
    {
      $_SESSION['current_related_person'] = new RelatedPerson();
    }
  
    $related_person = $_SESSION['current_related_person'];
    //$related_person->setId(fmGetPost($_POST['related_person_id']));
    $related_person->setName(fmGetPost($_POST['related_person_name']));
    $related_person->setUrl(fmGetPost($_POST['related_person_url']));
    $related_person->setUrlText(fmGetPost($_POST['related_person_url_text']));
    $related_person->setRole(fmGetPost($_POST['related_person_role']));
    $related_person->setDescription(fmGetPost($_POST['related_person_description']));
  
    return $related_person;
  }
  
  function storeEvent()
  {
    if(isset($_SESSION['current_event']) == false)
    {
      $_SESSION['current_event'] = new Event();
    }
  
    $event = $_SESSION['current_event'];
    //$event->setId(fmGetPost($_POST['event_id']));
    $event->setName(fmGetPost($_POST['event_name']));
    $event->setLocation_Id(fmGetPost($_POST['event_location_id']));
    $event->setDescription(fmGetPost($_POST['event_description']));
    $event->setStartTimeString(fmGetPost($_POST['event_start_time']));
    $event->setEndTimeString(fmGetPost($_POST['event_end_time']));
    
    return $event;
  }
  
  //
  // Fired from the event_editor.php page.
  //
  function storeCompleteEvent(EventContext $context)
  {
    $event = $this->storeEvent();
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
  
      $ppi_id = fmGetPost($_POST['ppi_ids'][$i]);
      $is_checked = isset($_POST['checked_ppi_ids_'.$i]); // not currently used
      $position = fmGetPost($_POST['positions'][$i]);
      $start_time = fmGetPost($_POST['start_times'][$i]);
      $instance_id = (isset($_POST['instance_ids'][$i]) == true ? fmGetPost($_POST['instance_ids'][$i]) : NULL);
  
  //    $context->addActionMessage("<BR>INDEX: " . $i . "<BR>");
  //    $context->addActionMessage("ppi_id: " . $ppi_id . "<BR>");
  //    $context->addActionMessage("is_checked: " . $is_checked . "<BR>");
  //    $context->addActionMessage("position: " . $position . "<BR>");
  //    $context->addActionMessage("start_time: " . $start_time . "<BR>");
  //    $context->addActionMessage("instance_id: " . ($instance_id == NULL ? "NULL" : $instance_id) . "<BR>");
  //    $context->addActionMessage("There are " . $num_ppis . " existing ppis and" . count($_POST['ppi_ids']) . "ppis on the page");
        
      if($is_checked == true)
      {
        if($i < $num_ppis)
        {
          $ppi = $ppis[$i];
          $ppi->setDoDelete(true);
  //        $context->addActionMessage("checked: ppi to delete" . $ppi->getId());
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
    
  //      $context->addActionMessage("ppi -> program id" . $ppi->getProgram_Id());
  //      $context->addActionMessage("program -> program id" . $program->getId());
        
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
    //$location->seId(fmGetPost($_POST['location_id']));
    $location->setName(fmGetPost($_POST['location_name']));
    $location->setUrl(fmGetPost($_POST['location_url']));
    $location->setUrlText(fmGetPost($_POST['location_url_text']));
    $location->setMapUrl(fmGetPost($_POST['location_map_url']));
    $location->setMapUrlText(fmGetPost($_POST['location_map_url_text']));
    $location->setAddress(fmGetPost($_POST['location_address']));
    $location->setCity(fmGetPost($_POST['location_city']));
    $location->setState(fmGetPost($_POST['location_state']));
    $location->setZipCode(fmGetPost($_POST['location_zipcode']));
    $location->setDescription(fmGetPost($_POST['location_description']));
    
    return $location;
  }
  
  function storeCollateralCollection($collateral_collection_type)
  {
    $collateral_collection = NULL;
    
    switch($collateral_collection_type)
    {
      case "program_item":
        $collateral_collection = $this->storeProgramItem();
        break;
      case "related_person":
        $collateral_collection = $this->storeRelatedPerson();
        break;
      case "event":
        $collateral_collection = $this->storeEvent();
        break;
      case "location":
        $collateral_collection = $this->storeLocation();
        break;
    }
    
    return $collateral_collection;
  }
  
  function saveAllObject_Collateral(AbstractCollateralCollection $collateral_collection, Messaging $messaging)
  {
    $default_collateral_id = '';
    if(isset($_POST['object_collateral_default']))
    {
      $default_collateral_id = fmGetPost($_POST['object_collateral_default']);
    }
  
    if(isset($_POST['object_collateral_ids']))
    {
      for($i = 0; $i < count($_POST['object_collateral_ids']); $i++)
      {
        $object_collateral = $collateral_collection->getObject_CollateralByCollateralId(fmGetPost($_POST['object_collateral_ids'][$i]));
        if(isset($object_collateral))
        {
          $object_collateral->setSortOrder(fmGetPost($_POST['object_collateral_sort_order'][$i]));
          if(fmGetPost($_POST['object_collateral_ids'][$i]) == $default_collateral_id)
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

}
?>