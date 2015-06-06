<?php
namespace App\View\Helper;

use Cake\View\Helper;

class PlayerHelper extends Helper
{
    public function name($data, $include_first_name = false)
    {
        return h($data["initials"] . " " . $data["last_name"] . ($include_first_name ? " (" . $data["first_name"] . ")" : ""));
    }

    public function scorecardSymbols($data, $match)
    {
        $return = "";
        if ($data["id"]== $match['captain_id']) {
            $return .= "*";
        }
        if ($data["id"] == $match['wicketkeeper_id']) {
            $return .= "+";
        }
        return $return;
    }

    public function best($player, $key)
    {
        if ($player[$key]["apps"] == 0) {
            return "";
        }

        $html = '<div class="one-fourth column">';

        $runsHtml = "-";
        if ($player[$key]["bestBatting"]->did_not_bat == 0) {
            $runsHtml = $player[$key]["bestBatting"]->batting_runs;
            if ($player[$key]["bestBatting"]->modes_of_dismissal->not_out == 1) {
                $runsHtml .= "*";
            }
        }

        $html .= $runsHtml;

        $html .= '</div>';


        $html .= '<div class="one-fourth column">';

        $bowlingHtml = "-";
        if (isset($player[$key]["bestBowling"]->bowling_order_no)) {
            $bowlingHtml = $player[$key]["bestBowling"]->bowling_wickets;
            $bowlingHtml .= " for ";
            $bowlingHtml .= $player[$key]["bestBowling"]->bowling_runs;
        }

        $html .= $bowlingHtml;

        $html .= '</div>';

        return $html;
    }

}