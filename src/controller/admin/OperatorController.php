<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class OperatorController
{

    private const BASE_URL = "views/admin/operator";
    private const BASE_MODEL = "OPERATOR";
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
            $operatorModel = self::getBaseModel();
            $operators = $operatorModel->find();

            $transformed_operators = [];

            foreach ($operators as $details) {
                $accountModel = new Model("USERS");
                $user = $accountModel->findOne(
                    ["ID" => $details["USER_ID"]],
                    ["select" => "FIRST_NAME, LAST_NAME"]
                );

                $fullName = $user ? $user["FIRST_NAME"] . " " . $user["LAST_NAME"] : "Unknown";

                $transformed_operators[] = [
                    "FULL_NAME" => $fullName,
                    ...$details
                ];
            }


            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["operators" => $transformed_operators]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Operators: " . $e->getMessage()]);
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
            $operatorID = $req->query["id"];
            $operatorModel = new Model("OPERATOR");
            $accountModel = new Model("USERS");

            $operators = $operatorModel->findOne(["ID" => $operatorID]);

            $account_credentials = $accountModel->findOne(
                ["ID" => $operators["USER_ID"]],

            );

            $transformed_operators = [
                ...$account_credentials,
                ...$operators
            ];


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $operatorID,
                    "details" => $transformed_operators,
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
    public static function createOperator(Request $req, Response $res)
    {
        $credentials = $req->body;
        $UID = Uuid::uuid4()->toString();

        $operatorModel = new Model("OPERATOR");
        $accountModel = new Model("USERS");

        // check if the email exist
        $existingAccount = $accountModel->findOne(["#or" => ["EMAIL" => $credentials["EMAIL"], "PHONE_NUMBER" => $credentials["PHONE_NUMBER"]]]);

        if ($existingAccount) {
            $res->status(400)->redirect("/operator/create", ["error" => "Operator Already exist"]);
        }

        $existingOperator = $operatorModel->findOne(["OPERATOR_ID" => $credentials["OPERATOR_ID"]]);

        if ($existingOperator) {
            $res->status(400)->redirect("/operator/create", ["error" => "Operator Already exist"]);
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
            "ROLE" => "operator"
        ]);

        if (!$accountCredentials) {
            $res->status(400)->redirect("/operator/create", ["error" => "Create Operator Account Failed"]);
        }

        $operatorCredentials = $operatorModel->createOne([
            "ID" => Uuid::uuid4()->toString(),
            "USER_ID" => $UID,
            "OPERATOR_ID" => $credentials["OPERATOR_ID"],
            "ORGANIZATION" => $credentials["ORGANIZATION"],
            "POSITION" => $credentials["POSITION"],
        ]);


        if (!$operatorCredentials) {
            $res->status(400)->redirect("/operator/create", ["error" => "Create Operator Details Failed"]);
        }


        return $res->status(200)->redirect("/operator/create", ["success" => "Operator created successfully"]);
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
    public static function deleteOperator(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $accountModel = new Model("USERS");
        $operatorModel = new Model("OPERATOR");



        $existOperator = $operatorModel->findOne(["ID" => $UID], ["select" => "ID, USER_ID"]);
        if (!$existOperator) {
            return $res->status(400)->redirect("/operator", ["error" => "Operator doesn't exist"]);
        }


        $existingAccount = $accountModel->findOne(["ID" => $existOperator["USER_ID"]], ["select" => "ID"]);
        if (!$existingAccount) {
            return $res->status(400)->redirect("/operator", ["error" => "Account doesn't exist"]);
        }


        // delete the account
        $deleteAccount = $accountModel->deleteOne(["ID" => $existOperator["USER_ID"]]);
        if (!$deleteAccount) {
            return $res->status(400)->redirect("/operator", ["error" => "Deletion Failed"]);
        }


        // delete the operator
        $deletedOperator = $operatorModel->deleteOne(["ID" => $UID]);
        if (!$deletedOperator) {
            return $res->status(400)->redirect("/operator", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/operator", ["success" => "Deleted Successfully"]);


    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}