<?php

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', false);

/** Loads the WordPress Environment and Template */
require('../../../../wp-blog-header.php');

require_once CMKYF_PLUGIN_BASE_DIR.'/library/config.php';
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

if(isset($_GET["event_id"]))
{
	$current_event_id = $_GET["event_id"];
	$current_event = Event::getEvent($current_event_id);
	$image_url = "";
}

if(isset($current_event))
{
	$default_image = $current_event->getDefaultImage();
	
	if($default_image != NULL)
	{
		$image_url = $default_image->getUrl();
	}
}

if(isset($current_event))
{
	$date_time = Utils::getDateTime($current_event->getStartTime());
	$day = "";
	$month = "";
	$year = "";
	$day_of_month = "";
	$time = "";
	
	if(isset($date_time))
	{
		$day = date_format($date_time, "l");
		$month = date_format($date_time, "F");
		$year = date_format($date_time, "Y");
		$day_of_month = date_format($date_time, "d");
		$time = date_format($date_time, "g:i A");
	}
?>

	<div class="page-panel c2-style">
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="page-panel-header c-border c-bg corners-top">
				<table class="panel-content">
					<tr>
						<td class="page-title"><h2><?php echo $current_event->getName() ?></h2></td>
						<td class="page-sep"></td>
						<td class="page-origin"><?php echo $day . ', ' . $month . ' ' . $day_of_month . ' ' . $year . ' at ' . $time ?></td>
					</tr>
				</table>
			</div>

			<div class="page-panel-body c-border corners-bottom">
				<div class="entry panel-content">
					<?php 
						if($image_url != "")
						{
						    echo "<img width=\"240\" height=\"240\" class=\"alignleft page-image\" src=\"" . CMKYF_PLUGIN_BASE_URL . "/" . $image_url . "\" />";
						}
				 	?>
					<?php echo $current_event->getDescription() ?>
					<div class="clear"></div>
				</div>
			
				<div class="page-details">
					<?php 
					$current_location = $current_event->getLocation();
					if(isset($current_location)) {
					?>
				    <table>
					    <tr>
							<td>
								<div class="left">
								    <?php
									    echo '<a href ="?page_id=4#venue-' . $current_location->getId() . '" class="page-details-link">' . $current_location->getName() . '</a><br/>';
									    echo $current_location->getAddress() . '<br/>';
									    echo $current_location->getCity() .', ' . $current_location->getState() . ' ' . $current_location->getZipCode() . '<br/>';
									    echo $current_location->getPhoneNumber();
									?>
								</div>
							</td>
							<td>
								<div class="right">
									<?php 
										echo '<a href ="' . $current_location->getMapUrl() . '" class="page-details-link" target=\"_blank\">' . $current_location->getMapUrlDisplayText() . '</a><br/>';
									 ?>	
								 </div>
							</td>
						</tr>
					</table>
					<?php } ?>
				</div>
				<?php
					$program = $current_event->getProgram();
					if(isset($program))
					{
						echo "<div class=\"page-performance\">";
						$program_program_items = &$program->getProgram_ProgramItems();
						$add_sep = false;
						for($j = 0; $j < count($program_program_items); $j++)
						{
							$program_item = $program_program_items[$j]->getProgramItem();
							if(isset($program_item))
							{
								if($add_sep == true)
								{
									echo '<span class="item-sep"> | </span>';
								}
								else
								{
									$add_sep = true;
								}
								
								$item_name = strtolower($program_item->getObjectClass());
								echo "<a class=\"item_namelist\" href=\"?page_id=4#" . $item_name ."-" . $program_item->getId() . "\">" . $program_item->getName() . "</a>\n";
							}
						}
						
						echo "</div>" ;
					}
				?>
			</div>
		</div>
	</div>

			
<?php 
}
else
{
?>
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>
<?php
}

if(isset($_SESSION['action_message']))
{
	echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>".$_SESSION['action_message']."</TD></TR></TABLE>";
}

?>