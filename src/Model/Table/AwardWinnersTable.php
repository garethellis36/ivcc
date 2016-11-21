<?php

namespace App\Model\Table;

use Cake\Validation\Validator;

class AwardWinnersTable extends AppTable
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('Players', ['joinType' => 'INNER']);
        $this->belongsTo('Awards', ['joinType' => 'INNER']);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('player_id')
            ->notEmpty('award_id')
            ->add('year', 'valid', ['rule' => ['range', 2014, date('Y')]]);
    }
}