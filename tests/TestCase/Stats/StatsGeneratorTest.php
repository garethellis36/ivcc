<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 10/09/2016
 * Time: 17:04
 */

namespace App\Test\TestCase\Stats;

use App\Stats\StatsGenerator;
use Cake\TestSuite\TestCase;

/**
 * @property StatsGenerator $statsGenerator
 */
class StatsGeneratorTest extends TestCase
{
    public $fixtures = [
        "app.matches",
        "app.matches_players",
        "app.players",
        "app.modes_of_dismissal",
    ];

    public function setUp()
    {
        parent::setUp();
        $this->statsGenerator = new StatsGenerator();
    }

    public function test_it_can_get_all_results_for_a_year_and_format()
    {
        $matches = $this->statsGenerator->getAllMatches();
        assertThat(iterator_count($matches), is(equalTo(4)));

        $matches = $this->statsGenerator->getAllMatches("2016");
        assertThat(iterator_count($matches), is(equalTo(2)));

        $matches = $this->statsGenerator->getAllMatches("all", 2);
        assertThat(iterator_count($matches), is(equalTo(2)));
    }

    public function test_it_can_get_highest_team_total()
    {
        $match = $this->statsGenerator->getHighestTeamScore($this->statsGenerator->getAllMatches());
        assertThat($match->ivcc_total, is(equalTo(350)));
    }

    public function test_it_can_get_lowest_team_total()
    {
        $match = $this->statsGenerator->getLowestTeamScore($this->statsGenerator->getAllMatches());
        assertThat($match->ivcc_total, is(equalTo(80)));
    }

    public function test_it_can_get_result_stats()
    {
        $results  = $this->statsGenerator->getResults($this->statsGenerator->getAllMatches());
        $expected = [
            "P" => 3,
            "W" => "2",
            "L" => "1",
        ];
        assertThat($results, is(equalto($expected)));
    }

    public function test_different_stats_methods_can_operate_on_one_querys_worth_of_results_without_problems()
    {
        $match = $this->statsGenerator->getLowestTeamScore($this->statsGenerator->getAllMatches());
        assertThat($match->ivcc_total, is(equalTo(80)));

        $results  = $this->statsGenerator->getResults($this->statsGenerator->getAllMatches());
        $expected = [
            "P" => 3,
            "W" => "2",
            "L" => "1",
        ];
        assertThat($results, is(equalto($expected)));
    }

    public function test_it_can_get_the_highest_individual_batting_score()
    {
        $bestBatting = $this->statsGenerator->getTeamStats()["highestIndividualScore"];
        assertThat($bestBatting->highestIndividualScore->batting_runs, is(equalto(182)));
    }

    public function test_it_can_get_the_best_bowling_figures()
    {
        $bestBowling = $this->statsGenerator->getTeamStats()["bestBowling"];
        assertThat($bestBowling->bestBowling->bowling_wickets . " for " . $bestBowling->bestBowling->bowling_runs, is(equalTo("4 for 17")));
    }
}