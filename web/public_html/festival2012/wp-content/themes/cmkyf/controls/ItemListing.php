<?php
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
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

class ItemListing implements IControl
{
    private $item = NULL;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function render()
    {
        echo $this->getContent();
    }
            
    private function getContent()
    {
        $item = $this->item;
        $default_image = $item->getDefaultImage();

        if ($default_image != NULL)
            $image_url = $default_image->getUrl();

        $events = array();

        ob_start();
        
        ?>
            <div class="cf-item-nugget">
                <div class="cf-item-nugget-hero">
                    <?php
                        if ($image_url != "")
                        {
                        echo "<img class=\"cf-item-nugget-image\" src=\"" . CMKYF_PLUGIN_BASE_URL . "/" . $image_url . "\" />";
                        }
                        ?>
                </div>
                
                <div class="cf-item-nugget-header">
                    <h2><?php echo $item->getName() ?></h2>
                    <div class="cf-item-nugget-origin"><?php echo $item->getOrigin() ?></div>
                </div>

                <div class="cf-item-nugget-body">
                    <div class="cf-item-nugget-content">
                        
                        <?php echo cmkyf_string_excerpt( $item->getDescription() , 140 ) ?>
                        <div class="clear"></div>
                    </div>

                    <div class="cf-item-nugget-details">

                        <div class="cf-item-nugget_url"><a href ="<?php echo $item->getUrl() ?>" class="page-details-link" target=\"_blank\"><?php echo $item->getUrlDisplayText() ?></a></div>
                        <div class="cf-item-nugget_audio">
                            <?php
                            $audio_list = $item->getAllAudio();

                            for ($audio_index = 0;
                            $audio_index < count($audio_list);
                            $audio_index++)
                            {
                            $audio = $audio_list[$audio_index];
                            $audioUrl = CMKYF_PLUGIN_BASE_URL . $audio->getLocation() . '/' . $audio->getName();
                            echo "<div id=\"soundclip\"><a href=\"" . $audioUrl . "\" class=\"page-details-link\" target=\"_blank\">" . $audio->getName() . "</a></div>";
                            }
                            ?>	
                        </div>
                    </div>
                    <?php
                        $events = $item->getEvents();
                        if (count($events) > 0)
                        {
                            echo "<div class=\"cf-item-nugget-performance\">";
                            $add_line = false;
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
                                $current_location = $event->getLocation();

                                if ($add_line == true)
                                echo "<br><br>";
                                else
                                $add_line = true;

                                echo "<a class=\"cf-item-nugget-event-name\" href=\"?page_id=4#event-" . $event->getId() . "\">" . $event->getName() . "</a> ";
                                echo "<span class=\"cf-item-nugget-event-day\">" . $day . " Starting at </span><span class=\"page-time\">" . $time . "</span> ";
                                echo "<a class=\"cf-item-nugget-event-location\" href=\"?page_id=4#venue-" . $current_location->getId() . "\">" . $current_location->getName() . "</a>\n";
                            }
                            echo "</div>";
                        }
                        else
                        {
                        echo "<div class=\"cf-item-nugget-performance\">";
                            echo "<span class=\"cf-item-nugget-event-name\">" . $item->getName() . " is not yet scheduled.</span> ";
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