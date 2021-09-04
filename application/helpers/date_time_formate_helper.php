<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
function xTimeAgo ($oldTime, $newTime, $timeType)
    {
        $timeCalc = strtotime($newTime) - strtotime($oldTime);
        
        if($timeType == "x")
        {
            if ($timeCalc >= 0) {
                $timeType = "s";
            }
            if ($timeCalc > 60) {
                $timeType = "m";
            }
            if ($timeCalc > (60*60)) {
                $timeType = "h";
            }
            if ($timeCalc > (60*60*24)) {
                $timeType = "d";
            }
        }
        if ($timeType == "s") {
            $timeCalc .= " seconds ago";
        }
        if ($timeType == "m") {
            $timeCalc = round($timeCalc/60) . " minutes ago";
        }
        if ($timeType == "h") {
            $timeCalc = round($timeCalc/60/60) . " hours ago";
        }
        if ($timeType == "d") {
            $timeCalc = round($timeCalc/60/60/24) . " days ago";
        }
        return $timeCalc;
  	}
?>