<?php
require_once dirname(__FILE__).'/admin_prebody_header.php';
require_once dirname(__FILE__).'/../../library/config.php';
require_once dirname(__FILE__).'/../../library/opendb.php';
require_once dirname(__FILE__).'/../../library/utils.php';
require_once dirname(__FILE__).'/admin_utils.php';

require_once dirname(__FILE__).'/../../objects/EventContext.php';

unset($_SESSION['error_message']);
unset($_SESSION['action_message']);
unset($_SESSION['progress_message']);

$context = new EventContext();

if(array_key_exists('action', $_POST))
{
	$action = getPost($_POST['action']);
	$action_id = getPost($_POST['action_id']);
	$forward = NULL;
	
	if(shouldPerformAction($action_id) == true)
	{
//		   echo "Performing Action: " . $action. "<br/>";
//		
//		    foreach($_POST as $n=>$v)
//		    {
//		       echo " Name: " . $n . " Value: " . $v . "<br/>";
//		    }

		$context->setEventName($action);
		
        handleEvent($context);
		
		if(strlen($context->getErrorMessage()) > 0)
		{
			$_SESSION['error_message'] = $context->getErrorMessage();
		}

		if(strlen($context->getActionMessage()) > 0)
		{
			$_SESSION['action_message'] = $context->getActionMessage();
		}
		
		//
		// see if the handler set the forward.
		//
		if(strlen($context->getForward()) > 0)
		{
			$forward = $context->getForward();
		}
		
		//echo 'forward: ' . $forward;
	}
}
else
{
	$context->addErrorMessage("No event was found.");
}

include dirname(__FILE__).'/../../library/closedb.php';

if($forward == NULL)
{
	$forward = getFallbackLocation();
}

forward($forward);
?>