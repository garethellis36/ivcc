<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class MatchesPlayersFixture extends TestFixture
{
    public $import = [
        "table" => "matches_players",
    ];

    public $records = [];

    public function init()
    {
        $this->records = [
            $this->matchData(1, 1, 1, 20, 1, 5, 1, 20, 0, 2, 2),
            $this->matchData(1, 2, 2, 15, 1, 0, 0, 0, 0, 0, 1),
            $this->matchData(1, 3, 3, 182, 1, 2, 0, 15, 0, 1, 1),
            $this->matchData(1, 4, 4, 75, 1, 9, 5, 18, 4, 3, 1),
            $this->matchData(1, 5, 5, 0, 1, 5, 0, 17, 4, 4, 1),
        ];

        parent::init();
    }

    private function matchData(
        $matchId,
        $playerId,
        $battingOrderNo,
        $battingRuns,
        $modesOfDismissalId,
        $bowlingOvers,
        $bowlingMaidens,
        $bowlingRuns,
        $bowlingWickets,
        $bowlingOrderNo,
        $catches,
        $didNotBat = 0
    )
    {
        return [
            "match_id"              => $matchId,
            "player_id"             => $playerId,
            "batting_order_no"      => $battingOrderNo,
            "did_not_bat"           => $didNotBat,
            "batting_runs"          => $battingRuns,
            "modes_of_dismissal_id" => $modesOfDismissalId,
            "bowling_overs"         => $bowlingOvers,
            "bowling_maidens"       => $bowlingMaidens,
            "bowling_runs"          => $bowlingRuns,
            "bowling_wickets"       => $bowlingWickets,
            "bowling_order_no"      => $bowlingOrderNo,
            "catches"               => $catches,
        ];
    }
}
