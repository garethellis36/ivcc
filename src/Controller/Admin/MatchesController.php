<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class MatchesController extends AppController
{

    public function add()
    {
        $match = $this->Matches->newEntity();

        if ($this->request->is('post')) {

            $match = $this->Matches->patchEntity($match, $this->request->data);

            // this is the only way I've worked out how to pass on the date value from a single text field
            // from FormHelper - without this line the save fails because there's no date value left after patchEntity
            if (empty($match->errors()['date'])) {
                $match->date = $this->request->data['date'];
            }

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

        $this->render("form");
    }

    public function edit($match_id)
    {
        $match = $this->Matches->get($match_id, [
            'contain' => [
                "MatchesPlayers" => [
                    "sort" => [
                        "MatchesPlayers.batting_order_no ASC"
                    ],
                    "Players"
                ]
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $match = $this->Matches->patchEntity($match, $this->request->data, [
                "associated" => [
                    "MatchesPlayers"
                ]
            ]);

            if (empty($match->errors()['date'])) {
                $match->date = $this->request->data['date'];
            }

            if ($this->Matches->save($match)) {
                $this->Flash->success('Match saved.');

                $url = "/matches";
                if ($match->result) {
                    $url .= "/view/{$match_id}";
                } else {
                    $url .= "?year=" . substr($match->date, 0, 4);
                }
                return $this->redirect($url);
            }

            //convert player validation errors into strings to print in a flash message above scorecard
            if (!empty($match->errors()['matches_players'])) {
                $this->convertMatchesPlayersErrorsForFlash($match);
            }

            $this->Flash->error('The match could not be saved. Please, try again.');

        }

        $this->populateDropDowns();

        $this->set(compact('match'));
        $this->set('_serialize', ['match']);

        $this->render("form");
    }

    private function convertMatchesPlayersErrorsForFlash(\App\Model\Entity\Match $match)
    {
        $scorecardErrors = [];
        foreach ($match->errors()['matches_players'] as $k => $scorecardError) {
            foreach ($scorecardError as $field => $error) {
                foreach ($error as $ruleType => $errorMessage) {
                    $playerNo = $k+1;
                    $playerName = ($match->matches_players[$k]->player ? $match->matches_players[$k]->player->name : "Player #" . $playerNo);
                    $scorecardErrors[] = $playerName
                        . " - " . Inflector::humanize($field)
                        . " - " . $errorMessage;
                }
            }
        }

        $this->set(compact("scorecardErrors"));
    }

    private function populateDropdowns()
    {
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

        $this->set(compact(
            "formats",
            "players",
            "modesOfDismissals",
            "playerRowFields",
            "results"
        ));
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
                "class" => "input-mini number disableWithoutPlayer",
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
            "catches" => [
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