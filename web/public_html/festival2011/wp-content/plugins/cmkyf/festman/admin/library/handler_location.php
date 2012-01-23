<?php
require_once dirname(__FILE__).'/../../objects/ProgramItem.php';
require_once dirname(__FILE__).'/../../objects/Act.php';
require_once dirname(__FILE__).'/../../objects/Event.php';
require_once dirname(__FILE__).'/../../objects/Collateral.php';
require_once dirname(__FILE__).'/../../objects/Klass.php';
require_once dirname(__FILE__).'/../../objects/Film.php';
require_once dirname(__FILE__).'/../../objects/Location.php';
require_once dirname(__FILE__).'/../../objects/Panel.php';
require_once dirname(__FILE__).'/../../objects/Film.php';
require_once dirname(__FILE__).'/../../objects/Installation.php';
require_once dirname(__FILE__).'/../../objects/Program_ProgramItem.php';
require_once dirname(__FILE__).'/../../objects/Program.php';
require_once dirname(__FILE__).'/../../objects/Workshop.php';

require_once dirname(__FILE__).'/storage.php';

//
// Sets up data and calls handleEvent.
//
require_once dirname(__FILE__).'/handler_include.php';

//
//Event Handlers
//

//
// Handle the general events and delegate them to the appropriate function.
//
function handleEvent(EventContext $context)
{
	$event_name = $context->getEventName();
	
	if($event_name == "manage_locations")
	{
		handleManageLocations($context);
	}
	else if($event_name == "cancel_location_selector")
	{
		handleCancelLocationSelector($context);
	}
	else if($event_name == "cancel_location_editor")
	{
		handleCancelLocationEditor($context);
	}
	else if($event_name == "delete_locations")
	{
		handleDeleteLocations($context);
	}
	else if($event_name == "delete_location")
	{
		handleDeleteLocation($context);
	}
	else if($event_name == "save_location")
	{
		handleSaveLocation($context);
	}
	else if($event_name == "edit_location")
	{
		handleEditLocation($context);
	}
	else if($event_name == "create_location")
	{
		handleCreateLocation($context);
	}
}

function getFallbackLocation()
{
	return "../location_editor.php";
}

//
// Fired from the IndexMenuControl.php page.
//
function handleManageLocations(EventContext $context)
{
   $context->setForward("../location_selector.php");
}

//
// Fired from the location_selector.php page.
//
function handleCancelLocationSelector(EventContext $context)
{
	$context->setForward("../index.php");
}

//
// Fired from the location_editor.php page.
//
function handleCancelLocationEditor(EventContext $context)
{
	$context->setForward("../location_selector.php");
}

//
// Fired from the location_selector.php page.
//
function handleDeleteLocations(EventContext $context)
{
	$count = count($_POST["location_ids"]);
	for($i = 0; $i < $count; $i++)
	{
		Location::deleteLocation(getPost($_POST["location_ids"][$i]));
		//echo $_POST["act_ids"][$i] . "<BR>";
	}

	$action_message = "Deleted " . $count . "Location";
	if($count > 0) $action_message = $action_message . "s";
	$action_message = $action_message . ".";
	
	$context->addActionMessage($action_message);
	$context->setForward("../location_selector.php");
}

//
// Fired from the location_selector.php page.
//
function handleDeleteLocation(EventContext $context)
{
	Location::deleteLocation(getPost($_POST["location_id"]));

	$context->addActionMessage("Deleted 1 Location.");
	$context->setForward("../location_selector.php");
}

//
// Fired from the location_editor.php page.
//
function handleSaveLocation(EventContext $context)
{
	$location = storeLocation();
	$location->save();
		
	saveAllObject_Collateral($location, $context);
	$context->setForward("../location_selector.php");
}

//
// Fired from the location_selector.php page.
//
function handleEditLocation(EventContext $context)
{
	$_SESSION['current_location'] = Location::getLocation(getPost($_POST['location_id']));
	$context->setForward("../location_editor.php");
}

//
// Fired from the location_selector.php page.
//
function handleCreateLocation(EventContext $context)
{
	unset($_SESSION['current_location']);
	$context->setForward("../location_editor.php");
}

?>