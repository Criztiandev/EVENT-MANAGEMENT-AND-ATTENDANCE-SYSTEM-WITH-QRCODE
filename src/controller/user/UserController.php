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
        $organizationModel = new Model("ORGANIZATION");

        $currentCredentials = $studentModel->findOne(["USER_ID" => $_SESSION["UID"]]);
        $event_list = $eventsModel->find(["STATUS" => "ACTIVE"]);

        $transformed_event_list = array_map(function ($event) {
            $organizationCredentials = (new Model("ORGANIZATION"))->findOne(["ID" => $event["ORGANIZATION_ID"]],["select" => "NAME"]);
            return[
                ...$event,
                "ORGANIZATION_NAME" => $organizationCredentials["NAME"]
            ];

        }, $event_list);

        $allowed_events = array_filter($transformed_event_list, function ($event) use ($currentCredentials) {
            $yearLevelMatch = $event["YEAR_LEVEL"] == $currentCredentials["YEAR_LEVEL"];
            $courseMatch = $event["COURSE_ID"] == $currentCredentials["COURSE_ID"];
            $departmentMatch = $event["DEPARTMENT_ID"] == $currentCredentials["DEPARTMENT_ID"];

            return $yearLevelMatch && $courseMatch && $departmentMatch;
        });



        $res->status(200)->render("views/user/dashboard.view.php", ["events" => $allowed_events]);
    }
}