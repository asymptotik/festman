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

class EventListingFull implements IControl
{

    private $event = NULL;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function render()
    {
        echo $this->getContent();
    }

    private function getContent()
    {
        $current_event = $this->event;
        $default_image = $current_event->getDefaultImage();

        if ($default_image != NULL)
            $image_url = CMKYF_PLUGIN_BASE_URL . "/" . $default_image->getUrl();

        if (!isset($image_url))
            $image_url = cmkyf_image_url('unknown-item.png');

        $date_time = Utils::getDateTime($current_event->getStartTime());
        $day = "";
        $month = "";
        $year = "";
        $day_of_month = "";
        $time = "";

        if (isset($date_time))
        {
            $day = date_format($date_time, "l");
            $month = date_format($date_time, "F");
            $year = date_format($date_time, "Y");
            $day_of_month = date_format($date_time, "d");
            $time = date_format($date_time, "g:i A");
        }

        $current_location = $current_event->getLocation();

        ob_start();
        ?>

        <div class="cf-nugget">
            <div class="cf-nugget-col-left">
                <div class="cf-nugget-hero">
                    <?php
                    if ($image_url != "")
                    {
                        echo "<img class=\"cf-nugget-image\" src=\"" . $image_url . "\" />";
                    }
                    ?>
                </div>

                <?php if (isset($current_location))
                { ?>
                    <div class="cf-nugget-details">

                        <div class="cf-nugget-address">
                            <?php
                            echo '<a href ="#!/venue/' . $current_location->getId() . '" class="page-details-link">' . $current_location->getName() . '</a><br/>';
                            echo $current_location->getAddress() . '<br/>';
                            echo $current_location->getCity() . ', ' . $current_location->getState() . ' ' . $current_location->getZipCode() . '<br/>';
                            echo $current_location->getPhoneNumber();
                            ?>
                        </div>
                        <?php
                        $map_url = $current_location->getMapUrl();
                        if (!empty($map_url))
                        {
                            ?>
                            <div class="cf-nugget-map">
                            <?php
                            echo '<a href ="' . $map_url . '" class="page-details-link" target=\"_blank\">' . $current_location->getMapUrlDisplayText() . '</a><br/>';
                            ?>
                            </div>
            <?php } ?>
                    </div>
                <?php } ?>


                <?php
                $program = $current_event->getProgram();
                if (isset($program))
                {
                    echo "<div class=\"cf-nugget-features\">";
                    $program_program_items = &$program->getProgram_ProgramItems();
                    $add_sep = false;
                    for ($j = 0; $j < count($program_program_items); $j++)
                    {
                        $program_item = $program_program_items[$j]->getProgramItem();
                        if (isset($program_item))
                        {
                            if ($add_sep == true)
                            {
                                echo '<span class="item-sep"> | </span>';
                            }
                            else
                            {
                                $add_sep = true;
                            }

                            $item_name = strtolower($program_item->getObjectClass());
                            echo "<a class=\"item_namelist\" href=\"#!/" . $item_name . "/" . $program_item->getId() . "\">" . $program_item->getName() . "</a>\n";
                        }
                    }

                    echo "</div>";
                }
                ?>
            </div>

            <div class="cf-nugget-col-right">
                <div class="cf-nugget-header">
                    <h2><?php echo $current_event->getName() ?></h2>
                    <div class="cf-nugget-origin"><?php echo $day . ', ' . $month . ' ' . $day_of_month . ' ' . $year . ' at ' . $time ?></div>
                </div>

                <div class="cf-nugget-content">
                    <?php echo $current_event->getDescription() ?>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <?php
        $ret = ob_get_contents();
        ob_end_clean();

        return $ret;
    }
}
?>