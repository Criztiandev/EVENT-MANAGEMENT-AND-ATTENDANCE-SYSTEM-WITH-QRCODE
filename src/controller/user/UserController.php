<?php

namespace controller\user;

use lib\Router\classes\Request;
use lib\Router\classes\Response;

class UserController
{

    public static function renderDashboard(Request $req, Response $res)
    {

        $res->status(200)->render("views/user/dashboard.view.php");
    }
}