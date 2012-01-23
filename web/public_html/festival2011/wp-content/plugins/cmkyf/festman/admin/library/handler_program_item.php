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
require_once dirname(__FILE__).'/../../objects/RelatedPerson.php';
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

	if($event_name == "manage_program_items")
	{
		handleManageProgramItems($context);
	}
	else if($event_name == "cancel_program_item_editor")
	{
		handleCancelProgramItemEditor($context);
	}
	else if($event_name == "cancel_program_item_selector")
	{
		handleCancelProgramItemSelector($context);
	}
	else if($event_name == "delete_program_items")
	{
		handleDeleteProgramItems($context);
	}
	else if($event_name == "delete_program_item")
	{
		handleDeleteProgramItem($context);
	}
	else if($event_name == "save_program_item")
	{
		handleSaveProgramItem($context);
	}
	else if($event_name == "refresh_program_item")
	{
		handleRefreshProgramItem($context);
	}
	else if($event_name == "edit_program_item")
	{
		handleEditProgramItem($context);
	}
	else if($event_name == "create_program_item")
	{
		handleCreateProgramItem($context);
	}
	else if($event_name == "cancel_related_person_editor")
	{
		handleCancelRelatedPersonEditor($context);
	}
	else if($event_name == "store_related_person")
	{
		handleStoreRelatedPerson($context);
	}
	else if($event_name == "create_related_person")
	{
		handleCreateRelatedPerson($context);
	}
	else if($event_name == "edit_related_person")
	{
		handleEditRelatedPerson($context);
	}
	else if($event_name == "delete_related_persons")
	{
		handleDeleteRelatedPersons($context);
	}
	else if($event_name == "delete_related_person")
	{
		handleDeleteRelatedPerson($context);
	}
}

function getFallbackLocation()
{
	return "/../program_item_editor.php";
}

//
// Fired from the IndexMenuControl.php page.
//
function handleManageProgramItems(EventContext $context)
{
	$_SESSION['current_program_item_class'] = getPost($_POST['program_item_class']);
	$context->setForward("../program_item_selector.php");
}

//
// Fired from the program_item_selector.php page.
//
function handleCancelProgramItemEditor(EventContext $context)
{
	$context->setForward("../program_item_selector.php");
}

//
// Fired from the program_item_selector.php page.
//
function handleCancelProgramItemSelector(EventContext $context)
{
	$context->setForward("../index.php");
}

//
// Fired from the program_item_selector.php page.
//
function handleDeleteProgramItems(EventContext $context)
{
	$count = count($_POST["program_item_ids"]);
	for($i = 0; $i < $count; $i++)
	{
		ProgramItem::deleteProgramItem(getPost($_POST["program_item_ids"][$i]));
	}

	$action_message = "Deleted " . $count . " " . ProgramItem::getObjectClassDisplayName(getPost($_POST["program_item_class"]));
	if($count > 0) $action_message = $action_message . "s";
	$action_message = $action_message . ".";

	$context->addActionMessage($action_message);
	$context->setForward("../program_item_selector.php");
}

//
// Fired from the program_item_selector.php page.
//
function handleDeleteProgramItem(EventContext $context)
{
	ProgramItem::deleteProgramItem(getPost($_POST["program_item_id"]));
	$context->addActionMessage("Deleted 1 " . ProgramItem::getObjectClassDisplayName(getPost($_POST["program_item_class"])));
	$context->setForward("../program_item_selector.php");
}

//
// Fired from the program_item_editor.php page.
//
function handleSaveProgramItem(EventContext $context)
{
	$program_item = storeProgramItem();
	$program_item->save();

	$object_class_display_name = ProgramItem::getObjectClassDisplayName($program_item->getObjectClass());

	if($program_item->getId() != NULL)
	{
		saveAllObject_Collateral($program_item, $context);
	}
	else
	{
		$context->addErrorMessage($object_class_display_name. " has a null id.");
	}
	
	$related_persons = $program_item->getRelatedPersons();
	
	for($i = 0; $i < count($related_persons); $i++)
	{
		$related_person = $related_persons[$i];
		$related_person->save();
		
	
		if($related_person->getId() != NULL)
		{
			saveAllObject_Collateral($related_person, $context);
		}
		else
		{
			$context->addErrorMessage("RelatedPerson " . $related_person->getName(). " has a null id.");
		}
	}
	
	$context->setForward("../program_item_selector.php");
}

//
// Fired from the program_item_selector.php page.
//
function handleEditProgramItem(EventContext $context)
{
	$_SESSION['current_program_item'] = ProgramItem::getProgramItem(getPost($_POST['program_item_id']));
	$_SESSION['current_program_item_class'] = getPost($_POST['program_item_class']);
	$context->setForward("../program_item_editor.php");
}

//
// Fired from the program_item_selector.php page.
//
function handleCreateProgramItem(EventContext $context)
{
	unset($_SESSION['current_program_item']);
	$_SESSION['current_program_item_class'] = getPost($_POST['program_item_class']);
	$context->setForward("../program_item_editor.php");
}

//
// Fired from the related_person_editor.php page
//
function handleCancelRelatedPersonEditor(EventContext $context)
{
	$context->setForward("../program_item_editor.php");
}

//
// Fired from the related_person_editor.php page
//
function handleStoreRelatedPerson(EventContext $context)
{
	$related_person = storeRelatedPerson();

	if($related_person->getId() == NULL)
	{
		$program_item = $_SESSION['current_program_item'];
		if(isset($program_item))
		{
			$program_item->addRelatedPerson($related_person);
		}
	}

	$context->setForward("../program_item_editor.php");
}

//
// Fired from the program_item_editorr.php page.
//
function handleEditRelatedPerson(EventContext $context)
{
	storeProgramItem();
	$related_person = RelatedPerson::getRelatedPerson(getPost($_POST['related_person_id']));
	$_SESSION['current_related_person'] = $related_person;
	$context->setForward("../related_person_editor.php");
}

//
// Fired from the program_item_editor.php page.
//
function handleCreateRelatedPerson(EventContext $context)
{
	storeProgramItem();
	unset($_SESSION['current_related_person']);
	$context->setForward("../related_person_editor.php");
}

//
// Fired from the program_item_editor.php page.
//
function handleDeleteRelatedPersons(EventContext $context)
{
	storeProgramItem();
	$count = count($_POST["related_person_ids"]);
	for($i = 0; $i < $count; $i++)
	{
		RelatedPerson::deleteRelatedPerson(getPost($_POST["related_person_ids"][$i]));
	}

	$action_message = "Deleted " . $count . " Related Person";
	if($count > 0) $action_message = $action_message . "s";
	$action_message = $action_message . ".";

	$context->addActionMessage($action_message);
	$context->setForward("../related_person_editor.php");
}

//
// Fired from the program_item_editor.php page.
//
function handleDeleteRelatedPerson(EventContext $context)
{
	storeProgramItem();
	RelatedPerson::deleteRelatedPerson(getPost($_POST["related_person_id"]));
	$context->addActionMessage("Deleted 1 Related Person.");
	$context->setForward("../related_person_editor.php");
}

?>