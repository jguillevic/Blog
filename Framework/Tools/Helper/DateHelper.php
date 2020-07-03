<?php

namespace Framework\Tools\Helper;

class DateHelper
{
    public static function GetDayFromDate($date)
    {
        $dayOfWeekInInt = strftime("%w", $date->getTimeStamp());

        switch ($dayOfWeekInInt)
        {
            case 0:
                $dayOfWeek = "sunday";
                break;
            case 1:
                $dayOfWeek = "monday";
                break;
            case 2:
                $dayOfWeek = "tuesday";
                break;
            case 3:
                $dayOfWeek = "wednesday";
                break;
            case 4:
                $dayOfWeek = "thursday";
                break;
            case 5:
                $dayOfWeek = "friday";
                break;
            case 6:
                $dayOfWeek = "saturday";
                break;
            default:
                $dayOfWeek = "monday";
                break;
        }

        return $dayOfWeek;
    }
}