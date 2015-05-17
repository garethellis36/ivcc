<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:08
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

class MatchesTable extends Table {

    use SoftDeleteTrait;

    public function initialize(array $config)
    {
        $this->hasMany("MatchesPlayers");

        $this->belongsTo("Formats");
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
            ->add('date', 'valid', ['rule' => [
                'datetime', 'ymd'
            ]])
            ->notEmpty('email');

        $validator
            ->add('opposition', 'valid', ['rule' => 'notBlank'])
            ->notEmpty('opposition');

        $validator
            ->add('ivcc_batted_first', 'value', [
                'rule' => 'boolean'
            ]);

        $isNumericAndNotDecimal = "/^[0-9]*$/";

        //certain fields can be empty if no result set
        $fields = [
            'ivcc_total' => [],
            'ivcc_extras' => [],
            'ivcc_wickets' => [],
            'ivcc_overs' => [
                'rule' => 'numeric'
            ],
            'opposition_total' => [],
            'opposition_wickets' => [],
            'opposition_overs' => [
                'rule' => 'numeric'
            ]
        ];

        foreach ($fields as $field => $rules) {

            $rule = ["custom", $isNumericAndNotDecimal];
            if (isset($rules["rule"])) {
                $rule = ['rule' => $rules["rule"]];
            }

            $validator
                ->allowEmpty($field, function ($context) {
                    return (!isset($context['data']['result']) || empty($context['data']['result']));
                })
                ->add($field, "valid", $rule);
        }

        return $validator;
    }

    public function isNumericAndNotDecimal()
    {
        if (stripos($value, '.') !== false) {
            return false;
        }
    }

    public function getTeamStats($year)
    {
        if ($year != "all") {
            $where["Matches.date >= "] = $year . "-01-01";
            $where["Matches.date < "] = $year + 1 . "-01-01";
        }

        $where[] = "Matches.ivcc_total IS NOT NULL";

        $options = [
            "where" => $where
        ];

        $stats["highestScore"] = $this->getHighestTeamScore($options);
        $stats["lowestScore"] = $this->getLowestTeamScore($options);

        return $stats;
    }

    public function getHighestTeamScore($options)
    {
        //get best team score

        return $this->find("all")
            ->where($options["where"])
            ->order(["Matches.ivcc_total DESC"])
            ->first();
    }

    public function getLowestTeamScore($options)
    {
        //get best team score
        return $this->find("all")
            ->order(["Matches.ivcc_total ASC"])
            ->where($options["where"])
            ->first();
    }

}