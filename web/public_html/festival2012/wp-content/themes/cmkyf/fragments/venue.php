<?php
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', false);

/** Loads the WordPress Environment and Template */
require('../../../../wp-blog-header.php');

require_once CMKYF_PLUGIN_BASE_DIR . '/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Utils.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Location.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Event.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Workshop.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Installation.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Program_ProgramItem.php';
require_once get_template_directory() . '/controls/VenueListingFull.php';

if (isset($_GET["id"]))
{
    $current_venue_id = $_GET["id"];
    $current_venue = Location::getLocation($current_venue_id);
    $image_url = "";
}

if (isset($current_venue))
{
    $default_image = $current_venue->getDefaultImage();

    if ($default_image != NULL)
    {
        $image_url = $default_image->getUrl();
    }
}

if (isset($current_venue))
{
    $listing = new VenueListingFull($current_venue);
    $listing->render();
}
else
{
    ?>
    <h2 class="center">Not Found</h2>
    <p class="center">Sorry, but you are looking for something that isn't here.</p>
    <?php get_search_form(); ?>
    <?php
}

if (isset($_SESSION['action_message']))
{
    echo "<BR/><TABLE align=\"center\" class=\"border\" WIDTH=\"400\"><TR><TD>" . $_SESSION['action_message'] . "</TD></TR></TABLE>";
}
?>