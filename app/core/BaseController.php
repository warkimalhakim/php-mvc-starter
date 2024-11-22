<?php

namespace Warkim\core;

use Warkim\core\Session;

class BaseController
{


    public function __constructor()
    {
        new Session();
        session();
    }
}
