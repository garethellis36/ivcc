<?php

namespace App\Model\Table;

use Cake\Validation\Validator;
use SoftDelete\Model\Table\SoftDeleteTrait;

class AwardsTable extends AppTable
{
    use SoftDeleteTrait;

    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator->add('name', 'valid', ['rule' => ['maxLength', '255']])
            ->notEmpty('name')
            ->add('order_number', 'valid', ['rule' => 'naturalNumber'])
            ->notEmpty('order_number');
    }
}