<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Inflector;


class Match extends Entity
{

    protected function _setResult($result)
    {
        return (empty($result) ? null : $result);
    }

    protected function _getResultMore($result)
    {
        return strtolower($result);
    }

}
