<?php
namespace App\View\Helper;

use Cake\View\Helper;

class PlayerHelper extends Helper
{
    public function name($data, $include_first_name = false)
    {
        return h($data["initials"] . " " . $data["last_name"] . ($include_first_name ? " (" . $data["first_name"] . ")" : ""));
    }
}