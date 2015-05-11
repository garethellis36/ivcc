<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class PlayersController extends AppController
{

    public $helpers = ["Player"];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('index');
    }

    public function index()
    {

        $players = $this->Players->findForIndex();

        $scorecards = TableRegistry::get("MatchesPlayers");
        foreach ($players as &$player) {

            //get ivcc career stats
            $player["career_stats"] = $scorecards->getIndividualStats($player["id"]);

            //get stats for current year
            $player["this_season_stats"] = $scorecards->getIndividualStats($player["id"], date("Y"));

        }

        $this->set(compact(
            "players"
        ));

    }


}