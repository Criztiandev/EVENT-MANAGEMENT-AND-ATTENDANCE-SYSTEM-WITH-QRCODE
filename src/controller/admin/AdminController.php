<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;

class AdminController
{

    public static function renderDashboard(Request $req, Response $res)
    {
        $eventsModel = new Model("EVENT");

        $finished_events = $eventsModel->find(["STATUS" => "DONE"]);
        $upcoming_events = $eventsModel->find(["STATUS" => "ACTIVE"]);


        $res->status(200)->render("views/admin/dashboard.view.php", ["upcoming_events" => $upcoming_events, "finished_events" => $finished_events]);
    }
}