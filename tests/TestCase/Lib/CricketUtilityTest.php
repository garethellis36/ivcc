<?php
namespace App\Test\TestCase\Lib;

use App\Lib\CricketUtility;
use Cake\TestSuite\TestCase;


class CricketUtilityTest extends TestCase
{

    public $overs = [
        [
            "overs" => 1,
            "balls" => 6
        ],
        [
            "overs" => 3,
            "balls" => 18
        ],
        [
            "overs" => 5,
            "balls" => 30
        ],
        [
            "overs" => 2.1,
            "balls" => 13
        ],
        [
            "overs" => 2.2,
            "balls" => 14
        ],
        [
            "overs" => 4.3,
            "balls" => 27
        ],
        [
            "overs" => 6.4,
            "balls" => 40
        ],
        [
            "overs" => 5.5,
            "balls" => 35
        ],
    ];

    public function testConvertOversToBalls()
    {
        foreach ($this->overs as $over) {
            $this->assertEquals(CricketUtility::convertOversToBalls($over["overs"]), $over["balls"]);
        }
    }

    public function testConvertBallsToOvers()
    {
        foreach ($this->overs as $over) {
            $this->assertEquals(CricketUtility::convertBallsToOvers($over["balls"]), $over["overs"]);
        }
    }

}
