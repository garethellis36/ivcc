<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 10/09/2016
 * Time: 17:04
 */

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatchesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * @property MatchesTable $MatchesTable
 */
class MatchesTableTest extends TestCase
{
    public $fixtures = [
        "app.matches",
    ];

    public function setUp()
    {
        parent::setUp();

        $this->MatchesTable = TableRegistry::get("Matches");
    }

    public function test_it_can_get_all_results_for_a_year_and_format()
    {
        $matches = $this->MatchesTable->getAllMatches();
        assertThat(iterator_count($matches),is(equalTo(4)));

        $matches = $this->MatchesTable->getAllMatches("2016");
        assertThat(iterator_count($matches),is(equalTo(2)));

        $matches = $this->MatchesTable->getAllMatches("all", 2);
        assertThat(iterator_count($matches),is(equalTo(2)));
    }

    public function test_it_can_get_highest_team_total()
    {
        $match = $this->MatchesTable->getHighestTeamScore($this->MatchesTable->getAllMatches());
        assertThat($match->ivcc_total, is(equalTo(350)));
    }

    public function test_it_can_get_lowest_team_total()
    {
        $match = $this->MatchesTable->getLowestTeamScore($this->MatchesTable->getAllMatches());
        assertThat($match->ivcc_total, is(equalTo(80)));
    }

    public function test_it_can_get_result_stats()
    {
        $results = $this->MatchesTable->getResults($this->MatchesTable->getAllMatches());
        $expected = [
            "P" => 3,
            "W" => "2",
            "L" => "1",
        ];
        assertThat($results, is(equalto($expected)));
    }

    public function test_different_stats_methods_can_operate_on_one_querys_worth_of_results_without_problems()
    {
        $match = $this->MatchesTable->getLowestTeamScore($this->MatchesTable->getAllMatches());
        assertThat($match->ivcc_total, is(equalTo(80)));

        $results = $this->MatchesTable->getResults($this->MatchesTable->getAllMatches());
        $expected = [
            "P" => 3,
            "W" => "2",
            "L" => "1",
        ];
        assertThat($results, is(equalto($expected)));
    }
}