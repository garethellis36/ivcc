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
use Cake\Datasource\EntityInterface;


class PlayersTable extends Table {

    use SoftDeleteTrait;

    public function initialize(array $config)
    {
        $this->hasMany("PlayersScorecards");

        $this->belongsToMany("Roles");
        $this->hasMany("AwardWinners");
    }

    /*
     * Overwriting patchEntity from Cake\ORM\Table in order to do some pre-save data manipulation
     */
    public function patchEntity(EntityInterface $entity, array $data, array $options = [])
    {
        if (isset($data["photo"]) && empty($data["photo"]["name"])) {
            unset($data["photo"]);
        }

        if (isset($data["photo"]) && empty($data["photo"]["name"]) && isset($data["delete_photo"]) && $data["delete_photo"] == 1) {
            $data["photo"] = null;
        }

        return parent::patchEntity($entity, $data, $options);
    }

    public function findForIndex()
    {
        $query = $this->find("all")
            ->contain(['Roles', 'AwardWinners' => [
                'Awards',
                'sort' => ['AwardWinners.year DESC']
            ]])
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
            ->add('first_name', 'length', ['rule' => ['lengthBetween', 1, 25]])
            ->add('first_name', 'valid', ['rule' => ['custom', "/^([A-Za-z]|\s|-|')*$/"]]);

        $validator
            ->notEmpty("initials")
            ->add('initials', 'length', ['rule' => ['lengthBetween', 1, 15]])
            ->add('initials', 'valid', ['rule' => ['custom', "/^([A-Z]|\.)*$/"]]);

        $validator
            ->notEmpty("last_name")
            ->add('last_name', 'length', ['rule' => ['lengthBetween', 1, 25]])
            ->add('last_name', 'valid', ['rule' => ['custom', "/^([A-Za-z]|\s|-|')*$/"]]);

        $validator
            ->allowEmpty("fines_owed")
            ->add("fines_owed", "length", ["rule" => ["maxLength", 255]]);

        $validator
            ->allowEmpty("photo")
            ->add("photo", "validImage", [
                "rule" => ["uploadedFile", [
                        "types" => ["image/jpeg"],
                        "optional" => true
                    ]
                ],
                "message" => "File must be a JPG"
            ]);

        return $validator;
    }

    public function validateImageDimensions($file, $maxWidth = 190, $maxHeight = 190)
    {
        $fileSize = getimagesize($file["tmp_name"]);
        return ($fileSize[0] <= $maxWidth && $fileSize[1] <= $maxHeight);
    }

    public function getList()
    {
        $query = $this->find("list", [
                "keyField" => "id",
                "valueField" => "name_for_dropdown",
                "order" => ["last_name ASC"]
            ]);
        return $query->toArray();
    }

    public function getAllPlayers($year, $format)
    {
        //get all players who have played in this year
        $where = [];

        if (is_numeric($year) && $year !== "all") {
            $where["matches.date >= "] = $year . "-01-01";
            $where["matches.date < "] = $year + 1 . "-01-01";
        }

        if (is_numeric($format) && $format !== "all") {
            $where["matches.format_id"] = $format;
        }

        return $this->find("all")
            ->innerJoin("matches_players", ["matches_players.player_id = Players.id"])
            ->innerJoin("matches", ["matches.id = matches_players.match_id"])
            ->group(['Players.id'])
            ->where($where)
            ->order(["Players.last_name ASC"])
            ->all();
    }

    public function getBatsmen($year, $format)
    {
        //get all players who have batted in this year
        $where[] = "matches_players.batting_order_no IS NOT NULL";
        $where["matches_players.did_not_bat"] = 0;
        if (is_numeric($year) && $year !== "all") {
            $where["matches.date >= "] = $year . "-01-01";
            $where["matches.date < "] = $year + 1 . "-01-01";
        }

        if (is_numeric($format) && $format !== "all") {
           $where["matches.format_id"] = $format;
        }

        return $this->find("all")
            ->innerJoin("matches_players", ["matches_players.player_id = Players.id"])
            ->innerJoin("matches", ["matches.id = matches_players.match_id"])
            ->group(['Players.id'])
            ->where($where)
            ->order(["Players.last_name ASC"])
            ->all();

    }

    public function getBowlers($year, $format)
    {
        //get all players who have bowled in this year/format
        $where[] = "matches_players.bowling_order_no IS NOT NULL";
        if (is_numeric($year) && $year !== "all") {
            $where["matches.date >= "] = $year . "-01-01";
            $where["matches.date < "] = $year + 1 . "-01-01";
        }

        if (is_numeric($format) && $format !== "all") {
            $where["matches.format_id"] = $format;
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