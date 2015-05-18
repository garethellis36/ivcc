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

    protected function _setFirstName($first_name)
    {
        return $this->setNameCases($first_name);
    }

    protected function _setLastName($last_name)
    {
        return $this->setNameCases($last_name);
    }

    protected function setNameCases($name)
    {
        $parts = explode(" ", $name);

        $name = "";
        if (count($parts) > 1) {
            foreach ($parts as $part) {
                $name .= ucfirst(strtolower($part))  . " ";
            }
            return trim($name);
        }
        return trim(ucfirst(strtolower($parts[0])));
    }

}