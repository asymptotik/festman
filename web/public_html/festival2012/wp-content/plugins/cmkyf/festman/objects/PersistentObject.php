<?php

abstract class PersistentObject
{
    protected $DoDelete = false;
    protected $IsDirty = false;

    public function getDoDelete()
    {
        return $this->DoDelete;
    }

    public function setDoDelete($value)
    {
        $this->DoDelete = $value;
    }

    public function isDirty()
    {
        return $this->IsDirty;
    }

    public function setIsDirty($value)
    {
        $this->IsDirty = $value;
    }

    abstract public function getId();

    abstract public function getObjectTable();

    abstract public function getDisplayName();

    abstract public function getEditor();
}

?>