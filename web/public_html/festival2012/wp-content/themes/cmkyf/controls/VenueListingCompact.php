<?php
require_once CMKYF_PLUGIN_BASE_DIR . '/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR . '/objects/IControl.php';
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

class VenueListingCompact implements IControl
{
    private $venue = NULL;

    public function __construct($venue)
    {
        $this->venue = $venue;
    }

    public function render()
    {
        echo $this->getContent();
    }

    private function getContent()
    {
        $current_venue = $this->venue;
        $default_image = $current_venue->getDefaultImage();

        if ($default_image != NULL)
            $image_url = CMKYF_PLUGIN_BASE_URL . "/" . $default_image->getUrl();
        
        if (!isset($image_url))
            $image_url = cmkyf_image_url('unknown-item.png');
        
        ob_start();
        ?>

        <div class="cf-nugget-compact">
            <div class="cf-nugget-hero">
                <?php
                if ($image_url != "")
                {
                    echo "<a href=" . '#!/venue/' . $current_venue->getId() . "><img class=\"cf-nugget-image\" src=\"" . $image_url . "\" /></a>";
                }
                ?>
            </div>

            <div class="cf-nugget-header">
                <h2><?php echo $current_venue->getName() ?></h2>
            </div>

            <div class="cf-nugget-body">
                <div class="cf-nugget-content">
                    <?php echo cmkyf_string_excerpt_with_more( $current_venue->getDescription() , 140, 'cf-more', '#!/venue/' . $current_venue->getId()) ?>
                    <div class="clear"></div>
                </div>

                <div class="cf-nugget-details">

                    <div class="cf-nugget-address">
                        <?php
                        echo '<a href ="' . $current_venue->getUrl() . '" target="_blank" class="page-details-link">' . $current_venue->getName() . '</a><br/>';
                        echo $current_venue->getAddress() . '<br/>';
                        echo $current_venue->getCity() . ', ' . $current_venue->getState() . ' ' . $current_venue->getZipCode() . '<br/>';
                        echo $current_venue->getPhoneNumber();
                        ?>
                    </div>
                    <?php 
                    $map_url = $current_venue->getMapUrl();
                    if(!empty($map_url)) { ?>
                    <div class="cf-nugget-map">
                        <?php
                        echo '<a href ="' . $map_url . '" class="page-details-link" target=\"_blank\">' . $current_venue->getMapUrlDisplayText() . '</a><br/>';
                        ?>
                    </div>
                    <?php } ?>
                </div>


                <?php
                $events = $current_venue->getAllEvents();
                if (count($events) > 0)
                {
                    echo "<div class=\"cf-nugget-performance-list\">";
                    for ($i = 0; $i < count($events); $i++)
                    {
                        $event = $events[$i];
                        $date_time = Utils::getDateTime($event->getStartTime());
                        $day = "";
                        $month = "";
                        $year = "";
                        $day_of_month = "";

                        if (isset($date_time))
                        {
                            $day = date_format($date_time, "l");
                            $month = date_format($date_time, "F");
                            $year = date_format($date_time, "Y");
                            $day_of_month = date_format($date_time, "d");
                            $time = date_format($date_time, "g:i A");
                        }


                        $event->getName();
                        //$current_location = $event->getLocation();

                        echo "<div class=\"cf-nugget-performance\">";
                        echo "<a class=\"cf-nugget-event-name\" href=\"#!/event/" . $event->getId() . "\">" . $event->getName() . "</a> ";
                        echo "<span class=\"cf-nugget-event-day\">" . $day . " @ </span><span class=\"page-time\">" . $time . "</span> ";
                        echo "</div>";
                    }
                    echo "</div>";
                }
                else
                {
                    echo "<div class=\"page-performance\">";
                    echo "<span class=\"page-event-name\">" . $current_venue->getName() . " is not yet scheduled for any events.</span> ";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <?php
        $ret = ob_get_contents();
        ob_end_clean();

        return $ret;
    }
}
?>