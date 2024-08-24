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
        $res->status(200)->render("views/auth/register.view.php");
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
            "role" => "admin",
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
    public static function registerUser(Request $req, Response $res)
    {
        $email = $req->body["EMAIL"];
        $password = $req->body["PASSWORD"];
        $phone_number = $req->body["PHONE_NUMBER"];

        // check if user exist
        $userModel = new Model("USERS");

        $user_exist = $userModel->findOne(["#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]], ["select" => "ID"]);
        if ($user_exist) {
            $res->status(400)->redirect("/register", ["error" => "User already exist"]);
        }


        $hashed_password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
        unset($req->body["PASSWORD"]);

        $UID = Uuid::uuid4()->toString();
        $result = $userModel->createOne([
            "ID" => $UID,
            ...$req->body,
            "PASSWORD" => $hashed_password,
        ]);

        if (!$result) {
            $res->status(400)->redirect("/register", ["error" => "User already exist"]);
        }

        $res->status(200)->redirect("/", ["success" => "Registered successfully"]);


    }









}