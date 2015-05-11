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

    protected function _setBattingRuns($runs)
    {
        if ($this->_properties["did_not_bat"] == 1) {
            return null;
        }

        if (empty($runs)) {
            return 0;
        }
    }

    protected function _setModesOfDismissalId($mid)
    {
        if ($this->_properties["did_not_bat"] == 1) {
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


    /*
     * Validation rules (TODO)
     * player_id - no duplicates
     * dnb - if selected, null all other batting values
     * dnb - if not selected, runs, batting order no and mode of dismissal must be set
     * batting_order_no - no duplicates, min 1, max 11
     * bowling values - if left blank, null, if any set, all must be set
     * bowling_order_no - must be set if any bowling values set, no duplicates, min 1, max 11
     */

}