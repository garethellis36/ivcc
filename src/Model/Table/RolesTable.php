<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:08
 */

namespace App\Model\Table;

use Cake\ORM\Table;

class RolesTable extends Table {

    public function getList()
    {
        $query = $this->find("list");
        return $query->toArray();
    }

}