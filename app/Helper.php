<?php


namespace App;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;

class Helper extends Model
{
    static function get_time_passed($date)
    {
        $date = new DateTime($date, new DateTimeZone('Europe/Kiev'));
        $interval = time() - $date->getTimestamp();

        if ($interval < 0) {
            $interval = 0;
        }
        if ($interval < 60) {
            $range = $interval . " seconds";
        } else {
            $temp_interval = floor($interval / 60);
            if ($temp_interval < 60) {
                $range = $temp_interval . " minutes";
            } else {
                $temp_interval = floor($interval / (60 * 60));
                if ($temp_interval < 24) {
                    $range = $temp_interval . " hours";
                } else {
                    $temp_interval = floor($interval / (60 * 60 * 24));
                    if ($temp_interval < 7) {
                        $range = $temp_interval . " days";
                    } else {
                        $temp_interval = floor($interval / (60 * 60 * 24 * 7));
                        if ($temp_interval < 5) {
                            $range = $temp_interval . " weeks";

                        } else {
                            $temp_interval = floor($interval / (60 * 60 * 24 * 30));
                            if ($temp_interval < 12) {
                                $range = $temp_interval . " months";
                            } else {
                                $temp_interval = floor($interval / (60 * 60 * 24 * 365));
                                $range = $temp_interval . " years";
                            }
                        }
                    }
                }
            }
        }
        return $range;
    }

    static function getUserAgent(){
        $user_agent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
        elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
        elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
        elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
        elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
        else $browser = "Неизвестный";
        return $browser;
    }
}