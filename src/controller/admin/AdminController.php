<?php

namespace controller\admin;

use lib\Router\classes\Request;
use lib\Router\classes\Response;

class AdminController
{

    public static function renderDashboard(Request $req, Response $res)
    {

        $res->status(200)->render("views/user/dashboard.view.php");
    }
}