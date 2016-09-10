<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class MatchesFixture extends TestFixture
{
    public $import = [
        "table"      => "matches",
    ];

    public $records = [];

    public function init()
    {
        $this->records = [
            [
                "opposition"         => "Long Compton",
                "date"               => "2015-05-01 13:00:00",
                "result"             => "Won",
                "result_more"        => "14 runs",
                "venue"              => "A",
                "venue_name"         => "",
                "format_id"          => "1",
                "match_report"       => "",
                "ivcc_total"         => "350",
                "ivcc_wickets"       => "5",
                "ivcc_overs"         => "40",
                "ivcc_extras"        => "10",
                "opposition_total"   => "336",
                "opposition_wickets" => "10",
                "opposition_overs"   => "39",
                "ivcc_batted_first"  => "1",
                "captain_id"         => "1",
                "wicketkeeper_id"    => "2",
                "opposition_slug"    => "long-compton",
            ],
            [
                "opposition"         => "Appleton",
                "date"               => "2016-05-31 13:00:00",
                "result"             => "Lost",
                "result_more"        => "20 runs",
                "venue"              => "A",
                "venue_name"         => "",
                "format_id"          => "1",
                "match_report"       => "",
                "ivcc_total"         => "80",
                "ivcc_wickets"       => "10",
                "ivcc_overs"         => "19",
                "ivcc_extras"        => "1",
                "opposition_total"   => "100",
                "opposition_wickets" => "10",
                "opposition_overs"   => "24",
                "ivcc_batted_first"  => "0",
                "captain_id"         => "1",
                "wicketkeeper_id"    => "2",
                "opposition_slug"    => "appleton",
            ],
            [
                "opposition"         => "Sibcliffe",
                "date"               => "2016-06-18 13:00:00",
                "result"             => "Won",
                "result_more"        => "2 wickets",
                "venue"              => "A",
                "venue_name"         => "",
                "format_id"          => "2",
                "match_report"       => "",
                "ivcc_total"         => "170",
                "ivcc_wickets"       => "8",
                "ivcc_overs"         => "39",
                "ivcc_extras"        => "8",
                "opposition_total"   => "169",
                "opposition_wickets" => "10",
                "opposition_overs"   => "35",
                "ivcc_batted_first"  => "0",
                "captain_id"         => "1",
                "wicketkeeper_id"    => "2",
                "opposition_slug"    => "sibcliffe",
            ],
            [
                "opposition"         => "Wiley",
                "date"               => "2020-06-18 13:00:00",
                "result"             => null,
                "result_more"        => "",
                "venue"              => "A",
                "venue_name"         => "",
                "format_id"          => "2",
                "match_report"       => "",
                "ivcc_total"         => "",
                "ivcc_wickets"       => "",
                "ivcc_overs"         => "",
                "ivcc_extras"        => "",
                "opposition_total"   => "",
                "opposition_wickets" => "",
                "opposition_overs"   => "",
                "ivcc_batted_first"  => "",
                "captain_id"         => "",
                "wicketkeeper_id"    => "",
                "opposition_slug"    => "wiley",
            ]
        ];

        parent::init();
    }
}
