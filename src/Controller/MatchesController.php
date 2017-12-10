<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 12/05/15
 * Time: 14:47
 */

namespace App\Controller;
use App\Model\Table\MatchesPlayersTable;
use App\Model\Table\MatchesTable;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;
use App\Model\Table\PlayersTable;

/**
 * @property MatchesTable Matches
 */
class MatchesController extends AppController {

    public $helpers = ["Scorecard", "Player", "Stats"];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index', 'view', 'stats']);
    }

    public function index()
    {

        $year = ( $this->request->query("year") && is_numeric($this->request->query("year")) ? $this->request->query("year") : date("Y") );
var_dump($this->request->query);
        $query = $this->Matches->find("all")
            ->where([
                "Matches.date >=" => $year . "-01-01",
                "Matches.date <" => $year +1 . "-01-01"
            ])
            ->order([
                "Matches.date ASC"
            ])
            ->contain(["Formats", "MatchManager"]);

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
                ],
                "ManOfTheMatch",
                "MatchManager",
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

        $this->set("title", h("IVCC vs " . $scorecard['opposition'] . " (" . $scorecard["venue"] . "), " . $scorecard["date"]->format('jS F Y')));

    }

    public function stats()
    {
        $year = ( isset($this->request->query["year"]) && is_numeric($this->request->query["year"]) ? $this->request->query["year"] : "all" );
        $format = ( isset($this->request->query["format"]) && is_numeric($this->request->query["format"]) ? $this->request->query["format"] : "all" );

        $stats = $this->Matches->getTeamStats($year, $format);

        /** @var $scorecards MatchesPlayersTable */
        $scorecards = TableRegistry::get("MatchesPlayers");
        $stats = array_merge($scorecards->getTeamStats($year, $format), $stats);

        /** @var $players PlayersTable */
        $players = TableRegistry::get("Players");

        $all = $players->getAllPlayers($year, $format);
        $all = $scorecards->getAppearancesAndCatches($all, $year, $format)->toArray();

        usort($all, function ($a, $b) {
           return $b->appearances - $a->appearances;
        });

        $batsmen = $players->getBatsmen($year, $format);
        $batsmen = $scorecards->getBattingAverages($batsmen, $year, $format)->toArray();

        usort($batsmen, function($a, $b) {
           return $b->batting_runs - $a->batting_runs;
        });

        $bowlers = $players->getBowlers($year, $format);
        $bowlers = $scorecards->getBowlingAverages($bowlers, $year, $format)->toArray();

        usort($bowlers, function($a, $b) {
           return $b->bowling_wickets - $a->bowling_wickets;
        });

        $this->set(compact("year", "stats", "all", "batsmen", "bowlers", "format"));
        $this->_getYearsForView(true);
        $this->_getFormatsForView();

        $this->set("title", "Stats");
    }

    public function _getYearsForView($include_all_time = false)
    {
        //get list of years to display in filter
        $query = $this->Matches->find("all")
            ->order(["year DESC"])
            ->select(["year" => "DISTINCT YEAR(Matches.date)"]);

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

    public function _getFormatsForView()
    {
        $formats = TableRegistry::get("Formats");
        $query = $formats->find('all')->indexBy("id");

        $formats = [];
        foreach ($query->toArray() as $format) {
            $formats[$format->id] = $format->name;
        }

        $formats = ["all" => "All"] + $formats;

        $this->set(compact("formats"));
    }

}