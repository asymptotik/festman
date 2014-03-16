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

class ItemListingCompact implements IControl
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
        $object_class = $item->getObjectClass();
        
        if ($default_image != NULL)
            $image_url = CMKYF_PLUGIN_BASE_URL . "/" . $default_image->getUrl();

        if (!isset($image_url))
            $image_url = cmkyf_image_url('unknown-item.png');
            
        $events = array();
        
        $item_display_name = strtolower(ProgramItem::getObjectClassDisplayName($item->getObjectClass()));
    
        ob_start();
        
        ?>
            <div class="cf-nugget-compact">
                <div class="cf-nugget-hero">
                    <div class="cf-nugget-rollover">
                        click for more info
                    </div>
                    <?php
                        if ($image_url != "")
                        {
                        echo "<a href=\"" . '#!/' . $item_display_name . '/' . $item->getId() . "\"><img class=\"cf-nugget-image\" src=\"" . $image_url . "\" /></a>";
                        }
                        ?>
                </div>

                <div class="cf-nugget-header">
                    <h2><?php echo $item->getName() ?></h2>
                    <?php if($object_class === 'Act') { ?>
                    <div class="cf-nugget-origin"><?php echo $item->getOrigin() ?></div>
                    <?php } ?>
                </div>

                <div class="cf-nugget-body">
                    <div class="cf-nugget-content">
                        <?php echo cmkyf_string_excerpt_with_more( $item->getDescription() , 140, 'cf-more', '#' . $item_display_name . '/' . $item->getId()) ?>
                        <div class="clear"></div>
                    </div>

                    <div class="cf-nugget-details">

                        <div class="cf-nugget-url"><a href ="<?php echo $item->getUrl() ?>" class="page-details-link" target=\"_blank\"><?php echo $item->getUrlDisplayText() ?></a></div>
                        <div class="cf-nugget_audio">
                            <?php
                            $audio_list = $item->getAllAudio();

                            for ($audio_index = 0;
                            $audio_index < count($audio_list);
                            $audio_index++)
                            {
                            $audio = $audio_list[$audio_index];
                            $audioUrl = CMKYF_PLUGIN_BASE_URL . $audio->getLocation() . '/' . $audio->getName();
                            echo "<div id=\"soundclip\"><a href=\"" . $audioUrl . "\" class=\"page-details-link\" target=\"_blank\">listen</a></div>";
                            }
                            ?>	
                        </div>
                    </div>
                    <?php
                        $events = $item->getEvents();
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
                                $current_location = $event->getLocation();
                               
                                echo "<div class=\"cf-nugget-performance\">";
                                echo "<a class=\"cf-nugget-event-name\" href=\"#!/event/" . $event->getId() . "\">" . $event->getName() . "</a> ";
                                echo "<span class=\"cf-nugget-event-day\">" . $day . " at </span><span class=\"page-time\">" . $time . "</span> ";
                                
                                if(defined($current_location) && isset($current_location))
                                {
                                    echo "<a class=\"cf-nugget-event-location\" href=\"#!/venue/" . $current_location->getId() . "\">" . $current_location->getName() . "</a>\n";
                                }
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        else if($object_class !== 'Installation')
                        {
                            echo "<div class=\"cf-nugget-performance\">";
                                echo "<span class=\"cf-nugget-event-name\">" . $item->getName() . " is not yet scheduled.</span> ";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div> <!-- cf-nugget -->
        <?php
        $ret = ob_get_contents();
        ob_end_clean();
        
        return $ret;
    }
}
?>