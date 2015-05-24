<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 12/05/15
 * Time: 14:47
 */

namespace App\Controller;
use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

class MatchesController extends AppController {

    public $helpers = ["Scorecard", "Player", "Stats"];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view', 'stats']);
    }

    public function index()
    {

        $year = ( isset($this->request->query["year"]) && is_numeric($this->request->query["year"]) ? $this->request->query["year"] : date("Y") );

        $query = $this->Matches->find("all")
            ->where([
                "Matches.date >=" => $year . "-01-01",
                "Matches.date <" => $year +1 . "-01-01"
            ])
            ->order([
                "Matches.date ASC"
            ])
            ->contain("Formats");

        $matches = $query->all();

        $this->set(compact("matches", "year"));

        $this->_getYearsForView();
    }

    public function view($match_id)
    {

        $query = $this->Matches->get($match_id, [
            "contain" => [
                "Formats",
                "MatchesPlayers" => [
                    "Players",
                    "ModesOfDismissal",
                ]
            ],
            "conditions" => [
                "Matches.result IS NOT NULL"
            ]
        ]);


        $scorecard = $query->toArray();

        $batting = Hash::sort($scorecard["matches_players"], '{n}.batting_order_no', 'asc');

        $bowling = Hash::sort($scorecard["matches_players"], '{n}.bowling_order_no', 'asc');

        unset($scorecard["matches_players"]);

        $this->set(compact(
            "scorecard",
            "batting",
            "bowling"
        ));

    }

    public function stats()
    {
        $year = ( isset($this->request->query["year"]) && is_numeric($this->request->query["year"]) ? $this->request->query["year"] : "all" );

        $stats = $this->Matches->getTeamStats($year);

        $scorecards = TableRegistry::get("MatchesPlayers");
        $stats = array_merge($scorecards->getTeamStats($year), $stats);

        $players = TableRegistry::get("Players");
        $batsmen = $players->getBatsmen($year);
        $batsmen = $scorecards->getBattingAverages($batsmen, $year);

        $bowlers = $players->getBowlers($year);
        $bowlers = $scorecards->getBowlingAverages($bowlers, $year);

        $this->set(compact("year", "stats", "batsmen", "bowlers"));
        $this->_getYearsForView(true);
    }

    public function _getYearsForView($include_all_time = false)
    {
        //get list of years to display in filter
        $query = $this->Matches->find("all")
            ->group("LEFT(Matches.date, 4)")
            ->order(["Matches.date DESC"])
            ->select(["id", "year" => "LEFT(Matches.date, 4)"]);

        $years = [];
        foreach ($query as $year) {
            $years[] = $year["year"];
        }

        //add current year to year array if not already there
        if (!in_array(date("Y"), $years)) {
            $years[] = date("Y");
        }

        arsort($years);

        if ($include_all_time) {
            array_unshift($years, "All");
        }

        $this->set(compact("years"));
    }

}