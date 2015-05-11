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


class PlayersTable extends Table {

    use SoftDeleteTrait;

    public function initialize(array $config)
    {
        $this->hasMany("PlayersScorecards");

        $this->belongsToMany("Roles");
    }

    public function findForIndex()
    {
        $query = $this->find("all")
            ->contain(['Roles'])
            ->order(["Players.last_name ASC", "Players.initials ASC"]);

        return $query->toArray();
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
            ->notEmpty('first_name')
            ->notEmpty("initials")
            ->notEmpty("last_name");

        return $validator;
    }

    public function getList()
    {
        $query = $this->find("list", [
                "keyField" => "id",
                "valueField" => "name",
                "order" => ["last_name ASC"]
            ]);
        return $query->toArray();
    }

    public function getBatsmen($year)
    {

        //get all players who have batted in this year
        $where[] = "matches_players.batting_order_no IS NOT NULL";
        $where["matches_players.did_not_bat"] = 0;
        if (is_numeric($year) && $year !== "all") {
            $where["matches.date >= "] = $year . "-01-01";
            $where["matches.date < "] = $year + 1 . "-01-01";
        }

        return $this->find("all")
            ->innerJoin("matches_players", ["matches_players.player_id = Players.id"])
            ->innerJoin("matches", ["matches.id = matches_players.match_id"])
            ->group(['Players.id'])
            ->where($where)
            ->order(["Players.last_name ASC"])
            ->all();

    }

    public function getBowlers($year)
    {

        //get all players who have batted in this year
        $where[] = "matches_players.bowling_order_no IS NOT NULL";
        if (is_numeric($year) && $year !== "all") {
            $where["matches.date >= "] = $year . "-01-01";
            $where["matches.date < "] = $year + 1 . "-01-01";
        }

        return $this->find("all")
            ->innerJoin("matches_players", ["matches_players.player_id = Players.id"])
            ->innerJoin("matches", ["matches.id = matches_players.match_id"])
            ->group(['Players.id'])
            ->where($where)
            ->order(["Players.last_name ASC"])
            ->all();

    }


}