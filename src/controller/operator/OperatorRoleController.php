<?php

namespace controller\operator;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;

class OperatorRoleController
{

    public static function renderDashboard(Request $req, Response $res)
    {   
        $UID = $_SESSION["UID"];
        $eventsModel = new Model("EVENT");
        $operator_credentials = (new Model("OPERATOR"))->findOne(["USER_ID" => $UID]);


        $finished_events = $eventsModel->find(["STATUS" => "END"]);
        $upcoming_events = $eventsModel->find(["STATUS" => "ACTIVE"]);

        // filter events by organization only
        $filtered_upcoming_event = array_filter($upcoming_events, function($event) use ($operator_credentials) {
            return $event["ORGANIZATION_ID"] === $operator_credentials["ORGANIZATION_ID"];
        });

        $filtered_finished_event = array_filter($finished_events, function($event) use ($operator_credentials) {
            return $event["ORGANIZATION_ID"] === $operator_credentials["ORGANIZATION_ID"];
        });




        $transformed_upcoming_event = array_map(function ($event) {
            $organizationCredentials = (new Model("ORGANIZATION"))->findOne(["ID" => $event["ORGANIZATION_ID"]],["select" => "NAME"]);
            return[
                ...$event,
                "ORGANIZATION_NAME" => $organizationCredentials["NAME"]
            ];

        }, $filtered_upcoming_event);

        $transformed_finished_event = array_map(function ($event) {
            $organizationCredentials = (new Model("ORGANIZATION"))->findOne(["ID" => $event["ORGANIZATION_ID"]],["select" => "NAME"]);
            return[
                ...$event,
                "ORGANIZATION_NAME" => $organizationCredentials["NAME"]
            ];

        }, $filtered_finished_event);


        $res->status(200)->render("views/operator/dashboard.view.php", ["upcoming_events" => $transformed_upcoming_event, "finished_events" => $transformed_finished_event]);
    }


}