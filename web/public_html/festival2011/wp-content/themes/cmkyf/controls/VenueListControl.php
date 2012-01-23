<?php
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Location.php';

class VenueListControl
{
	private $locations = NULL;
	
	public function __construct(array $locations)
	{
		$this->locations = $locations;
	}
	
	public function getDefaultId()
	{
		$ret = -1;
		
		if(isset($this->locations) && count($this->locations) > 0)
		{
			$location = $this->locations[0];
			$ret = $location->getId();
		}
		
		return $ret;
	}
	
	public function render()
	{
		if(count($this->locations) > 1)
		{
			echo "<div class=\"scroll-pane\">\n";
			echo "<ul class=\"sub-menu\">\n";
			for($locationIndex = 0; $locationIndex < count($this->locations); $locationIndex++)
			{
				$location = $this->locations[$locationIndex];
			    echo "<li><a href=\"#venue-" . $location->getId() . "\" rel=\"history\">" . $location->getName() . "</a></li>\n";
			}

			echo "</ul>\n";
			echo "</div>\n";
		}
	}
}
?>