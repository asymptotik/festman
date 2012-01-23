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

if(isset($_GET["venue_id"]))
{
	$current_venue_id = $_GET["venue_id"];
	$current_venue = Location::getLocation($current_venue_id);
	$image_url = "";
}

if(isset($current_venue))
{
	$default_image = $current_venue->getDefaultImage();
	
	if($default_image != NULL)
	{
		$image_url = $default_image->getUrl();
	}
}

if(isset($current_venue))
{
?>

	<div class="page-panel c5-style">
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="page-panel-header c-border c-bg corners-top">
				<table class="panel-content">
					<tr>
						<td class="page-title"><h2><?php echo $current_venue->getName() ?></h2></td>
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
					<?php echo $current_venue->getDescription() ?>
					<div class="clear"></div>
				</div>
				
				<div class="page-details">
				    <table>
					    <tr>
							<td>
								<div class="left">
								    <?php
									    echo '<a href ="' . $current_venue->getUrl(). '" class="page-details-link">' . $current_venue->getName() . '</a><br/>';
									    echo $current_venue->getAddress() . '<br/>';
									    echo $current_venue->getCity() .', ' . $current_venue->getState() . ' ' . $current_venue->getZipCode() . '<br/>';
									    echo $current_venue->getPhoneNumber();
									?>
								</div>
							</td>
							<td>
								<div class="right">
									<?php 
										echo '<a href ="' . $current_venue->getMapUrl() . '" class="page-details-link" target=\"_blank\">' . $current_venue->getMapUrlDisplayText() . '</a><br/>';
									 ?>	
								 </div>
							</td>
						</tr>
					</table>
				</div>
				<?php
				    $events = $current_venue->getAllEvents();
					if(count($events) > 0)
					{
						echo "<div class=\"page-performance\">";
						$add_line = false;
						for($i = 0; $i < count($events); $i++) 
						{
							$event = $events[$i];
							$date_time = Utils::getDateTime($event->getStartTime());
							$day = "";
							$month = "";
							$year = "";
							$day_of_month = "";
							
							if(isset($date_time))
							{
								$day = date_format($date_time, "l");
								$month = date_format($date_time, "F");
								$year = date_format($date_time, "Y");
								$day_of_month = date_format($date_time, "d");
								$time = date_format($date_time, "g:i A");
							}
							
							
							$event->getName();
							$current_location = $event->getLocation();
							
							if($add_line == true)
								echo "<br><br>";
							else
								$add_line = true;
							
							echo "<a class=\"page-event-name\" href=\"?page_id=4#event-" . $event->getId() . "\">" . $event->getName() . "</a> ";
							echo "<span class=\"page-day\">" . $day . " Starting at </span><span class=\"page-time\">" . $time . "</span> "; 
						} 
						echo "</div>" ;
					}
					else
					{
						echo "<div class=\"page-performance\">";
							echo "<span class=\"page-event-name\">" .$current_item->getName() . " is not yet scheduled for any events.</span> "; 
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