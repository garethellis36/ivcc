<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Controller\Component\AuthComponent;

class AuthorizationHelper extends Helper
{
    public function isAdmin()
    {
        $authUser = $this->_View->viewVars["authUser"];
        return ($authUser["is_admin"] == 1);
    }
}