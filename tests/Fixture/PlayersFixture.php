<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Faker\Factory;

class PlayersFixture extends TestFixture
{
    public $import = [
        "table" => "players",
    ];

    public $records = [];

    public function init()
    {
        $faker = Factory::create();
        for ($i = 0; $i <= 15; $i++) {
            $this->records[] = [
                "first_name"     => $faker->firstName,
                "last_name"      => $faker->lastName,
                "initials"       => "X.X.",
                "previous_clubs" => "",
                "photo"          => null,
                "fines_owed"     => "",
            ];
        }

        parent::init();
    }


}
