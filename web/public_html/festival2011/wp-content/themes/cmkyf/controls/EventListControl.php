<?php
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Event.php';

class EventListControl
{
	private $events = NULL;
	
	public function __construct(array $events)
	{
		$this->events = $events;
	}
	
	public function getDefaultId()
	{
		$ret = -1;
		
		if(isset($this->events) && count($this->events) > 0)
		{
			$event = $this->events[0];
			$ret = $event->getId();
		}
		
		return $ret;
	}
	
	public function render()
	{
		if(count($this->events) > 1)
		{
			echo "<div class=\"scroll-pane\">\n";
			echo "<ul class=\"sub-menu\">\n";
			for($eventIndex = 0; $eventIndex < count($this->events); $eventIndex++)
			{
				$event = $this->events[$eventIndex];

			    echo "<li><a href=\"#event-" . $event->getId() . "\" rel=\"history\">" . $event->getName() . "</a></li>\n";
			}

			echo "</ul>\n";
			echo "</div>\n";
		}
	}
}
?>