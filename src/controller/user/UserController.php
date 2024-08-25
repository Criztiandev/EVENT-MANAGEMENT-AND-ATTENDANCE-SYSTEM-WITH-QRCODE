<?php

namespace controller\user;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;

class UserController
{

    public static function renderDashboard(Request $req, Response $res)
    {
        $studentModel = new Model("STUDENT");
        $eventsModel = new Model("EVENT");

        // $currentCredentials = $studentModel->findOne(["USER_ID" => $_SESSION["UID"]]);


        $event_list = $eventsModel->find(["STATUS" => "ACTIVE"]);
        $res->status(200)->render("views/user/dashboard.view.php");
    }
}