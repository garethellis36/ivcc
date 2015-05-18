<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 15/05/15
 * Time: 13:23
 */

namespace App\Lib;


class CricketUtility
{

    private static $ballsPerOver = 6;

    //converts from cricket over decimal notation to number of balls
    //e.g. 10.3 overs is 10 whole overs plus 3 balls, e.g. 63 balls
    public static function convertOversToBalls($overs)
    {
        $bits = explode(".", $overs);

        if (count($bits) == 1) {
            return ($overs * self::$ballsPerOver);
        }

        return ($bits[0] * self::$ballsPerOver) + $bits[1];

    }

    public static function convertBallsToOvers($balls)
    {
        $remainder = fmod($balls, self::$ballsPerOver);
        if ($remainder == 0) {
            return ($balls / self::$ballsPerOver);
        }

        $wholeOvers = floor($balls / self::$ballsPerOver);

        return $wholeOvers + ($remainder / 10);
    }


    public static function convertBallsToDecimal($balls)
    {
        return ($balls / self::$ballsPerOver);
    }

    public static function calculateBattingAverage($runs, $innings, $notOuts)
    {
        $dismissals = $innings - $notOuts;
        if ($dismissals == 0) {
            return false;
        }

        return round($runs / $dismissals, 2);
    }

    public static function calculateBowlingAverage($runs, $wickets)
    {
        if ($wickets == 0) {
            return false;
        }
        return round($runs / $wickets, 2);
    }

    public static function calculateBowlingEconomy($overs, $runs)
    {
        if ($overs == 0) {
            return false;
        }

        //we need overs in decimal form, e.g. 12.3 (12 overs & 3 balls) needs to be 12.5 (12 and a half)
        $overs = self::convertBallsToDecimal(self::convertOversToBalls($overs));
        return round($runs / $overs, 2);
    }

}