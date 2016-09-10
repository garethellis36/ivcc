<?php

namespace App\Stats;

use App\Model\Entity\Match;
use App\Model\Entity\MatchesPlayer;
use App\Model\Table\MatchesPlayersTable;
use App\Model\Table\MatchesTable;
use Cake\Collection\Collection;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\TableRegistry;

class StatsGenerator
{
    /**
     * @var MatchesTable
     */
    private $matchesTable;

    /**
     * @var MatchesPlayersTable
     */
    private $matchesPlayersTable;

    public function __construct()
    {
        $this->matchesTable        = TableRegistry::get("Matches");
        $this->matchesPlayersTable = TableRegistry::get("MatchesPlayers");
    }

    /**
     * @param $year
     * @param $formatId
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function getAllMatches($year = "all", $formatId = "all")
    {
        $where = [];
        if ($year != "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "]  = $year + 1 . "-01-01";
        }

        if ($formatId != "all") {
            $where["Matches.format_id"] = $formatId;
        }

        return $this->matchesTable->find("all")
            ->where($where)
            ->contain([
                "MatchesPlayers" => [
                    "Players",
                    "ModesOfDismissal",
                ],
            ])
            ->all();
    }

    public function getTeamStats($year = "all", $formatId = "all")
    {
        $all                   = $this->getAllMatches($year, $formatId);
        $stats["results"]      = $this->getResults($all);
        $stats["highestScore"] = $this->getHighestTeamScore($all);
        $stats["lowestScore"]  = $this->getLowestTeamScore($all);

        if ($year != "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "]  = $year + 1 . "-01-01";
        }

        if ($formatId != "all") {
            $where["Matches.format_id"] = $formatId;
        }

        $contain = [
            "Matches",
            "Players" => [
                "fields" => ["Players.initials", "Players.last_name"],
            ],
            "ModesOfDismissal",
        ];

        $options = [
            "contain" => $contain,
            "where"   => (isset($where) ? $where : []),
        ];

        return array_merge($stats, $this->_getTeamStats($options));
    }

    private function _getTeamStats($options)
    {
        $stats = [];

        $stats["mostApps"] = $this->matchesPlayersTable->find("mostApps", $options);

        $stats["leadingRunScorer"]   = $this->matchesPlayersTable->find("leading", array_merge($options, ["field" => "batting_runs"]));
        $stats["leadingWicketTaker"] = $this->matchesPlayersTable->find("leading", array_merge($options, ["field" => "bowling_wickets"]));
        $stats["mostCatches"]        = $this->matchesPlayersTable->find("leading", array_merge($options, ["field" => "catches"]));

        $stats["highestIndividualScore"] = $this->getHighestIndividualScore($this->getAllMatches());

        for ($i = 1; $i <= 11; $i++) {
            $scoreByPositionOptions                                             = $options;
            $scoreByPositionOptions["where"]["MatchesPlayers.batting_order_no"] = $i;
            $stats["highestIndividualScoreByPosition"][$i]                      = $this->matchesPlayersTable->find("highestIndividualScore", $scoreByPositionOptions);
        }

        $stats["bestBowling"] = $this->getBestBowlingFigures($this->getAllMatches());

        $stats["hundreds"] = $this->matchesPlayersTable->find("hundreds", $options);
        $stats["fifties"]  = $this->matchesPlayersTable->find("fifties", $options);
        $stats["fivefors"] = $this->matchesPlayersTable->find("fivefors", $options);
        $stats["ducks"]    = $this->matchesPlayersTable->find("ducks", $options);

        return $stats;
    }

    public function getResults(ResultSetInterface $all)
    {
        $matches = clone $all;

        return [
            "P" => iterator_count($matches->filter(function (Match $match) {
                return !empty($match->result);
            })),
            "W" => iterator_count($matches->filter(function (Match $match) {
                return $match->result === "Won";
            })),
            "L" => iterator_count($matches->filter(function (Match $match) {
                return $match->result === "Lost";
            })),
        ];
    }

    public function getHighestTeamScore(ResultSetInterface $all)
    {
        $matches = clone $all;
        return $matches->filter(function (Match $match) {
            return $match->ivcc_total !== null;
        })->sortBy('ivcc_total', SORT_DESC)
            ->first();
    }

    public function getLowestTeamScore(ResultSetInterface $all)
    {
        $matches = clone $all;
        return $matches->filter(function (Match $match) {
            return $match->ivcc_total !== null;
        })->sortBy('ivcc_total', SORT_ASC)
            ->first();
    }

    private function getHighestIndividualScore(ResultSetInterface $all)
    {
        $matches = clone $all;

        //filter out games with no players
        return $matches->filter(function (Match $match) {
            return !empty($match->matches_players);
        })
            //for each game, find the highest score
            ->map(function (Match $match) {
                $collection                    = new Collection($match->matches_players);
                $match->highestIndividualScore = $collection
                    ->sortBy("batting_runs")
                    ->first();
                return $match;
            })
            //sort overall collection by highest score in the game
            ->sortBy(function (Match $match) {
                return $match->highestIndividualScore->batting_runs;
            })
            //get the first match
            ->first();
    }

    private function getBestBowlingFigures(ResultSetInterface $all)
    {
        $matches = clone $all;

        $matchWithMostWickets = $matches->filter(function (Match $match) {
            return !empty($match->matches_players);
        })
            //for each game, find the best bowling performance by number of wickets
            ->map(function (Match $match) {
                $collection = new Collection($match->matches_players);

                //find most number of wickets taken in match
                $mostWickets = $collection
                    ->sortBy("bowling_wickets")
                    ->first();

                if (!$mostWickets || $mostWickets->bowling_wickets === null) {
                    $match->bestBowling = null;
                    return $match;
                }

                //get players who took this number of wickets in this match
                $match->bestBowling = $collection->filter(function (MatchesPlayer $player) use ($mostWickets) {
                    return $player->bowling_wickets == $mostWickets->bowling_wickets;
                })
                    //sort remaining records by number of runs conceded
                    ->sortBy("bowling_runs", SORT_ASC)
                    //return the record
                    ->first();

                return $match;
            })
            //sort collection by best bowling to get most number of wickets taken in a match
            ->sortBy(function (Match $match) {
                return $match->bestBowling->bowling_wickets;
            })
            ->first();

        if (!$matchWithMostWickets || !$matchWithMostWickets->bestBowling || $matchWithMostWickets->bestBowling->bowling_wickets === null) {
            return null;
        }

        //get all the matches with this number of wickets taken
        return $matches->filter(function (Match $match) use ($matchWithMostWickets) {
            return $match->bestBowling
            && $match->bestBowling->bowling_wickets !== null
            && $match->bestBowling->bowling_wickets == $matchWithMostWickets->bestBowling->bowling_wickets;
        })
            //sort results so that the match with the fewest runs conceded in the best bowling figures is first
            ->sortBy(function (Match $match) {
                return $match->bestBowling->bowling_runs;
            }, SORT_ASC)
            //return the first match in the collection
            ->first();
    }
}