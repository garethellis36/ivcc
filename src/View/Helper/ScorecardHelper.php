<?php
namespace App\View\Helper;

use Cake\View\Helper;

class ScorecardHelper extends Helper
{
    public function total($total, $wickets, $overs)
    {
        return "<strong>" . $total . ($wickets == 10 ? " all out" : " for " . $wickets) . " (" . $overs . " overs)" . "</strong>";
    }
}