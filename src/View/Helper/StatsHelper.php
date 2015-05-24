<?php
namespace App\View\Helper;

use App\Model\Entity\Player;
use Cake\View\Helper;
use App\View\Helper\PlayerHelper;

class StatsHelper extends Helper
{
    public function leading($stats, $field)
    {

        if (!isset($stats[$field])) {
            return "-";
        }

        $playerHelper = new PlayerHelper($this->_View);

        $html = number_format($stats[$field][0]->total) . ":&nbsp;";

        $first = true;
        foreach ($stats[$field] as $stat) {
            if ($first) {
                $first = false;
            } else {
                $html .= ",&nbsp;";
            }
            $html .= $playerHelper->name($stat->player);
        }

        return $html;

    }
}