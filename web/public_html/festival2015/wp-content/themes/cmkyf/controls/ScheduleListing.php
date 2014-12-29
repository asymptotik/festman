<?php

require_once CMKYF_PLUGIN_BASE_DIR . '/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/library/utils.php';

require_once CMKYF_PLUGIN_BASE_DIR . '/objects/IControl.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Act.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Event.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Collateral.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Klass.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Location.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Panel.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Film.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Program_ProgramItem.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Program.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Workshop.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/objects/Installation.php';

require_once CMKYF_PLUGIN_BASE_DIR . '/library/config.php';
require_once CMKYF_PLUGIN_BASE_DIR . '/library/utils.php';

class ScheduleListing implements IControl
{
    private $events = NULL;

    public function __construct(array $events)
    {
        $this->events = $events;
    }

    public function render()
    {
        echo $this->getContent();
    }

    private function getContent()
    {
        ob_start();
        $events = $this->events;

        if (count($events) > 0)
        {
            $currentDayDate = new DateTime($events[0]->getStartTime());
            $currentDayStr = date_format($currentDayDate, "m/d/Y");
            $itemNum = 1;

            echo '<div class="schedule">' . "\n";
            echo '  <div class="schedule-column">' . "\n";
            echo '    <div class="cf-nugget-compact c' . (($itemNum % 5) + 1) . '-style">' . "\n";
            echo '      <div class="cf-nugget-header">';
            echo '        <h2>' . date_format($currentDayDate, "l") . '</h2>';
            echo '      </div>';
            
           
            echo '      <div class="cf-nugget-body">' . "\n";

            foreach ($events as $event)
            {
                $startTime = new DateTime($event->getStartTime());
                $endTime = new DateTime($event->getEndTime());
                $location = $event->getLocation();
                $program = $event->getProgram();

                $startTimeStr = date_format($startTime, "m/d/Y");
                $startHour = date_format($startTime, "G");

                if ($startTimeStr != $currentDayStr && $startHour >= 6)
                {
                    $itemNum += 1;
                    echo "      </div>\n"; // end page-panel-body for events
                    echo "    </div>\n"; // end day start a new one

                    if ($itemNum != 4)
                    {
                        echo '  </div>' . "\n"; // end schedule-column
                        echo '  <div class="schedule-column schedule-column-margin">' . "\n";
                    }

                    echo '    <div class="cf-nugget-compact c' . (($itemNum % 5) + 1) . '-style">' . "\n";
                    echo '      <div class="cf-nugget-header">';
                    echo '        <h2>' . date_format($startTime, "l") . '</h2>';
                    echo '      </div>';
                    echo '    <div class="cf-nugget-body">' . "\n";

                    $currentDayStr = $startTimeStr;
                }

                echo '    <div class="cf-nugget-content">' . "\n";
                echo '      <h3 class="name"><a href="#!/event/' . $event->getId() . '">' . $event->getName() . '</a></h3>' . "\n";

                if (isset($location))
                {
                    echo '      <div class="location">' . "\n";
                    echo '        <div class="name"><a href="#!/venue/' . $location->getId() . '">' . $location->getName() . '</a></div>' . "\n";
                    echo '        <div class="address">' . $location->getAddress() . '</div>' . "\n";
                    //echo '        <span class="city">' . $location->getCity() . '</span>';
                    //echo '        <span class="state">' . $location->getState() . '</span>';
                    //echo '        <span class="zip-code">' . $location->getZipCode() . '</span>' . "\n";
                    echo '      </div>' . "\n";
                }
                echo '      <div class="time">' . date_format($startTime, "g:i A") . ' - ' . date_format($endTime, "g:i A") . '</div>' . "\n";
                echo '      <ul class="program">' . "\n";
                //echo '        <div class="program-id">' . $program->getId() . '</div>' . "\n";

                $program->sortProgram_ProgramItems();
                $program_program_items = &$program->getProgram_ProgramItems();

                foreach ($program_program_items as $program_program_item)
                {
                    $program_item = $program_program_item->getProgramItem();
                    $date_time = Utils::getDateTime($program_program_item->getStartTime());
                    
                    if(!empty($date_time))
                        $time = date_format($date_time, "g:i") . ' ';
                    else
                        $time = '';
                    
                    // echo '          <div class="type">' . $program_item->getObjectClass() . '</div>' . "\n";
                    echo '          <li>' . $time . '<a href="#!/' . strtolower($program_item->getObjectClass()) . '/' . $program_item->getId() . '">' . $program_item->getName() . '</a></li>' . "\n";
                }

                echo '      </ul>' . "\n"; // program
                echo '    </div>' . "\n"; // event
            }

            echo '   </div>' . "\n"; // end panel-content for events
            echo '  </div>' . "\n"; // day
            echo '  </div>' . "\n"; // day
            echo '</div>' . "\n"; // schedule
            echo '<div class="clear"></div>';

            $ret = ob_get_contents();
            ob_end_clean();

            return $ret;
        }
    }
}
?>