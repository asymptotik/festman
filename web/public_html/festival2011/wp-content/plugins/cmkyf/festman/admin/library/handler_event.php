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

	if($event_name == "manage_events")
	{
		handleManageEvents($context);
	}
	else if($event_name == "cancel_event_editor")
	{
		handleCancelEventEditor($context);
	}
	else if($event_name == "cancel_event_selector")
	{
		handleCancelEventSelector($context);
	}
	else if($event_name == "delete_events")
	{
		handleDeleteEvents($context);
	}
	else if($event_name == "delete_event")
	{
		handleDeleteEvent($context);
	}
	else if($event_name == "save_event")
	{
		handleSaveEvent($context);
	}
	else if($event_name == "remove_program_item")
	{
		handleRemoveProgramItem($context);
	}
	else if($event_name == "edit_event")
	{
		handleEditEvent($context);
	}
	else if($event_name == "create_event")
	{
		handleCreateEvent($context);
	}
}

function getFallbackLocation()
{
	return "../event_editor.php";
}

//
// Fired from the IndexMenuControl.php page.
//
function handleManageEvents(EventContext $context)
{
   $context->setForward("../event_selector.php");
}

//
// Fired from the event_editor.php page.
//
function handleCancelEventEditor(EventContext $context)
{
   $context->setForward("../event_selector.php");
}

//
// Fired from the event_selector.php page.
//
function handleCancelEventSelector(EventContext $context)
{
   $context->setForward("../index.php");
}

//
// Fired from the event_selector.php page.
//
function handleDeleteEvents(EventContext $context)
{
	$count = count($_POST["event_ids"]);
	for($i = 0; $i < $count; $i++)
	{
		Event::deleteEvent(getPost($_POST["event_ids"][$i]));
		//echo $_POST["act_ids"][$i] . "<BR>";
	}

	$action_message = "Deleted " . $count . "Location";
	if($count > 0) $action_message = $action_message . "s";
	$action_message = $action_message . ".";

	$context->addActionMessage($action_message);
	$context->setForward("../event_selector.php");
}

//
// Fired from the event_selector.php page.
//
function handleDeleteEvent(EventContext $context)
{
	Event::deleteEvent(getPost($_POST["event_id"]));

	$context->addActionMessage("Deleted 1 Event.");
	$context->setForward("../event_selector.php");
}

//
// Fired from the event_editor.php page.
//
function handleSaveEvent(EventContext $context)
{
	$event   = storeCompleteEvent($context);
	$program = $event->getProgram();
	
	if($program != NULL)
	{
		$program->save();
		$event->setProgram_Id($program->getId());
	}

	$context->setForward("../event_selector.php");
	
	$event->save();
	saveAllObject_Collateral($event, $context);

	$program->sortProgram_ProgramItems();
	$ppis = &$program->getProgram_ProgramItems();
	$num_ppis_saved = 0;

	for($i = 0; $i < count($ppis); $i++)
	{
		$ppi = $ppis[$i];
		if($ppi->getDoDelete() == true)
		{
			$program->removeProgram_ProgramItemById($ppi->getId());		
			Program_ProgramItem::deleteProgram_ProgramItem($ppi->getId());
			$context->addActionMessage("Removed ppi: " . $ppi->getId());
		}
		else if($ppi->isBlank() == false)
		{
			$ppis[$i]->setProgram_Id($program->getId());
			$validation_message = $ppi->validate();

			if($validation_message == '')
			{
				$ppi->save();
				$num_ppis_saved++;
			}
			else
			{
				$context->addErrorMessage($validation_message);
				$context->setForward("../event_editor.php");
			}
		}
	}
		
	if($num_ppis_saved > 0)
	{
		$context->addActionMessage("Saved " . $num_ppis_saved . " Program_ProgramItems.");
	}
}

//
// Fired from event_editor.php page.
//
function handleRemoveProgramItem(EventContext $context)
{
	if(isset($_SESSION['current_event']) == true)
	{
		$program_program_item_id = $_POST['program_program_item_id'];
		
		$event   = storeCompleteEvent($context);
		$program = $event->getProgram();
		
		$program->removeProgram_ProgramItemById($program_program_item_id);		
		$context->addActionMessage("Removed Program_ProgramItem: " . $program_program_item_id);
		Program_ProgramItem::deleteProgram_ProgramItem($program_program_item_id);
		$context->setForward("../event_editor.php");
	}
}

//
// Fired from the event_selector.php page.
//
function handleEditEvent(EventContext $context)
{
	$_SESSION['current_event'] = Event::getEvent(getPost($_POST['event_id']));
	$context->setForward("../event_editor.php");
}

//
// Fired from the event_selector.php page.
//
function handleCreateEvent(EventContext $context)
{
	unset($_SESSION['current_event']);
	$context->setForward("../event_editor.php");
}

?>