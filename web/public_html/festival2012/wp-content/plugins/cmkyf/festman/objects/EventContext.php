<?php

// The EventContext has nothing to do with festival events, 
// but rather has to do with UI action events triggered from form posts.

require_once dirname(__FILE__) . '/Messaging.php';

class EventContext extends Messaging
{

    private $event_name = '';
    private $forward = '';

    function __construct($event_name = '')
    {
        $this->event_name = $event_name;
    }

    public function getEventName()
    {
        return $this->event_name;
    }

    public function setEventName($value)
    {
        $this->event_name = $value;
    }

    public function setForward($value)
    {
        $this->forward = $value;
    }

    public function getForward()
    {
        return $this->forward;
    }
}

?>