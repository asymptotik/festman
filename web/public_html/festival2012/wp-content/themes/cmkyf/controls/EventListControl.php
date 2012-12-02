<?php

require_once CMKYF_PLUGIN_BASE_DIR . '/objects/IControl.php';
require_once get_template_directory() . '/controls/EventListingCompact.php';

class EventListControl implements IControl
{

    private $events = NULL;

    public function __construct(array $events)
    {
        $this->events = $events;
    }

    public function getDefaultId()
    {
        $ret = -1;

        if (isset($this->events) && count($this->events) > 0)
        {
            $event = $this->events[0];
            $ret = $event->getId();
        }

        return $ret;
    }

    public function render()
    {
        if (count($this->events) > 1)
        {
            echo "<ul class=\"cf-events\">\n";
            for ($eventIndex = 0; $eventIndex < count($this->events); $eventIndex++)
            {
                echo "<li>";
                $event = $this->events[$eventIndex];
                $event_listing = new EventListingCompact($event);
                $event_listing->render();
                echo "</li>\n";
            }

            echo "</ul>\n";
        }
    }
}

?>