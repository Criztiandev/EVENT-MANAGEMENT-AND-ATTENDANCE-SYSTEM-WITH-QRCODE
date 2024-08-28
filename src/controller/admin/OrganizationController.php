<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class OrganizationController
{

    private const BASE_URL = "views/admin/organization";
    private const BASE_MODEL = "ORGANIZATION";
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
            $organizationModel = self::getBaseModel();
            $organizations = $organizationModel->find(["DELETE_STATUS" => "ACTIVE"]);

            $departmentModel = new Model("DEPARTMENT");
            $positionModel = new Model("POSITIOn");

            $transformed_organization_list = array_map( function($org) use ($departmentModel){

                $department_credentials = $departmentModel->findOne(["ID" => $org["DEPARTMENT_ID"]],["select" => "NAME"]);

                return [
                    ...$org,
                    "DEPARTMENT_NAME" => $department_credentials["NAME"],
                ];
            },$organizations);

            $position_list = $positionModel->find(["DELETE_STATUS" => "ACTIVE"]);


            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["organization_list" => $transformed_organization_list, "position_list" => $position_list]);
        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Organizations: " . $e->getMessage()]);
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

    public static function renderCreatePositionPage(Request $req, Response $res)
    {
        $organizationModel = new Model("ORGANIZATION");
        $organization_list = $organizationModel->find(["DELETE_STATUS" => "ACTIVE"]);

        $res->status(200)->render(self::BASE_URL . "/pages/position.create.page.php", ["organization_list" => $organization_list]);
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
            $organizationID = $req->query["id"];
            $organizationModel = new Model("ORGANIZATION");
            $accountModel = new Model("USERS");

            $organizations = $organizationModel->findOne(["ID" => $organizationID]);

            $account_credentials = $accountModel->findOne(
                ["ID" => $organizations["USER_ID"]],

            );

            $transformed_organizations = [
                ...$account_credentials,
                ...$organizations
            ];


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $organizationID,
                    "details" => $transformed_organizations,
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
    public static function createOrganization(Request $req, Response $res)
    {
        $payload = $req->body;
        $UID = Uuid::uuid4()->toString();

        $organizationModel = new Model("ORGANIZATION");
        $departmentModel = new Model("DEPARTMENT");


        // check if department exist
        $department_credentials = $departmentModel->findOne(["ID" => $payload["DEPARTMENT_ID"]]);
        if (!$department_credentials) {
            return $res->status(400)->redirect("/organization", ["error" => "Department doest exist"]);
        }

        $credentials = $organizationModel->createOne([
            "ID" => $UID,
            ...$payload
          ]);
          if(!$credentials){
            return $res->status(400)->redirect("/organization/create", ["error" => "Creation failed"]);
          }

        return $res->status(200)->redirect("/organization", ["success" => "Organization created successfully"]);
    }
    public static function createPosition(Request $req, Response $res)
    {
        $payload = $req->body;
        $UID = Uuid::uuid4()->toString();

        $organizationModel = new Model("ORGANIZATION");
        $positionModel = new Model("POSITION");

        $existing_organization = $organizationModel->findOne(["ID" => $payload["ORGANIZATION_ID"]]);
        if (!$existing_organization) {
         return $res->status(200)->redirect("/organization/create", ["error" => "Organization doesn't exist"]);
        }

        $credentials = $positionModel->createOne([
            ...$payload
        ]);

        if (!$credentials) {
            return $res->status(200)->redirect("/organization/create", ["error" => "Creation failed"]);
           }
   

        return $res->status(200)->redirect("/organization", ["success" => "Organization created successfully"]);
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
    public static function deleteOrganization(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $accountModel = new Model("USERS");
        $organizationModel = new Model("ORGANIZATION");



        $existOrganization = $organizationModel->findOne(["ID" => $UID], ["select" => "ID, USER_ID"]);
        if (!$existOrganization) {
            return $res->status(400)->redirect("/organization", ["error" => "Organization doesn't exist"]);
        }


        $existingAccount = $accountModel->findOne(["ID" => $existOrganization["USER_ID"]], ["select" => "ID"]);
        if (!$existingAccount) {
            return $res->status(400)->redirect("/organization", ["error" => "Account doesn't exist"]);
        }


        // delete the account
        $deleteAccount = $accountModel->deleteOne(["ID" => $existOrganization["USER_ID"]]);
        if (!$deleteAccount) {
            return $res->status(400)->redirect("/organization", ["error" => "Deletion Failed"]);
        }


        // delete the organization
        $deletedOrganization = $organizationModel->deleteOne(["ID" => $UID]);
        if (!$deletedOrganization) {
            return $res->status(400)->redirect("/organization", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/organization", ["success" => "Deleted Successfully"]);
    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}
