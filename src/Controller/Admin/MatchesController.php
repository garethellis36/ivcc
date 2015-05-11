<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class MatchesController extends AppController
{

    public function add()
    {
        $match = $this->Matches->newEntity();

        if ($this->request->is('post')) {

            $match = $this->Matches->patchEntity($match, $this->request->data);

            // this is the only way I've worked out how to pass on the date value from a single text field
            // from FormHelper - without this line the save fails because there's no date value left after patchEntity
            $match->date = $this->request->data['date'];

            if ($this->Matches->save($match)) {
                $this->Flash->success('The match has been saved.');
                return $this->redirect("/matches");
            }

            $this->Flash->error('The match could not be saved. Please, try again.');
        }

        $formats = TableRegistry::get("formats");
        $formats = $formats->getList();

        $this->set(compact('match', 'formats'));
        $this->set('_serialize', ['match']);
    }

    public function edit($match_id)
    {
        $match = $this->Matches->get($match_id, [
            'contain' => [
                "MatchesPlayers" => [
                    "sort" => [
                        "MatchesPlayers.batting_order_no ASC"
                    ]
                ]
            ]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            //remove unselected player slots from data array
            foreach ($this->request->data["matches_players"] as $i => $player) {
                if (empty($player["player_id"])) {
                    unset($this->request->data["matches_players"][$i]);

                    //delete existing MatchesPlayers record if necessary
                    if (isset($player["id"])) {
                        $matchesPlayers = TableRegistry::get("MatchesPlayers");
                        $matchesPlayerRecord = $matchesPlayers->get($player["id"]);
                        $matchesPlayers->delete($matchesPlayerRecord);
                    }
                }
            }

            $match = $this->Matches->patchEntity($match, $this->request->data, [
                "associated" => [
                    "MatchesPlayers"
                ]
            ]);

            $match->date = $this->request->data['date'];

            if ($this->Matches->save($match)) {
                $this->Flash->success('Match saved.');

                $url = "/matches";
                if ($match->result) {
                    $url .= "/view/{$match_id}";
                } else {
                    $url .= "?year=" . substr($match->date, 0, 4);
                }
                return $this->redirect($url);
            } else {
                $this->Flash->error('The match could not be saved. Please, try again.');
            }
        }



        $formats = TableRegistry::get("formats");
        $formats = $formats->getList();

        $players = TableRegistry::get("players");
        $players = $players->getList();

        $modesOfDismissals = TableRegistry::get("modesOfDismissal");
        $modesOfDismissals = $modesOfDismissals->getList();

        $playerRowFields = $this->_playerRowFields();

        $results = [
            "Won" => "Won",
            "Lost" => "Lost",
            "Tied" => "Tied",
            "Drawn" => "Drawn",
            "Abandoned" => "Abandoned",
            "Cancelled" => "Cancelled"
        ];

        $this->set(compact('match', 'formats', 'results', 'players', 'modesOfDismissals', 'playerRowFields'));
        $this->set('_serialize', ['match']);
    }

    protected function _playerRowFields()
    {
        return [
            "player_id" => [
                "empty" => "--",
                "class" => "input-mini playerSelect"
            ],
            "did_not_bat" => [
                "label" => "DNB",
                "class" => "input-mini disableWithoutPlayer dnb",
                "type" => "checkbox",
            ],
            "batting_order_no" => [
                "class" => "input-mini number disableWithoutPlayer disableIfDnb",
                "type" => "number",
                "default" => "rowNumber",
                "max" => 11
            ],
            "batting_runs" => [
                "class" => "input-mini number disableWithoutPlayer disableIfDnb",
                "type" => "number"
            ],
            "modes_of_dismissal_id" => [
                "class" => "input-mini disableWithoutPlayer disableIfDnb",
                "empty" => "--"
            ],
            "bowling_overs" => [
                "class" => "input-mini number disableWithoutPlayer",
                "type" => "number"
            ],
            "bowling_maidens" => [
                "class" => "input-mini number disableWithoutPlayer",
                "type" => "number"
            ],
            "bowling_runs" => [
                "class" => "input-mini number disableWithoutPlayer",
                "type" => "number"
            ],
            "bowling_wickets" => [
                "class" => "input-mini number disableWithoutPlayer",
                "type" => "number"
            ],
            "bowling_order_no" => [
                "class" => "input-mini number disableWithoutPlayer",
                "type" => "number"
            ],
        ];
    }

    public function delete($match_id)
    {
        $match = $this->Matches->get($match_id);
        if ($this->Matches->delete($match)) {
            $this->Flash->success('Match deleted.');
        } else {
            $this->Flash->error('The match could not be deleted. Please, try again.');
        }
        return $this->redirect("/matches");
    }

}