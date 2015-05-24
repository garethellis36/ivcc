<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 17/05/15
 * Time: 18:38
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class AppTable extends Table
{

    public function validOvers($value)
    {
        if (!is_numeric($value)) {
            return false;
        }

        if (stripos($value, '.') === false) {
            return true;
        }

        $parts = explode(".", $value);
        return ($parts[1] < 6);
    }


    public function validInteger($value)
    {
        //debug(preg_match("/^[0-9]*$/", $value));
        return (bool)preg_match("/^[0-9]*$/", $value);
    }
}