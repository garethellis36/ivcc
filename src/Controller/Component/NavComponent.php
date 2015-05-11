<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 14/05/15
 * Time: 12:33
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\Routing\Router;

class NavComponent extends Component {

    private $menuItems = [
        [
            "label" => "Home",
            "url" => "/",
            "pattern" => "/^\/$/",
            "active" => false,
        ],
        [
            "label" => "Matches",
            "url" => "/matches",
            "pattern" => "/^\/matches/",
            "active" => false
        ],
        [
            "label" => "Players",
            "url" => "/players",
            "pattern" => "/^\/players/",
            "active" => false
        ],
        [
            "label" => "Stats",
            "url" => "/stats",
            "pattern" => "/^\/stats/",
            "active" => false
        ]
    ];

    public function menuItems()
    {
        foreach ($this->menuItems as &$item) {
            if (preg_match($item["pattern"], Router::url())) {
                $item["active"] = true;
            }
        }

        return $this->menuItems;
    }

}