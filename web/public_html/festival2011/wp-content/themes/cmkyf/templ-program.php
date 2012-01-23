<?php
/**
 * Template Name: Program Template
 */


wp_enqueue_script('jquery-history', get_template_directory_uri() . "/js/jquery-history.min.js", array(), false, true);
wp_enqueue_script('program-history', get_template_directory_uri() . "/js/program.js", array(), false, true);

get_header();

require_once CMKYF_PLUGIN_BASE_DIR.'/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/opendb.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Utils.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Location.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Event.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Workshop.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Installation.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR.'/objects/Program_ProgramItem.php';

require_once dirname(__FILE__).'/controls/ItemListControl.php';
require_once dirname(__FILE__).'/controls/EventListControl.php';
require_once dirname(__FILE__).'/controls/VenueListControl.php';

?>

<!-- program -->
<div class="left-column c3-style">
	<div id="performance-menu" class="corners-top corners-bottom page-panel-body c-border bottom-margin">
		<div class="panel-content">
			<div id="accordion">
				<div>
				<h3 id="item-menu"><a href="#">Artists</a></h3>
				<div><?php 
				$items = ProgramItem::getAllTypedProgramItems('Act');
				$default_index = 0;
				if(count($items) > 0)
				{
					$item_list_control = new ItemListControl($items, "act");
					$item_list_control->render();
				}
				?></div>
				</div>
				
				<div>
				<h3 id="item-menu"><a href="#">InterACT</a></h3>
				<div><?php 
				$items = ProgramItem::getAllTypedProgramItems('Workshop');
				$default_index = 0;
				if(count($items) > 0)
				{
					$item_list_control = new ItemListControl($items, "workshop");
					$item_list_control->render();
				}
				?></div>
				</div>
				
				<div>
				<h3 id="item-menu"><a href="#">Installations</a></h3>
				<div><?php 
				$items = ProgramItem::getAllTypedProgramItems('Installation');
				$default_index = 0;
				if(count($items) > 0)
				{
					$item_list_control = new ItemListControl($items, "installation");
					$item_list_control->render();
				}
				?></div>
				</div>
				
				<div>
				<h3 id="item-menu"><a href="#">Films</a></h3>
				<div><?php 
				$items = ProgramItem::getAllTypedProgramItems('Film');
				$default_index = 0;
				if(count($items) > 0)
				{
					$item_list_control = new ItemListControl($items, "film");
					$item_list_control->render();
				}
				?></div>
				</div>
				
				<div>
				<h3 id="event-menu"><a href="#">Events</a></h3>
				<div><?php 
				$events = Event::getAllEventsSortedByDate();
				if(count($events) > 0)
				{
					$events_list_control = new EventListControl($events);
					$events_list_control->render();
				}
				?></div>
				</div>
				
				<div>
				<h3 id="venue-menu"><a href="#">Venues</a></h3>
				<div><?php 
				$venues = Location::getAllLocations();
				if(count($venues) > 0)
				{
					$venue_list_control = new VenueListControl($venues);
					$venue_list_control->render();
				}
				?></div>
				</div>
				
				<div id="schedule-menu">
				<h3><a href="#schedule-1">Schedule</a></h3>
				<div><div class="scroll-pane">
				<ul class="sub-menu">
				<li><a href="#schedule-1" rel="history">Festival Overview</a></li>
				<li><a href="#program-449" rel="history">Festival Program Guide</a></li>
				</ul>
				</div>
				</div>
				</div>
			
			</div>
		</div>
	</div>
	
	<?php get_sidebar(2); ?>
</div>
	

<div id="content-container" class="narrowcolumn" role="main">
<div id="content"></div>
<div id="processing-wrapper">
<div id="processing"><img
	src="<?php echo cmkyf_image_url('ajax-loader.gif') ?>" /></div>
</div>
</div>
<div class="clear"></div>

<!-- ?php get_sidebar(); ? -->


<?php
cmkyf_set_section("program");
get_footer();

require_once CMKYF_PLUGIN_BASE_DIR.'/library/closedb.php';
?>