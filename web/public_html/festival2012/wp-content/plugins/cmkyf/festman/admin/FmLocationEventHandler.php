<?php
require_once (dirname(__FILE__).'/../objects/ProgramItem.php');
require_once (dirname(__FILE__).'/../objects/Act.php');
require_once (dirname(__FILE__).'/../objects/Event.php');
require_once (dirname(__FILE__).'/../objects/Collateral.php');
require_once (dirname(__FILE__).'/../objects/Klass.php');
require_once (dirname(__FILE__).'/../objects/Film.php');
require_once (dirname(__FILE__).'/../objects/Location.php');
require_once (dirname(__FILE__).'/../objects/Panel.php');
require_once (dirname(__FILE__).'/../objects/Film.php');
require_once (dirname(__FILE__).'/../objects/Installation.php');
require_once (dirname(__FILE__).'/../objects/Program_ProgramItem.php');
require_once (dirname(__FILE__).'/../objects/Program.php');
require_once (dirname(__FILE__).'/../objects/Workshop.php');
//
// Sets up data and calls handleEvent.
//
require_once (dirname(__FILE__).'/FmEventHandler.php');

//
//Event Handlers
//
class FmLocationEventHandler extends FmEventHandler
{
  function __construct() 
  {
    parent::__construct();
  }
  //
  //
  // Handle the general events and delegate them to the appropriate function.
  //
  function handleEvent(EventContext $context)
  {
  	$event_name = $context->getEventName();
  	
  	if($event_name == "manage_locations")
  	{
  		$this->handleManageLocations($context);
  	}
  	else if($event_name == "cancel_location_selector")
  	{
  		$this->handleCancelLocationSelector($context);
  	}
  	else if($event_name == "cancel_location_editor")
  	{
  		$this->handleCancelLocationEditor($context);
  	}
  	else if($event_name == "delete_locations")
  	{
  		$this->handleDeleteLocations($context);
  	}
  	else if($event_name == "delete_location")
  	{
  		$this->handleDeleteLocation($context);
  	}
  	else if($event_name == "save_location")
  	{
  		$this->handleSaveLocation($context);
  	}
  	else if($event_name == "edit_location")
  	{
  		$this->handleEditLocation($context);
  	}
  	else if($event_name == "create_location")
  	{
  		$this->handleCreateLocation($context);
  	}
  }
  
  function getFallbackLocation()
  {
  	return dirname(__FILE__) . "/location_editor.php";
  }
  
  //
  // Fired from the IndexMenuControl.php page.
  //
  function handleManageLocations(EventContext $context)
  {
     $context->setForward(dirname(__FILE__) . "/location_selector.php");
  }
  
  //
  // Fired from the location_selector.php page.
  //
  function handleCancelLocationSelector(EventContext $context)
  {
  	$context->setForward(dirname(__FILE__) . "/index.php");
  }
  
  //
  // Fired from the location_editor.php page.
  //
  function handleCancelLocationEditor(EventContext $context)
  {
  	$context->setForward(dirname(__FILE__) . "/location_selector.php");
  }
  
  //
  // Fired from the location_selector.php page.
  //
  function handleDeleteLocations(EventContext $context)
  {
  	$count = count(fmGetVar("location_ids"));
  	for($i = 0; $i < $count; $i++)
  	{
  		Location::deleteLocation(fmGetPost($_POST["location_ids"][$i]));
  		//echo $_POST["act_ids"][$i] . "<BR>";
  	}
  
  	$action_message = "Deleted " . $count . "Location";
  	if($count > 0) $action_message = $action_message . "s";
  	$action_message = $action_message . ".";
  	
  	$context->addActionMessage($action_message);
  	$context->setForward(dirname(__FILE__) . "/location_selector.php");
  }
  
  //
  // Fired from the location_selector.php page.
  //
  function handleDeleteLocation(EventContext $context)
  {
  	Location::deleteLocation(fmGetVar("location_id"));
  
  	$context->addActionMessage("Deleted 1 Location.");
  	$context->setForward(dirname(__FILE__) . "/location_selector.php");
  }
  
  //
  // Fired from the location_editor.php page.
  //
  function handleSaveLocation(EventContext $context)
  {
  	$location = storeLocation();
  	$location->save();
  		
  	$this->saveAllObject_Collateral($location, $context);
  	$context->setForward(dirname(__FILE__) . "/location_selector.php");
  }
  
  //
  // Fired from the location_selector.php page.
  //
  function handleEditLocation(EventContext $context)
  {
  	$_SESSION['current_location'] = Location::getLocation(fmGetVar('location_id'));
  	$context->setForward(dirname(__FILE__) . "/location_editor.php");
  }
  
  //
  // Fired from the location_selector.php page.
  //
  function handleCreateLocation(EventContext $context)
  {
  	unset($_SESSION['current_location']);
  	$context->setForward(dirname(__FILE__) . "/location_editor.php");
  }
}
?>