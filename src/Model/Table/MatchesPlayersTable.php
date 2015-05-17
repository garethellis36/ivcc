<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:08
 */

namespace App\Model\Table;

use Cake\Datasource\Exception\RecordNotFoundException;
use App\Model\Table\AppTable;
use Cake\ORM\Query;
use App\Lib\CricketUtility;
use Cake\Validation\Validator;

class MatchesPlayersTable extends AppTable {

    public function initialize(array $config)
    {
        $this->belongsTo("Matches");
        $this->belongsTo("Players");
        $this->belongsTo("ModesOfDismissal");

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('player_id', 'valid', ['rule' => 'notBlank'])
            ->notEmpty("player_id");

        $validator
            ->add('did_not_bat', 'valid', ['rule' => 'boolean']);


        //these rules apply to both batting order no and bowling order no
        $isNumericAndNotDecimal = "/^[0-9]*$/";

        $validNumber = [
            'rule' => ['custom', $isNumericAndNotDecimal],
            'message' => 'Number only, no decimals'
        ];

        $validOrderNo = [
            'validNumber' => $validNumber,
            'validRange' => [
                'rule' => ['range', 1, 11],
                'message' => 'Must be between 1 and 11'
            ]
        ];

        $validator
            ->notEmpty('batting_order_no')
            ->add('batting_order_no', $validOrderNo);

        $validator
            ->allowEmpty('batting_runs', function ($context) {
                return $this->hasBatted($context);
            })
            ->add('batting_order_no', 'validNumber', $validNumber);

        $validator
            ->allowEmpty('modes_of_dismissal_id', function ($context) {
                return $this->hasBatted($context);
            })
            ->add('modes_of_dismissal_id', 'validNumber', $validNumber);


        $validator
            ->allowEmpty('bowling_order_no', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_order_no', $validOrderNo);

        $validator
            ->allowEmpty('bowling_overs', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_overs', 'validOvers', [
                'rule' => [$this, 'validOvers']
            ]);

        $validator
            ->allowEmpty('bowling_maidens', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_maidens', 'validNumber', $validNumber);

        $validator
            ->allowEmpty('bowling_runs', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_runs', 'validNumber', $validNumber);

        $validator
            ->allowEmpty('bowling_wickets', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_wickets', 'validNumber', $validNumber);


        return $validator;

    }

    private function hasBatted($context)
    {
        if (empty($context['data']['did_not_bat'])) {
            return false;
        }
        return ($context['data']['did_not_bat'] == 1);
    }

    private function anyBowlingFieldsCompleted($context)
    {
        if (empty($context['data'])) {
            return false;
        }

        $fields = ["overs", "maidens", "wickets", "runs", "order_no"];
        foreach ($fields as $field) {
            if ($context['data']['bowling_' . $field] != '') {
                return false;
            }
        }
        return true;
    }

    private function zeroOrNotEmpty($value)
    {
        return ($value == 0 || !empty($value));
    }

    public function getTeamStats($year)
    {
        if ($year != "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        $contain = [
            "Matches",
            "Players" => [
                "fields" => ["Players.initials", "Players.last_name"]
            ],
            "ModesOfDismissal"
        ];

        $options = [
            "contain" => $contain,
            "where" => (isset($where) ? $where : [])
        ];

        $stats["leadingRunScorer"] = $this->find("leading", array_merge($options, ["field" => "batting_runs"]));
        $stats["leadingWicketTaker"] = $this->find("leading", array_merge($options, ["field" => "bowling_wickets"]));

        $stats["highestIndividualScore"] = $this->find("highestIndividualScore", $options);
        $stats["bestBowling"] = $this->find("bestBowling", $options);

        $stats["fifties"] = $this->find("fifties", $options);
        $stats["fivefors"] = $this->find("fivefors", $options);
        $stats["ducks"] = $this->find("ducks", $options);

        return $stats;
    }

    public function findLeading(Query $query, array $options = [])
    {
        $total = $query->func()->sum($options["field"]);
        $data = $query->find("all")
            ->select(["total" => $total])
            ->order(["total DESC"])
            ->where($options["where"])
            ->group(["MatchesPlayers.player_id"])
            ->all();

        $resultsToReturn = [];

        $record = 0;
        foreach ($data as $k => $player) {

            if ($player->total < $record) {
                continue;
            }

            $record = $player->total;
            $resultsToReturn[] = $player;
        }

        return $resultsToReturn;

    }

    public function getIndividualStats($player_id, $year = null)
    {
        $results = [];

        $where = ["MatchesPlayers.player_id" => $player_id];
        if ($year) {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        $options = [
            "contain" => ["Matches", "ModesOfDismissal"],
            "where" => $where
        ];

        $results["apps"] = $this->find("individualApps", $options);
        $results["bestBatting"] = $this->find("highestIndividualScore", $options);
        $results["bestBowling"] = $this->find("bestBowling", $options);

        return $results;
    }

    public function findIndividualApps(Query $query, array $options = [])
    {
        return $query->where($options["where"])->count();
    }


    public function findHighestIndividualScore(Query $query, array $options = [])
    {
        //highest individual score
        return $query->find("all")
            ->where($options["where"])
            ->order(['MatchesPlayers.batting_runs DESC'])
            ->first();
    }

    public function findBestBowling(Query $query, array $options = [])
    {
        return $query->find("all")
            ->where($options["where"])
            ->order(["MatchesPlayers.bowling_wickets DESC", "MatchesPlayers.bowling_runs ASC"])
            ->first();
    }

    public function findFifties(Query $query, array $options = [])
    {
        $options["where"]["MatchesPlayers.batting_runs >="] = 50;
        return $query->find("all")
            ->where($options["where"])
            ->order(["MatchesPlayers.batting_runs DESC"])
            ->group(["MatchesPlayers.player_id"])
            ->all();
    }

    public function findFivefors(Query $query, array $options = [])
    {
        $options["where"]["MatchesPlayers.bowling_wickets >="] = 5;
        return $query->find("all")
            ->where($options["where"])
            ->order(["MatchesPlayers.bowling_wickets DESC, bowling_runs ASC"])
            ->group(["MatchesPlayers.player_id"])
            ->all();
    }

    public function findDucks(Query $query, array $options = [])
    {
        //players who have got ducks
        $options["where"]["MatchesPlayers.batting_runs"] = 0;
        return $query->find("all")
            ->where($options["where"])
            ->order(["Players.last_name ASC"])
            ->group(["MatchesPlayers.player_id"])
            ->all();
    }

    public function getBattingAverages($players, $year)
    {
        $where[] = "MatchesPlayers.batting_order_no IS NOT NULL";
        $where["MatchesPlayers.did_not_bat"] = 0;

        if (is_numeric($year) && $year !== "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        foreach ($players as $player) {

            $playerWhere = $where;
            $playerWhere["MatchesPlayers.player_id"] = $player->id;

            $options = [
                "where" => $playerWhere,
                "contain" => ["Matches", "ModesOfDismissal"]
            ];

            $player->batting_innings = $this->find("numberOfInnings", $options);
            $player->batting_not_out = $this->find("numberNotOut", $options);
            $player->batting_high_score = $this->find("highestIndividualScore", $options);
            $player->batting_runs = $this->find("total", array_merge($options, ["field" => "batting_runs"]));

            $player->batting_average = "-";
            $dismissals = $player->batting_innings - $player->batting_not_out;
            if ($dismissals > 0) {
                $player->batting_average = round($player->batting_runs / $dismissals, 2);
            }
        }
        return $players;
    }

    public function findNumberOfInnings (Query $query, array $options = [])
    {
        return $query->find("all")
            ->where($options["where"])
            ->count();
    }

    public function findNumberNotOut(Query $query, array $options = [])
    {
        return $query->find("all")
            ->where(array_merge($options["where"], ["ModesOfDismissal.not_out" => 1]))
            ->count();
    }


    public function getBowlingAverages($players, $year)
    {
        $where[] = "MatchesPlayers.bowling_order_no IS NOT NULL";

        if (is_numeric($year) && $year !== "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        foreach ($players as $player) {

            $playerWhere = $where;
            $playerWhere["MatchesPlayers.player_id"] = $player->id;

            $options = [
                "where" => $playerWhere,
                "contain" => ["Matches", "ModesOfDismissal"]
            ];

            $player->bowling_overs = $this->find("numberOfOvers", $options);
            $player->bowling_maidens = $this->find("total", array_merge($options, ["field" => "bowling_maidens"]));
            $player->bowling_runs = $this->find("total", array_merge($options, ["field" => "bowling_runs"]));
            $player->bowling_wickets = $this->find("total", array_merge($options, ["field" => "bowling_wickets"]));

            $player->bowling_economy = "-";
            if ($player->bowling_overs > 0) {
                $ballsBowled = CricketUtility::convertOversToBalls($player->bowling_overs);
                $decimalOvers = CricketUtility::convertBallsToDecimal($ballsBowled);
                $player->bowling_economy = round($player->bowling_runs / $decimalOvers, 2);
            }

            $player->bowling_average = "-";
            if ($player->bowling_wickets > 0) {
                $player->bowling_average = round($player->bowling_runs / $player->bowling_wickets, 2);
            }

            $player->best_bowling = $this->find("bestBowling", $options);
        }
        return $players;
    }

    public function findNumberOfOvers(Query $query, array $options = [])
    {
        $data = $query->find("all")
            ->where($options["where"]);

        $totalBalls = 0;
        foreach ($data as $bowlingFigures) {
            $totalBalls += CricketUtility::convertOversToBalls($bowlingFigures->bowling_overs);
        }
        return CricketUtility::convertBallsToOvers($totalBalls);
    }

    public function findTotal(Query $query, array $options = [])
    {
        $total = $query->func()->sum($options["field"]);
        return $query
            ->where($options["where"])
            ->select(["total" => $total])
            ->first()->total;
    }

}