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
            $this->assertEquals($over["balls"], CricketUtility::convertOversToBalls($over["overs"]));
        }
    }

    public function testConvertBallsToOvers()
    {
        foreach ($this->overs as $over) {
            $this->assertEquals($over["overs"], CricketUtility::convertBallsToOvers($over["balls"]));
        }
    }

    private $battingInputs = [
        [
            "runs" => 150,
            "innings" => 10,
            "not_outs" => 0,
            "average" => 15
        ],
        [
            "runs" => 12,
            "innings" => 2,
            "not_outs" => 1,
            "average" => 12
        ],
        [
            "runs" => 255,
            "innings" => 12,
            "not_outs" => 2,
            "average" => 25.5
        ],
        [
            "runs" => 75,
            "innings" => 2,
            "not_outs" => 2,
            "average" => false
        ],
    ];

    public function testCalculateBattingAverage()
    {
        foreach ($this->battingInputs as $input) {
            $this->assertEquals(
                $input["average"],
                CricketUtility::calculateBattingAverage(
                    $input["runs"],
                    $input["innings"],
                    $input["not_outs"]
                )
            );
        }

    }

    private $bowlingAverageInputs = [
        [
            "runs" => 100,
            "wickets" => 10,
            "average" => 10
        ],
        [
            "runs" => 50,
            "wickets" => 0,
            "average" => false
        ],
        [
            "runs" => 185,
            "wickets" => 10,
            "average" => 18.5
        ]
    ];

    public function testCalculateBowlingAverage()
    {
        foreach ($this->bowlingAverageInputs as $input) {
            $this->assertEquals(
                $input["average"],
                CricketUtility::calculateBowlingAverage(
                    $input["runs"],
                    $input["wickets"]
                )
            );
        }
    }

    private $bowlingEconomyInputs = [
        [
            "runs" => 50,
            "overs" => 5,
            "econ" => 10
        ],
        [
            "runs" => 50,
            "overs" => 0,
            "econ" => false
        ],
        [
            "runs" => 165,
            "overs" => 20,
            "econ" => 8.25
        ]
    ];

    public function testCalculateBowlingEconomy()
    {

        foreach ($this->bowlingEconomyInputs as $input) {
            $this->assertEquals(
                $input["econ"],
                CricketUtility::calculateBowlingEconomy(
                    $input["overs"],
                    $input["runs"]
                )
            );
        }
    }

}
