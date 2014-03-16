<?php

class Utils
{

    //
    // Converts from db time format yyyy-mm-dd hh:mm:ss to display format
    // mm/dd/yyyy hh:mm
    // 
    public static function getTime($db_time)
    {
        if (isset($db_time) == true && empty($db_time) == false)
        {
            $date_time = date_create($db_time);
            return date_format($date_time, "n/j/Y G:i");
        }
        else
        {
            return NULL;
        }
    }

    //
    // Converts from db time format to DateTime
    // 
    public static function getDateTime($db_time)
    {
        if (isset($db_time) == true && empty($db_time) == false)
        {
            return date_create($db_time);
        }
        else
        {
            return NULL;
        }
    }

    //
    // Converts from display time format mm/dd/yyyy hh:mm to display format
    // yyyy-mm-dd hh:mm:ss
    // 
    public static function setTime($display_time)
    {
        $value = NULL;

        if (isset($display_time) == true && empty($display_time) == false)
        {
            $timestamp = strtotime($display_time);
            $value = strftime("%Y-%m-%d %H:%M:%S", $timestamp);
        }

        return $value;
    }
}

?>