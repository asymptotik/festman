<?php

require_once CMKYF_PLUGIN_BASE_DIR . '/objects/IControl.php';
require_once get_template_directory() . '/controls/ItemListingCompact.php';

class ItemListControl implements IControl
{

    private $items = NULL;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getDefaultId()
    {
        $ret = -1;

        if (isset($this->items) && count($this->items) > 0)
        {
            $item = $this->items[0];
            $ret = $item->getId();
        }

        return $ret;
    }

    public function render()
    {
        if (count($this->items) > 0)
        {
            echo "<ul class=\"cf-program-items\">";
            for ($itemIndex = 0; $itemIndex < count($this->items); $itemIndex++)
            {
                echo "<li>";
                $item = $this->items[$itemIndex];
                $item_listing = new ItemListingCompact($item);
                $item_listing->render();
                echo "</li>\n";
            }

            echo "</ul>";
        }
    }
}

?>