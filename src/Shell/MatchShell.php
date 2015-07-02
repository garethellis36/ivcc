<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Utility\Inflector;

class MatchShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Matches');
    }

    public function setSlugs()
    {
        $matches = $this->Matches->find("all");
        foreach ($matches as $match) {
            $match->opposition_slug = substr(strtolower(Inflector::slug($match->opposition)), 0, 255);
            $this->Matches->save($match);
        }
    }
}