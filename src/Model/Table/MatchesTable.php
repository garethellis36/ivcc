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

        return $validator;
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