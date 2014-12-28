<?php

class Messaging
{

    private $error_message = '';
    private $action_message = '';

    public function addErrorMessage($message)
    {
        $this->error_message = fmAppendLine($this->error_message, $message);
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    public function addActionMessage($message)
    {
        $this->action_message = fmAppendLine($this->action_message, $message);
    }

    public function getActionMessage()
    {
        return $this->action_message;
    }

    public function hasError()
    {
        return $this->error_message != '';
    }
}

?>