<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:08
 */

namespace App\Model\Table;

use App\Model\Entity\MatchesPlayer;
use Cake\Datasource\Exception\RecordNotFoundException;
use App\Model\Table\AppTable;
use Cake\ORM\Query;
use App\Lib\CricketUtility;
use Cake\Validation\Validator;
use App\Model\Entity\Player;
use Garethellis\CricketStatsHelper\CricketStatsHelper;

class MatchesPlayersTable extends AppTable {

    public function initialize(array $config)
    {
        $this->belongsTo("Matches");
        $this->belongsTo("Players");
        $this->belongsTo("ModesOfDismissal");

    }

    private $validOrderNo = [
        "validNumber" => [
            'rule' => ["naturalNumber", true],
            'message' => 'Number only, no decimals'
        ],
        'validRange' => [
            'rule' => ['range', 1, 11],
            'message' => 'Must be between 1 and 11'
        ]
    ];

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

        $validator = $this->validationBatting($validator);

        $validator = $this->validationBowling($validator);

        $validator
            ->add("catches", 'validNumber', ["message" => "Number only, no decimals", "rule" => ["naturalNumber", true]]);

        return $validator;

    }

    private function validationBatting(Validator $validator)
    {
        $validator
            ->add('did_not_bat', 'valid', ['rule' => 'boolean']);
        $validator
            ->notEmpty('batting_order_no')
            ->add('batting_order_no', $this->validOrderNo);

        $validator
            ->allowEmpty('batting_runs', function ($context) {
                return $this->hasBatted($context);
            })
            ->add('batting_order_no', 'validNumber', ["message" => "Number only, no decimals", "rule" => ["naturalNumber", true]]);

        $validator
            ->allowEmpty('modes_of_dismissal_id', function ($context) {
                return $this->hasBatted($context);
            })
            ->add('modes_of_dismissal_id', 'validNumber', ["message" => "Number only, no decimals", "rule" => ["naturalNumber", true]]);

        return $validator;
    }

    private function validationBowling(Validator $validator)
    {
        $validator
            ->allowEmpty('bowling_order_no', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_order_no', $this->validOrderNo);

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
            ->add('bowling_maidens', 'validNumber', ["message" => "Number only, no decimals", "rule" => ["naturalNumber", true]]);

        $validator
            ->allowEmpty('bowling_runs', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_runs', 'validNumber', ["message" => "Number only, no decimals", "rule" => ["naturalNumber", true]]);

        $validator
            ->allowEmpty('bowling_wickets', function ($context) {
                return $this->anyBowlingFieldsCompleted($context);
            })
            ->add('bowling_wickets', 'validNumber', ["message" => "Number only, no decimals", "rule" => ["naturalNumber", true]]);

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

    public function getTeamStats($year, $format)
    {
        if ($year != "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        if ($format != "all") {
            $where["Matches.format_id"] = $format;
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

        return $this->_getTeamStats($options);
    }

    private function _getTeamStats($options)
    {
        $stats = [];

        $stats["mostApps"] = $this->find("mostApps", $options);

        $stats["leadingRunScorer"] = $this->find("leading", array_merge($options, ["field" => "batting_runs"]));
        $stats["leadingWicketTaker"] = $this->find("leading", array_merge($options, ["field" => "bowling_wickets"]));
        $stats["mostCatches"] = $this->find("leading", array_merge($options, ["field" => "catches"]));

        $stats["highestIndividualScore"] = $this->find("highestIndividualScore", $options);

        for ($i = 1; $i <= 11; $i++) {
            $scoreByPositionOptions = $options;
            $scoreByPositionOptions["where"]["MatchesPlayers.batting_order_no"] = $i;
            $stats["highestIndividualScoreByPosition"][$i] = $this->find("highestIndividualScore", $scoreByPositionOptions);
        }

        $stats["bestBowling"] = $this->find("bestBowling", $options);

        $stats["hundreds"] = $this->find("hundreds", $options);
        $stats["fifties"] = $this->find("fifties", $options);
        $stats["fivefors"] = $this->find("fivefors", $options);
        $stats["ducks"] = $this->find("ducks", $options);

        return $stats;
    }

    public function findMostApps(Query $query, array $options = [])
    {
        $total = $query->func()->count("MatchesPlayers.id");
        $data = $query->find("all")
            ->select(["total" => $total])
            ->order(['total desc'])
            ->where($options["where"])
            ->group(['MatchesPlayers.player_id'])
            ->all();

        $results = [];
        $record = 0;
        foreach ($data as $player) {
            if ($player->total < $record) {
                break;
            }
            $record = $player->total;
            $results[] = $player;
        }
        return $results;
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

        return $this->_getIndividualStats($options);
    }

    private function _getIndividualStats($options)
    {
        $results = [];
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
        $options["where"][] = ["batting_runs IS NOT NULL"];
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

    /**
     * @param Query $query
     * @param array $options
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function findHundreds(Query $query, array $options = [])
    {
        $options["where"]["MatchesPlayers.batting_runs >="] = 100;
        return $query->find("all")
            ->where($options["where"])
            ->order(["MatchesPlayers.batting_runs DESC"])
            ->all();
    }

    /**
     * @param Query $query
     * @param array $options
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function findFifties(Query $query, array $options = [])
    {
        $options["where"]["MatchesPlayers.batting_runs <="] = 99;
        $options["where"]["MatchesPlayers.batting_runs >="] = 50;
        return $query->find("all")
            ->where($options["where"])
            ->order(["MatchesPlayers.batting_runs DESC"])
            ->all();
    }

    public function findFivefors(Query $query, array $options = [])
    {
        $options["where"]["MatchesPlayers.bowling_wickets >="] = 5;
        return $query->find("all")
            ->where($options["where"])
            ->order(["MatchesPlayers.bowling_wickets DESC, bowling_runs ASC"])
            ->all();
    }

    public function findDucks(Query $query, array $options = [])
    {
        //players who have got ducks
        $options["where"]["MatchesPlayers.batting_runs"] = 0;
        $options["where"]["ModesOfDismissal.not_out"] = 0;
        return $query->find("all")
            ->where($options["where"])
            ->order(["Players.last_name ASC"])
            ->all();
    }

    public function getAppearancesAndCatches($players, $year, $format)
    {
        $where = [];

        if (is_numeric($year) && $year !== "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        if (is_numeric($format) && $format !== "all") {
            $where["Matches.format_id"] = $format;
        }

        foreach ($players as $player) {
            /** @var $player Player */
            $allApps = $this->find("all")
                ->where(array_merge($where, ["MatchesPlayers.player_id" => $player->id]))
                ->contain("Matches");

            $player->motm = 0;
            $player->catches = 0;

            foreach ($allApps as $appearance) {
                if ($appearance->match->man_of_the_match_id === $player->id) {
                    $player->motm++;
                }
                $player->catches += $appearance->catches;
            }

            $player->appearances = $allApps->count();
        }
        return $players;
    }

    public function getBattingAverages($players, $year, $format)
    {
        $where[] = "MatchesPlayers.batting_order_no IS NOT NULL";
        $where["MatchesPlayers.did_not_bat"] = 0;

        if (is_numeric($year) && $year !== "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        if (is_numeric($format) && $format !== "all") {
            $where["Matches.format_id"] = $format;
        }

        foreach ($players as $player) {
            $player = $this->_getPlayerBattingStats($player, $where);
        }
        return $players;
    }

    private function _getPlayerBattingStats(Player $player, $where)
    {
        $where["MatchesPlayers.player_id"] = $player->id;

        $options = [
            "where" => $where,
            "contain" => ["Matches", "ModesOfDismissal"]
        ];

        $player->batting_innings = $this->find("numberOfInnings", $options);
        $player->batting_not_out = $this->find("numberNotOut", $options);
        $player->batting_high_score = $this->find("highestIndividualScore", $options);
        $player->batting_runs = $this->find("total", array_merge($options, ["field" => "batting_runs"]));

        $player->fifties = iterator_count($this->find("fifties", $options)
            ->filter(function (MatchesPlayer $match) use ($player) {
                return $player->id == $match->player_id;
            }));

        $player->hundreds = iterator_count($this->find("hundreds", $options)
            ->filter(function (MatchesPlayer $match) use ($player) {
                return $player->id == $match->player_id;
            }));


        $statsHelper = new CricketStatsHelper();
        $player->batting_average = $statsHelper->calculateBattingAverage(
            (int)$player->batting_runs,
            (int)$player->batting_innings,
            (int)$player->batting_not_out
        );

        return $player;
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


    public function getBowlingAverages($players, $year, $format)
    {
        $where[] = "MatchesPlayers.bowling_order_no IS NOT NULL";

        if (is_numeric($year) && $year !== "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        if (is_numeric($format) && $format !== "all") {
            $where["Matches.format_id"] = $format;
        }

        foreach ($players as $player) {
            $player = $this->_getPlayerBowlingAverages($player, $where);
        }
        return $players;
    }

    private function _getPlayerBowlingAverages(Player $player, $where)
    {
        $where["MatchesPlayers.player_id"] = $player->id;

        $options = [
            "where" => $where,
            "contain" => ["Matches", "ModesOfDismissal"]
        ];

        $player->bowling_overs = $this->find("numberOfOvers", $options);
        $player->bowling_maidens = $this->find("total", array_merge($options, ["field" => "bowling_maidens"]));
        $player->bowling_runs = $this->find("total", array_merge($options, ["field" => "bowling_runs"]));
        $player->bowling_wickets = $this->find("total", array_merge($options, ["field" => "bowling_wickets"]));

        $statsHelper = new CricketStatsHelper();

        $player->bowling_economy = $statsHelper->calculateBowlingEconomy(
            $player->bowling_overs,
            (int)$player->bowling_runs
        );

        $player->bowling_average = $statsHelper->calculateBowlingAverage(
            (int)$player->bowling_runs,
            (int)$player->bowling_wickets
        );

        $player->bowling_strike_rate = $statsHelper->calculateStrikeRate(
            $player->bowling_overs,
            (int)$player->bowling_wickets
        );

        $player->best_bowling = $this->find("bestBowling", $options);

        return $player;
    }

    public function findNumberOfOvers(Query $query, array $options = [])
    {
        $data = $query->find("all")
            ->where($options["where"]);

        $totalBalls = 0;

        $statsHelper = new CricketStatsHelper();
        foreach ($data as $bowlingFigures) {
            $totalBalls += $statsHelper->convertOversToBalls($bowlingFigures->bowling_overs);
        }
        return $statsHelper->convertBallsToOvers($totalBalls);
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