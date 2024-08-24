<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Uuid;

class ManageUserController
{

    private const BASE_URL = "views/admin/users";
    private const BASE_MODEL = "USERS";
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
            $userModel = self::getBaseModel();
            $users = $userModel->find();
            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["users" => $users]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch users: " . $e->getMessage()]);
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

        $res->status(200)->render(self::BASE_URL . "/pages/create.page.php", ["roles" => self::ROLES]);
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
            $UID = $req->query["id"];

            $userModel = self::getBaseModel();

            $crendtials = $userModel->findOne(["ID" => $UID]);

            if (!$crendtials) {
                $res->status(400)->redirect("/users/update?id=" . $UID, ["error" => "User already exist"]);
            }

            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $UID,
                    "details" => $crendtials,
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
    public static function createUser(Request $req, Response $res)
    {
        $email = $req->body["EMAIL"] ?? null;
        $password = $req->body["PASSWORD"] ?? null;
        $phone_number = $req->body["PHONE_NUMBER"] ?? null;

        $user_model = self::getBaseModel();

        // Check if the user exist
        if (self::userExist($email, $phone_number)) {
            $res->status(400)->redirect("/users/create", ["error" => "User already exist"]);
        }

        // hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
        unset($req->body["PASSWORD"]);

        // generate UUID
        $UID = Uuid::uuid4()->toString();

        // Store the payload
        $result = $user_model->createOne([
            "ID" => $UID,
            ...$req->body,
            "PASSWORD" => $hashed_password,
        ]);

        if (!$result) {
            $res->status(400)->redirect("/users/create", ["error" => "Something went wrong"]);
        }

        $res->status(200)->redirect("/users/create", ["success" => "User Created uccessfully"]);

    }

    /**
     * Update user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function updateUser(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $password = $req->body["PASSWORD"];

        unset($req->body["_method"]);

        // check if user exist
        $credentials = (self::getBaseModel())->findOne(["ID" => $UID], ["select" => "ID"]);

        if (!$credentials) {
            $res->status(400)->redirect("/users/update?id=" . $UID, ["error" => "User doesnt exist"]);
        }

        // Deattach the password if empty else if exist then hash it
        if (!isset($password) || $password === "") {
            unset($req->body["PASSWORD"]);
        } else if (isset($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
            $req->body["PASSWORD"] = $hashed_password;
        }

        // Update Credentials
        $result = (self::getBaseModel())->updateOne(
            [...$req->body],
            ["ID" => $UID]
        );

        if (!$result) {
            $res->status(400)->redirect("/users/update?id=" . $UID, ["error" => "Update failed"]);
        }

        $res->status(200)->redirect("/users/update?id=" . $UID, ["success" => "Update Successfull"]);
    }

    /**
     * Delete user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function deleteUser(Request $req, Response $res)
    {
        $UID = $req->query["id"];

        // check if user exist
        $credentials = (self::getBaseModel())->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$credentials) {
            $res->status(400)->redirect("/users", ["error" => "User doesnt exist"]);
        }

        // delete the user
        $result = (self::getBaseModel())->deleteOne(["ID" => $UID]);
        if (!$result) {
            $res->status(400)->redirect("/users", ["error" => "Something went wrong"]);
        }
        $res->status(200)->redirect("/users", ["success" => "Deleted uccessfully"]);


    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}