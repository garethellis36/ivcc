<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:09
 */

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Player extends Entity {

    protected function _getName()
    {
        return $this->_properties['initials'] . " " . $this->_properties["last_name"];
    }

    protected function _setInitials($initials)
    {
        return trim(strtoupper($initials));
    }

}