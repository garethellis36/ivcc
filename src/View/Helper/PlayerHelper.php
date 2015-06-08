<?php
namespace App\View\Helper;

use Cake\View\Helper;

class PlayerHelper extends Helper
{
    public function name($data, $include_first_name = false)
    {
        return h($data["initials"] . $data["last_name"] . ($include_first_name ? " (" . $data["first_name"] . ")" : ""));
    }

    /*
     * Truncate a name so that it fits on the scorecard on a mobile without breaking lines
     */
    public function truncateName($data, $splitDoubleBarrelled = true, $maxChars = 20)
    {
        $name = $this->name($data);
        $length = strlen($name);

        if ($length <= $maxChars) {
            return $this->name($data);
        }

        if ($splitDoubleBarrelled === false) {
            return substr_replace($name, "'", $maxChars/2, strlen($name)-$maxChars);
        }

        //split name into constituent parts if double-barrelled
        $parts = explode("-", $data["last_name"]);

        //reduce parts of double-barrelled names to just first letter, except last part
        if (count($parts) > 1) {
            $last_part = array_pop($parts);
            foreach ($parts as &$part) {
                $part = substr($part, 0, 1);
            }
            $data["last_name"] = implode("-", $parts) . "-" . $last_part;
        }

        return $this->truncateName($data, false, $maxChars);
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