<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:08
 */

namespace App\Model\Table;

use App\Model\Entity\Match;
use App\Model\Table\AppTable;
use Cake\Datasource\ResultSetInterface;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class MatchesTable extends AppTable
{

    use SoftDeleteTrait;

    /**
     * @var array
     */
    private $allMatches;

    public function initialize(array $config)
    {
        $this->hasMany("MatchesPlayers");

        $this->belongsTo("Formats");
    }

    private function generateSlug($opposition)
    {
        return substr(strtolower(Inflector::slug($opposition)), 0, 255);
    }

    /*
     * Overwriting patchEntity from Cake\ORM\Table in order to do some pre-save data manipulation
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = [])
    {
        if (!$entity->id) {
            $data["opposition_slug"] = $this->generateSlug($data["opposition"]);
        }

        //unset certain fields if no result is set
        if (isset($data["result"]) && empty($data["result"])) {

            $data["result_more"] = null;

            $data["ivcc_total"] = null;
            $data["ivcc_extras"] = null;
            $data["ivcc_wickets"] = null;
            $data["ivcc_overs"] = null;

            $data["opposition_total"] = null;
            $data["opposition_wickets"] = null;
            $data["opposition_overs"] = null;
        }

        if (isset($data["matches_players"])) {
            $data = $this->tidyMatchesPlayersData($data);
        }

        return parent::patchEntity($entity, $data, $options);
    }

    private function tidyMatchesPlayersData($data)
    {

        if (count($data["matches_players"]) != 11) {
            throw new \Exception ("Invalid number of players provided");
        }

        //remove unselected player slots from data array
        $playerIds = [];
        foreach ($data["matches_players"] as $i => $player) {

            if (empty($player["player_id"])) {
                unset($data["matches_players"][$i]);

                //delete existing MatchesPlayers record if necessary
                if (!empty($player["id"])) {
                    $this->quickMatchPlayerDelete($player["id"]);
                }

                continue;
            }

            //remove duplicate players
            if (in_array($player["player_id"], $playerIds)) {

                //remove from request data array so nothing gets saved
                unset($data["matches_players"][$i]);

                //delete existing record from database if necessary
                $this->quickMatchPlayerDelete($player["id"]);

                continue;
            }
            $playerIds[] = $player["player_id"];

            //unset runs scored/mode of dismissal if player did not bat
            if ($player["did_not_bat"] == 1) {
                $data["matches_players"][$i]["batting_runs"] = null;
                $data["matches_players"][$i]["modes_of_dismissal_id"] = null;
            }

        }

        return $data;
    }

    private function quickMatchPlayerDelete($match_player_id)
    {
        $matchesPlayers = TableRegistry::get("MatchesPlayers");
        $matchesPlayerRecord = $matchesPlayers->get($match_player_id);
        $matchesPlayers->delete($matchesPlayerRecord);
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
            ->add('date', 'valid', [
                'rule'    => [
                    'datetime', 'ymd',
                ],
                "message" => "Date must be in the format Y-m-d H:i - use the datepicker widget",
            ])
            ->notEmpty('date');

        $validator
            ->add('opposition', 'valid', ['rule' => 'notBlank'])
            ->add('opposition', 'length', ['rule' => ['lengthBetween', 1, 50]])
            ->notEmpty('opposition');

        $validator
            ->allowEmpty("result_more")
            ->add('result_more', 'length', ['rule' => ['lengthBetween', 1, 50]]);

        $validator
            ->add('ivcc_batted_first', 'value', [
                'rule' => 'boolean',
            ]);

        $validator = $this->validationTeamScores($validator);

        return $validator;
    }

    private function validationTeamScores(Validator $validator)
    {
        //certain fields can be empty if no result set
        $fields = [
            'ivcc_total'         => [],
            'ivcc_extras'        => [
                'requireWith' => "ivcc_total",
            ],
            'ivcc_wickets'       => [
                'requireWith' => "ivcc_total",
            ],
            'ivcc_overs'         => [
                'rule'        => [$this, "validOvers"],
                'requireWith' => "ivcc_total",
            ],
            'opposition_total'   => [],
            'opposition_wickets' => [
                'requireWith' => "opposition_total",
            ],
            'opposition_overs'   => [
                'requireWith' => "opposition_total",
                'rule'        => [$this, "validOvers"],
            ],
        ];

        foreach ($fields as $field => $rules) {
            $validator = $this->_addTeamScoreValidationRules($validator, $field, $rules);
        }

        return $validator;
    }

    private function _addTeamScoreValidationRules(Validator $validator, $field, $rules)
    {
        $rule = ["naturalNumber", true];

        if (isset($rules["rule"])) {
            $rule = $rules["rule"];
        }

        $validator
            ->allowEmpty($field, function ($context) use ($rules) {

                if (!isset($rules["requireWith"])) {
                    return true;
                }


                return (empty($context["data"][$rules['requireWith']]));

            })
            ->add($field, "valid", [
                "rule" => $rule,
            ]);

        return $validator;
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
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        if ($formatId != "all") {
            $where["Matches.format_id"] = $formatId;
        }

        return $this->find("all")
            ->where($where)
            ->all();
    }

    public function getTeamStats($year = "all", $formatId = "all")
    {
        $all = $this->getAllMatches($year, $formatId);
        $stats["results"] = $this->getResults($all);
        $stats["highestScore"] = $this->getHighestTeamScore($all);
        $stats["lowestScore"] = $this->getLowestTeamScore($all);

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

}