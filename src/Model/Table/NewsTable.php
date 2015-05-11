<?php
/**
 * Created by PhpStorm.
 * User: garethellis
 * Date: 11/05/15
 * Time: 20:08
 */

namespace App\Model\Table;
use Cake\Validation\Validator;

use Cake\ORM\Table;

class NewsTable extends Table {

    public function initialize(array $config)
    {
        $this->belongsTo("Users");
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('title', 'valid', ['rule' => 'notBlank'])
            ->notEmpty('title');

        $validator
            ->add('body', 'valid', ['rule' => 'notBlank'])
            ->notEmpty('body');

        return $validator;
    }

}