<?php
/**
 * Created by PhpStorm.
 * User: Taras
 * Date: 25.12.2018
 * Time: 19:35
 */

namespace App;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comments";

    static function index()
    {

        $comments = Comment::where(['parent_id' => 0])->get();
        foreach ($comments as $key => $comment) {
            $children = Comment::where(['parent_id' => $comment['id']])->get();
            if (count($children) > 0) {
                $comments[$key]['children'] = $children;
            }
        }
        return $comments;
    }

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

}