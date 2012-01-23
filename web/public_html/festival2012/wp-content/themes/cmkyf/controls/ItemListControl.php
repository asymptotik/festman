<?php
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Installation.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Workshop.php';

class ItemListControl
{
	private $items = NULL;
	private $mprefix = NULL;
	
	public function __construct(array $items, $mprefix)
	{
		$this->items = $items;
		$this->mprefix = $mprefix;
	}
	
	public function getDefaultId()
	{
		$ret = -1;
		
		if(isset($this->items) && count($this->items) > 0)
		{
			$item = $this->items[0];
			$ret = $item->getId();
		}
		
		return $ret;
	}
	
	public function render()
	{
		if(count($this->items) > 0)
		{
			echo "<div class=\"scroll-pane\">\n";
			echo "<ul class=\"sub-menu\">\n";
			for($itemIndex = 0; $itemIndex < count($this->items); $itemIndex++)
			{
				$item = $this->items[$itemIndex];
			    echo "<li><a href=\"#" . $this->mprefix . "-" . $item->getId() . "\" rel=\"history\">" . $item->getName() . "</a></li>\n";
			}

			echo "</ul>\n";
			echo "</div>\n";
		}
	}
}
?>