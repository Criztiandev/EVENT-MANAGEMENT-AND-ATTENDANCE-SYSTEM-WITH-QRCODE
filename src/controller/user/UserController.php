<?php

namespace controller\user;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;

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
            $organizationCredentials = (new Model("ORGANIZATION"))->findOne(["ID" => $event["ORGANIZATION_ID"]], ["select" => "NAME"]);
            return [
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

    /**
     * Display the Update Page
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function renderUpdatePage(Request $req, Response $res)
    {
        try {
            $accountID = Express::Session()->get("UID");
            $studentModel = new Model("STUDENT");
            $accountModel = new Model("USERS");
            $courseModel = new Model("COURSE");
            $departmentModel = new Model("DEPARTMENT");

            $account_credentials = $accountModel->findOne(
                ["ID" => $accountID],
            );

            $students = $studentModel->findOne(["USER_ID" => $accountID]);
            $department_list = $departmentModel->find([]);
            $course_list = $courseModel->find([]);




            $transformed_students = [
                ...$account_credentials,
                ...$students
            ];

            $res->status(200)->render(
                "views/user/pages/update.page.php",
                [
                    "UID" => $students["ID"],
                    "department_list" => $department_list,
                    "course_list" => $course_list,
                    "student_details" => $transformed_students,
                ]
            );



        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch users: " . $e->getMessage()]);
        }
    }


    public static function updateStudent(Request $req, Response $res)
    {

        $student_id = $req->query["id"];
        $credentials = $req->body;


        $studentModel = new Model("STUDENT");
        $accountModel = new Model("USERS");

        $student_credentials = $studentModel->findOne(["ID" => $student_id]);
        $account_credentials = $accountModel->findOne(["ID" => $student_credentials["USER_ID"]]);

        if (!$student_credentials || !$account_credentials) {
            $res->status(400)->redirect("/profile", ["error" => "Student does'nt exist "]);
        }

        $updated_account_credentials = $accountModel->updateOne([
            "FIRST_NAME" => $credentials["FIRST_NAME"],
            "LAST_NAME" => $credentials["LAST_NAME"],
            "PHONE_NUMBER" => $credentials["PHONE_NUMBER"],
            "GENDER" => $credentials["GENDER"],
            "ADDRESS" => $credentials["ADDRESS"],
            "EMAIL" => $credentials["EMAIL"],
        ], ["ID" => $student_credentials["USER_ID"]]);

        if (!$updated_account_credentials) {
            $res->status(400)->redirect("/profile", ["error" => "Update Student Account Failed"]);
        }

        $updated_student_credentials = $studentModel->updateOne([
            "STUDENT_ID" => $credentials["STUDENT_ID"],
            "YEAR_LEVEL" => $credentials["YEAR_LEVEL"],
            "DEPARTMENT_ID" => $credentials["DEPARTMENT_ID"],
            "COURSE_ID" => $credentials["COURSE_ID"],
        ], ["ID" => $student_id]);


        if (!$updated_student_credentials) {
            $res->status(400)->redirect("/profile", ["error" => "Update Student Details Failed"]);
        }


        $res->status(200)->redirect("/profile", ["success" => "Update Successfully"]);
    }

}