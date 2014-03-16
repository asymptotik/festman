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
class FmGeneralEventHandler extends FmEventHandler
{
  function __construct() 
  {
    parent::__construct();
  }
  //
  // Handle the general events and delegate them to the appropriate function.
  //
  function handleEvent(EventContext $context)
  {
  }
  
  function getFallbackLocation()
  {
  	return "../index.php";
  }
}
?>