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
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('title', 'valid', ['rule' => 'notBlank'])
            ->notEmpty('title')
            ->add('title', 'length', ['rule' => ['lengthBetween', 1, 100]]);

        $validator
            ->add('body', 'valid', ['rule' => 'notBlank'])
            ->notEmpty('body');

        return $validator;
    }

}