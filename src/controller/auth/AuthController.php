<?php

namespace controller\auth;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;
use Ramsey\Uuid\Uuid;

class AuthController
{

    /**
     * Display the login screen
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function loginScreen(Request $req, Response $res)
    {
        $res->status(200)->render("views/auth/login.view.php", );
    }



    /**
     * Display the registration screen
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function registerScreen(Request $req, Response $res)
    {
        $departmentModel = new Model("DEPARTMENT");
        $courseModel = new Model("COURSE");

        $deparment_list = $departmentModel->find([]);
        $course_list = $courseModel->find([]);

        $res->status(200)->render("views/auth/register.view.php", [
            "department_list" => $deparment_list,
            "course_list" => $course_list,
        ]);
    }


    /**
     * Authentication of user
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function authenticateUser(Request $req, Response $res)
    {
        $email = $req->body["EMAIL"];
        $password = $req->body["PASSWORD"];

        $userModel = new Model("USERS");
        $user_exist = $userModel->findOne(["EMAIL" => $email]);


        if (!$user_exist) {
            $res->status(400)->redirect("/", ["error" => "User doesnt exist"]);
        }

        // if (!$user_exist && !password_verify($password, $user_exist["PASSWORD"])) {
        //     $res->status(400)->redirect("/", ["error" => "Password is in correct"]);
        // }

        // create session
        Express::Session()->insert("UID", $user_exist["ID"]);

        Express::Session()->insert("credentials", [
            "fullName" => $user_exist["FIRST_NAME"] . " " . $user_exist["LAST_NAME"],
            "email" => $user_exist["EMAIL"],
            "role" => $user_exist["ROLE"],
        ]);
        session_regenerate_id(true);

        $res->status(200)->redirect("/", ["success" => "Login successfully"]);

    }


    /**
     * Registration of the user
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function registerStudent(Request $req, Response $res)
    {
        $credentials = $req->body;
        $UID = Uuid::uuid4()->toString();

        $studentModel = new Model("STUDENT");
        $accountModel = new Model("USERS");


        // check if the email exist

        $existingEmail = $accountModel->findOne(["EMAIL" => $credentials["EMAIL"],]);

        if ($existingEmail) {
            $res->status(400)->redirect("/register", ["error" => "Email  Already exist"]);
        }

        $existingPhoneNumber = $accountModel->findOne(["PHONE_NUMBER" => $credentials["PHONE_NUMBER"],]);

        if ($existingPhoneNumber) {
            $res->status(400)->redirect("/register", ["error" => "Phone number  Already exist"]);
        }

        $existingStudent = $studentModel->findOne(["STUDENT_ID" => $credentials["STUDENT_ID"]]);

        if ($existingStudent) {
            $res->status(400)->redirect("/register", ["error" => "Student Already exist"]);
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
            $res->status(400)->redirect("/register", ["error" => "Create Student Account Failed"]);
        }

        $studentCredentials = $studentModel->createOne([
            "ID" => Uuid::uuid4()->toString(),
            "USER_ID" => $UID,
            "STUDENT_ID" => $credentials["STUDENT_ID"],
            "YEAR_LEVEL" => $credentials["YEAR_LEVEL"],
            "DEPARTMENT_ID" => $credentials["DEPARTMENT_ID"],
            "COURSE_ID" => $credentials["COURSE_ID"],
        ]);


        if (!$studentCredentials) {
            $res->status(400)->redirect("/register", ["error" => "Create Student Details Failed"]);
        }


        return $res->status(200)->redirect("/register", ["success" => "Student created successfully"]);
    }









}