<?php

	require_once CMKYF_PLUGIN_BASE_DIR.'/objects/IControl.php';
	
class BannerControl implements IControl
{

	// edit banners here. make sure last array doesn't have a comma after the closing paretheses ")" 
	private $banners = array(	
							array( 	image => "images/banners/banner1.gif", 
									url => "http://www.plasticsoundsupply.com"),
                    		array( 	image => "images/banners/banner2.gif", 
									url => "http://www.transmediale.de"),
                    		array( 	image => "images/banners/banner3.gif", 
									url => "http://www.mutek.ca"),
                    		array( 	image => "images/banners/banner4.gif", 
									url => "http://www.residentadvisor.net/"),
							array( 	image => "images/banners/xlr8r.gif", 
									url => "http://www.xlr8r.com/podcast"));
                 
	public function __construct()
	{		
	}

	public function render()
	{
		$randnum = rand(0, count($this->banners) - 1); // Choose a random banner

		$img = $this->banners[$randnum]["image"]; // Grab the image for the banner
		$url = $this->banners[$randnum]["url"]; // Grab the URL for the banner

		// Display the banner
		echo "<a href=\"$url\" target=\"_blank\"><img border=\"0\" src=\"$img\" /></a>";
	}
}
?>