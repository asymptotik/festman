<?php

require_once (dirname(__FILE__) . '/../objects/ProgramItem.php');
require_once (dirname(__FILE__) . '/../objects/Act.php');
require_once (dirname(__FILE__) . '/../objects/Event.php');
require_once (dirname(__FILE__) . '/../objects/Collateral.php');
require_once (dirname(__FILE__) . '/../objects/Klass.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Location.php');
require_once (dirname(__FILE__) . '/../objects/Panel.php');
require_once (dirname(__FILE__) . '/../objects/Film.php');
require_once (dirname(__FILE__) . '/../objects/Installation.php');
require_once (dirname(__FILE__) . '/../objects/Program_ProgramItem.php');
require_once (dirname(__FILE__) . '/../objects/Program.php');
require_once (dirname(__FILE__) . '/../objects/Workshop.php');
require_once (dirname(__FILE__) . '/../objects/FileExtension.php');
//
// Sets up data and calls handleEvent.
//
require_once dirname(__FILE__) . '/FmEventHandler.php';

//
//Event Handlers
//
class FmCollateralCollectionEventHandler extends FmEventHandler
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
        $event_name = $context->getEventName();

        if ($event_name == "cancel_collateral_collection_editor")
        {
            $this->handleCancelCollateralCollectionEditor($context);
        }
        else if ($event_name == "add_selected_collateral")
        {
            $this->handleAddSelectedCollateral($context);
        }
        else if ($event_name == "filter_collateral")
        {
            $this->handleFilterCollateralList($context);
        }
        else if ($event_name == "upload_collateral")
        {
            $this->handleUploadCollateralList($context);
        }
        else if ($event_name == "add_collateral")
        {
            $this->handleAddCollateral($context);
        }
        else if ($event_name == "remove_collateral")
        {
            $this->handleRemoveCollateral($context);
        }
        else if ($event_name == "remove_collateral_list")
        {
            $this->handleRemoveCollateralList($context);
        }
    }

    function getFallbackLocation()
    {
        return dirname(__FILE__) . "/collateral_collection_editor.php";
    }

    //
    // Fired from the collateral_collection_editor.php page.
    //
  function handleCancelCollateralCollectionEditor(EventContext $context)
    {
        $collateral_collection = AbstractCollateralCollection::getSessionCollateralCollection(fmGetVar('collateral_collection_type'));
        if ($collateral_collection != NULL)
        {
            $context->setForward(dirname(__FILE__) . "/" . $collateral_collection->getEditor());
        }
    }

    //
    // Fired from the CollateralCollectionControl.php page.
    //
  function handleAddSelectedCollateral(EventContext $context)
    {
        $collateral_collection_type = fmGetVar('collateral_collection_type');
        $collateral_collection = AbstractCollateralCollection::getSessionCollateralCollection($collateral_collection_type);

        if ($collateral_collection != NULL)
        {
            $context->setForward(dirname(__FILE__) . "/" . $collateral_collection->getEditor());

            $num_added = 0;
            if (isset($_POST['object_collateral_ids']))
            {
                for ($i = 0; $i < count($_POST['object_collateral_ids']); $i++)
                {
                    $object_collateral_id = fmGetPost($_POST['object_collateral_ids'][$i]);
                    $collateral_collection->addCollateralById($object_collateral_id);
                    $num_added++;
                }
            }

            $context->addActionMessage("Added " . $num_added . " collateral(s) to " . $collateral_collection->getDisplayName() . ".");
            $context->setForward(dirname(__FILE__) . "/" . $collateral_collection->getEditor());
        }
        else
        {
            $context->addErrorMessage("Could not find a valid object of type " . fmGetVar('collateral_collection_type') . " to add the collateral to.");
            $context->setForward(dirname(__FILE__) . "/collateral_collection_editor.php");
        }
    }

    //
    // Fired from the collateral_collection_editor.php page.
    //
  function handleFilterCollateral(EventContext $context)
    {
        $collateral_location_name = fmGetVar('collateral_location_name');
        $_SESSION['current_collateral_location_name'] = $collateral_location_name;
        $context->setForward(dirname(__FILE__) . "/collateral_collection_editor.php");
    }

    //
    // Fired from the collateral_collection_editor.php page.
    //
  function handleUploadCollateralList(EventContext $context)
    {
        //echo "handleUploadCollateralList<br/>";
        $collateral_collection_type = fmGetVar('collateral_collection_type');

        if (empty($collateral_collection_type))
            $context->addErrorMessage("handleUploadCollateralList: Unable to determine Collateral Collection Type");

        //echo "collateral_collection_type: " . $collateral_collection_type;
        $collateral_collection = AbstractCollateralCollection::getSessionCollateralCollection($collateral_collection_type);
        
        $folder = NULL;
        if ($collateral_collection != NULL)
        {
            $folder = $collateral_collection->getCollateralDirectory();
        }
        else
        {
            $folder = "misc";
        }
        
        $dest_dir = dirname(__FILE__) . "/../upload/" . $folder . "/";
        
        //echo "Dest Dir " . $dest_dir;
        $results = fmHandleMultipleUploadedFiles($dest_dir, isset($_POST['overwrite_existing_collateral']));
        //echo "One<br/>";
        for ($i = 0; $i < count($results['name']); $i++)
        {
            $collateral_name = fmGetPost($results['name'][$i]);
            $ext = substr(strrchr($collateral_name, "."), 1);
            $file_extension = FileExtension::getFileExtensionByExtention($ext);

            if ($results['error'][$i] <= 0)
            {
                $collateral = new Collateral();
                $collateral->setLocation("/upload/" . $folder);
                $collateral->setName($collateral_name);
                $collateral->setMimeType_Id($file_extension->getMimeType_Id());
                $collateral->save();

                $context->addActionMessage("Added Collateral '" . $collateral_name . "' with Mime Type ID '" . $file_extension->getMimeType_Id() . "'");
            }
            else if (strlen(trim($collateral_name)) > 0)
            {
                $context->addErrorMessage($results['message'][$i]);
            }
        }

        $context->setForward(dirname(__FILE__) . "/collateral_collection_editor.php");
    }

    //
    // Fired from the collateral_collection_editor.php page.
    //
  function handleAddCollateral(EventContext $context)
    {
        $collateral_collection_type = fmGetVar('collateral_collection_type');
        $collateral_collection = $this->storeCollateralCollection($collateral_collection_type);
        $_SESSION['current_collateral_collection_type'] = fmGetVar('collateral_collection_type');
        $context->setForward(dirname(__FILE__) . "/collateral_collection_editor.php");
    }

    // http://localhost/festival2012/wp-admin/admin.php?page=fm-collateral-page&action=remove_collateral&collateral_collection_type=program_item&object_collateral_id=82&_wpnonce=ac238b8b03
    //
  // Fired from the CollateralCollectionControl.php page.
    //
  function handleRemoveCollateral(EventContext $context)
    {
        $collateral_collection = $this->storeCollateralCollection(fmGetVar('collateral_collection_type'));
        $collateral_collection->removeCollateralById(fmGetVar('object_collateral_id'));
        $context->setForward(dirname(__FILE__) . "/" . $collateral_collection->getEditor());
    }

    //
    // Fired from the CollateralCollectionControl.php page.
    //
  function handleRemoveCollateralList(EventContext $context)
    {
        $collateral_collection = $this->storeCollateralCollection(fmGetVar('collateral_collection_type'));

        $collateral_id_count = 0;
        if (isset($_POST['object_collateral_checked_ids']))
        {
            $collateral_id_count = count(fmGetVar('object_collateral_checked_ids'));
            for ($i = 0; $i < $collateral_id_count; $i++)
            {
                $collateral_collection->removeCollateralById(fmGetPost($_POST['object_collateral_checked_ids'][$i]));
            }
        }

        $context->getActionMessage("Removed " . $collateral_id_count . " collateral(s).");
        $context->setForward(dirname(__FILE__) . "/" . $collateral_collection->getEditor());
    }
}

?>