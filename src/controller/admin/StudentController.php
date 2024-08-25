<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class StudentController
{

    private const BASE_URL = "views/admin/student";
    private const BASE_MODEL = "STUDENT";
    private const ROLES = ["admin", "user"];

    private static function getBaseModel()
    {
        return new Model(self::BASE_MODEL);
    }



    // ================================= Renderers ======================================================


    /**
     * Display the screen of the user
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function renderScreen(Request $req, Response $res)
    {
        try {
            $studentModel = self::getBaseModel();
            $students = $studentModel->find();

            $transformed_students = [];

            foreach ($students as $details) {
                $accountResult = (new Model("USERS"))->findOne(["ID" => $details["USER_ID"]]);
                $courseResult = (new Model("COURSE"))->findOne(["ID" => $details["COURSE"]], ["select" => "NAME"]);
                $departmentResult = (new Model("DEPARTMENT"))->findOne(["ID" => $details["DEPARTMENT"]], ["select" => "NAME"]);


                $fullName = $accountResult ? $accountResult["FIRST_NAME"] . " " . $accountResult["LAST_NAME"] : "Unknown";

                $transformed_students[] = [
                    ...$details,
                    "FULL_NAME" => $fullName,
                    "COURSE" => $courseResult["NAME"],
                    "DEPARTMENT" => $departmentResult["NAME"],
                ];
            }

            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["students" => $transformed_students]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Students: " . $e->getMessage()]);
        }
    }


    /**
     * Display the create page
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function renderCreatePage(Request $req, Response $res)
    {
        $departmentModel = new Model("DEPARTMENT");
        $courseModel = new Model("COURSE");
        $courseCredentials = $courseModel->find([]);
        $departmentCredentials = $departmentModel->find([]);

        $res->status(200)->render(self::BASE_URL . "/pages/create.page.php", ["departmentList" => $departmentCredentials, "courseList" => $courseCredentials, "roles" => self::ROLES]);
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
            $studentID = $req->query["id"];
            $studentModel = new Model("STUDENT");
            $accountModel = new Model("USERS");

            $students = $studentModel->findOne(["ID" => $studentID]);

            $account_credentials = $accountModel->findOne(
                ["ID" => $students["USER_ID"]],

            );

            $transformed_students = [
                ...$account_credentials,
                ...$students
            ];


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $studentID,
                    "details" => $transformed_students,
                    "roles" => self::ROLES
                ]
            );



        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch users: " . $e->getMessage()]);
        }
    }


    // ================================= Actions ======================================================

    /**
     * Create users Handler
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function createStudent(Request $req, Response $res)
    {
        $credentials = $req->body;
        $UID = Uuid::uuid4()->toString();

        $studentModel = new Model("STUDENT");
        $accountModel = new Model("USERS");


        // check if the email exist
        $existingAccount = $accountModel->findOne(["#or" => ["EMAIL" => $credentials["EMAIL"], "PHONE_NUMBER" => $credentials["PHONE_NUMBER"]]]);

        if ($existingAccount) {
            $res->status(400)->redirect("/student/create", ["error" => "Student Already exist"]);
        }

        $existingStudent = $studentModel->findOne(["STUDENT_ID" => $credentials["STUDENT_ID"]]);

        if ($existingStudent) {
            $res->status(400)->redirect("/student/create", ["error" => "Student Already exist"]);
        }

        $hashed_password = password_hash($credentials["PASSWORD"], PASSWORD_BCRYPT, ["cost" => 10]);

        // create the account
        $accountCredentials = $accountModel->createOne([
            "ID" => $UID,
            "FIRST_NAME" => $credentials["FIRST_NAME"],
            "LAST_NAME" => $credentials["LAST_NAME"],
            "PHONE_NUMBER" => $credentials["PHONE_NUMBER"],
            "GENDER" => $credentials["GENDER"],
            "ADDRESS" => $credentials["ADDRESS"],
            "EMAIL" => $credentials["EMAIL"],
            "PASSWORD" => $hashed_password,
            "ROLE" => "student"
        ]);

        if (!$accountCredentials) {
            $res->status(400)->redirect("/student/create", ["error" => "Create Student Account Failed"]);
        }

        $studentCredentials = $studentModel->createOne([
            "ID" => Uuid::uuid4()->toString(),
            "USER_ID" => $UID,
            "STUDENT_ID" => $credentials["STUDENT_ID"],
            "YEAR_LEVEL" => $credentials["YEAR_LEVEL"],
            "DEPARTMENT" => $credentials["DEPARTMENT_ID"],
            "COURSE" => $credentials["COURSE_ID"],
        ]);


        if (!$studentCredentials) {
            $res->status(400)->redirect("/student/create", ["error" => "Create Student Details Failed"]);
        }


        return $res->status(200)->redirect("/student/create", ["success" => "Student created successfully"]);
    }

    /**
     * Update user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function updateUser(Request $req, Response $res)
    {


        // $res->status(200)->redirect("/users/update?id=" . $UID, ["success" => "Update Successfull"]);
    }

    /**
     * Delete user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function deleteStudent(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $accountModel = new Model("USERS");
        $studentModel = new Model("STUDENT");



        $existStudent = $studentModel->findOne(["ID" => $UID], ["select" => "ID, USER_ID"]);
        if (!$existStudent) {
            return $res->status(400)->redirect("/student", ["error" => "Student doesn't exist"]);
        }


        $existingAccount = $accountModel->findOne(["ID" => $existStudent["USER_ID"]], ["select" => "ID"]);
        if (!$existingAccount) {
            return $res->status(400)->redirect("/student", ["error" => "Account doesn't exist"]);
        }


        // delete the account
        $deleteAccount = $accountModel->deleteOne(["ID" => $existStudent["USER_ID"]]);
        if (!$deleteAccount) {
            return $res->status(400)->redirect("/student", ["error" => "Deletion Failed"]);
        }


        // delete the student
        $deletedStudent = $studentModel->deleteOne(["ID" => $UID]);
        if (!$deletedStudent) {
            return $res->status(400)->redirect("/student", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/student", ["success" => "Deleted Successfully"]);


    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}