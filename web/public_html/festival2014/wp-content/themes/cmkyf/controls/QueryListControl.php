<?php

class QueryListControl
{
	private $query = NULL;
	private $map = NULL;
	
	public function __construct($query)
	{
		$this->query = $query;
		$this->map = array();
	}
	
	public function render()
	{
		if($this->query->have_posts())
		{
			$n = 0;
			
			echo '<ul>' . "\n";
			
			while ($this->query->have_posts()) {
				
				$this->query->the_post(); 
				$this->map[get_the_ID()] = $n;
				
				echo '<li><a href="#about-'; the_id(); echo '" rel="history" title="'; the_title_attribute(); echo '">'; the_title(); echo '</a></li>' . "\n";
				$n += 1;
			}

			echo '</ul>' . "\n";
		}
	}
	
	public function getMap()
	{
		return $this->map;
	}
}
?>