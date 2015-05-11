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
}