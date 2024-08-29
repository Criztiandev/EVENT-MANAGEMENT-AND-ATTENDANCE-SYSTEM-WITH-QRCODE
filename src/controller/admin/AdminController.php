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

        $finished_events = $eventsModel->find(["STATUS" => "END"]);
        $upcoming_events = $eventsModel->find(["STATUS" => "ACTIVE"]);


        $transformed_upcoming_event = array_map(function ($event) {
            $organizationCredentials = (new Model("ORGANIZATION"))->findOne(["ID" => $event["ORGANIZATION_ID"]],["select" => "NAME"]);
            return[
                ...$event,
                "ORGANIZATION_NAME" => $organizationCredentials["NAME"]
            ];

        }, $upcoming_events);

        $transformed_finished_event = array_map(function ($event) {
            $organizationCredentials = (new Model("ORGANIZATION"))->findOne(["ID" => $event["ORGANIZATION_ID"]],["select" => "NAME"]);
            return[
                ...$event,
                "ORGANIZATION_NAME" => $organizationCredentials["NAME"]
            ];

        }, $finished_events);


        $res->status(200)->render("views/admin/dashboard.view.php", ["upcoming_events" => $transformed_upcoming_event, "finished_events" => $transformed_finished_event]);
    }
}