<?php

namespace controller\general;


use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;

class AccountController
{

    public static function logout(Request $req, Response $res)
    {
        Express::Session()->destroy();
        $res->status(200)->redirect("/");
    }
}