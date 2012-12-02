<?php

require_once CMKYF_PLUGIN_BASE_DIR . '/objects/IControl.php';
require_once get_template_directory() . '/controls/VenueListingCompact.php';

class VenueListControl implements IControl
{
    private $locations = NULL;

    public function __construct(array $locations)
    {
        $this->locations = $locations;
    }

    public function getDefaultId()
    {
        $ret = -1;

        if (isset($this->locations) && count($this->locations) > 0)
        {
            $location = $this->locations[0];
            $ret = $location->getId();
        }

        return $ret;
    }

    public function render()
    {
        if (count($this->locations) > 1)
        {
            echo "<ul class=\"cf-locations\">\n";
            for ($locationIndex = 0; $locationIndex < count($this->locations); $locationIndex++)
            {
                echo "<li>";
                $location = $this->locations[$locationIndex];
                $item_listing = new VenueListingCompact($location);
                $item_listing->render();
                echo "</li>\n";
            }

            echo "</ul>\n";
        }
    }
}

?>