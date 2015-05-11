<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


class Match extends Entity
{

    protected function _setResult($result)
    {
        return (empty($result) ? null : $result);
    }

}
