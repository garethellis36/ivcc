<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 02/07/15
 * Time: 20:20
 */

namespace App\Routing;

use Cake\Routing\Route\Route;
use Cake\ORM\TableRegistry;



class MatchRoute extends Route
{

    public function parse($url)
    {
        $params = parent::parse($url);
        if (empty($params)) {
            return false;
        }

        $year = $params['year'];
        $month = $params['month'];
        $day = $params['day'];
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

        $matches = TableRegistry::get("matches");
        $result = $matches->find("all")
            ->where([
                "DATE_FORMAT(matches.date,'%Y-%m-%d')" => $date,
                "matches.opposition_slug" => $params["oppositionSlug"]
            ])
            ->limit(1)
            ->first();

        if (!$result) {
            return false;
        }

        $params['pass'] = [$result->id];
        return $params;

    }


}