<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 08/09/2016
 * Time: 12:23
 */

namespace App\Controller;

class PagesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow('about');

        $this->set("title", "About");
    }

    public function about()
    {
        $committee = [
            "Chairman" => "Ali Meier",
            "Club Captain" => "Bill Smith",
            "Vice-Captain" => "Olly Ross",
            "Fixture Secretary" => "Fergus Cable-Alexander",
            "Treasurer" => "Alex Troth",
            "Website & statto" => "Gareth Ellis",
            "Design" => "Tim Morton",
            "Ex-Officio" => [
                "Will Taylor",
                "Daniel Watkins",
                "Olly Ross",
            ],
        ];

        $vicePresidents = [
            "Nick Irvine",
            "Charlie Ross",
            "Des Smith",
        ];

        $this->set(compact(
            "committee",
            "vicePresidents"
        ));
    }
}
