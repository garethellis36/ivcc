<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 15/05/15
 * Time: 10:07
 */

namespace App\Model\Entity;

use Cake\ORM\Entity;

class MatchesPlayer extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'id' => true,
        'match_id' => true,
        'player_id' => true,
        'batting_order_no' => true,
        'did_not_bat' => true,
        'batting_runs' => true,
        'modes_of_dismissal_id' => true,
        'bowling_overs' => true,
        'bowling_maidens' => true,
        'bowling_runs' => true,
        'bowling_wickets' => true,
        'bowling_order_no' => true,
    ];

    protected function _setBattingRuns($runs = null)
    {
        if (isset($this->_properties["did_not_bat"]) && $this->_properties["did_not_bat"] == 1) {
            return null;
        }

        return $runs;
    }

    protected function _setDidNotBat($dnb)
    {
        if ($dnb == 1) {
            $this->_properties["batting_runs"] = $this->_setBattingRuns();
            $this->_properties["modes_of_dismissal_id"] = $this->_setModesOfDismissalId();
        }

        return $dnb;
    }

    protected function _setModesOfDismissalId($mid = null)
    {
        if (isset($this->_properties["did_not_bat"]) && $this->_properties["did_not_bat"] == 1) {
            return null;
        }
        return $mid;
    }

    protected function _setBowlingOvers($overs)
    {
        return $this->setEmptyToNull($overs);
    }

    protected function _setBowlingMaidens($overs)
    {
        return $this->setEmptyToNull($overs);
    }

    protected function _setBowlingRuns($overs)
    {
        return $this->setEmptyToNull($overs);
    }

    protected function _setBowlingWickets($overs)
    {
        return $this->setEmptyToNull($overs);
    }

    protected function _setBowlingOrderNo($overs)
    {
        return $this->setEmptyToNull($overs);
    }

    private function setEmptyToNull($var)
    {
        if (empty($var) && $var != "0") {
            return null;
        }
        return $var;
    }

}