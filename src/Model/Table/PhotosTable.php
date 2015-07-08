<?php

namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\ORM\Table;

class PhotosTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('title', 'maxLength', ['rule' => ['maxLength', 255]])
            ->allowEmpty('title');

        return $validator;
    }
}