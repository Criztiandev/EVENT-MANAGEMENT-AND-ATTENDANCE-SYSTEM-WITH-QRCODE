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

            $transformed_organization_list = array_map(function ($org) use ($departmentModel) {

                $department_credentials = $departmentModel->findOne(["ID" => $org["DEPARTMENT_ID"]], ["select" => "NAME"]);

                return [
                    ...$org,
                    "DEPARTMENT_NAME" => $department_credentials["NAME"],
                ];
            }, $organizations);

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
            $credentials = $req->body;
            $organizationModel = new Model("ORGANIZATION");
            $departmentModel = new Model("DEPARTMENT");

            $organization_credentials = $organizationModel->findOne(["ID" => $organizationID]);
            $department_list = $departmentModel->find([]);



            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $organizationID,
                    "details" => $organization_credentials,
                    "department_list" => $department_list,
                    "roles" => self::ROLES
                ]
            );
        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch users: " . $e->getMessage()]);
        }
    }

    public static function renderPositionUpdatePage(Request $req, Response $res)
    {
        try {

            $positionID = $req->query["id"];
            $credentials = $req->body;
            $positionModel = new Model("POSITION");
            $organizationModel = new Model("ORGANIZATION");

            $position_credentials = $positionModel->findOne(["ID" => $positionID]);
            $organization_list = $organizationModel->find([]);



            $res->status(200)->render(
                self::BASE_URL . "/pages/position.update.page.php",
                [
                    "UID" => $positionID,
                    "details" => $position_credentials,
                    "organization_list" => $organization_list,
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


        // check if the name is already created
        $existing_organization = $organizationModel->findOne(["NAME" => $payload["NAME"]]);
        if ($existing_organization) {
            return $res->status(400)->redirect("/organization/create", ["error" => "Name is already exist"]);
        }


        $credentials = $organizationModel->createOne([
            "ID" => $UID,
            ...$payload
        ]);
        if (!$credentials) {
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
            return $res->status(200)->redirect("/organization/position/create", ["error" => "Organization doesn't exist"]);
        }

        // check if position name is already created
        $existing_position = $positionModel->findOne(["NAME" => $payload["NAME"]]);
        if ($existing_position) {
            return $res->status(200)->redirect("/organization/position/create", ["error" => "Title is already exist"]);
        }

        $credentials = $positionModel->createOne([
            "ID" => $UID,
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
    public static function updateOrganization(Request $req, Response $res)
    {
        $organization_id = $req->query["id"];
        $credentials = $req->body;

        $organization_model = new Model("ORGANIZATION");

        $organization_credentials = $organization_model->findOne(["ID" => $organization_id]);
        if (!$organization_credentials) {
            return $res->status(200)->redirect("/organization/update?id=" . $organization_id, ["error" => "Organization doesn't exist "]);
        }

        $updated_organization_credentials = $organization_model->updateOne([
            "NAME" => $credentials["NAME"],
            "DEPARTMENT_ID" => $credentials["DEPARTMENT_ID"],
        ], ["ID" => $organization_id]);

        if (!$updated_organization_credentials) {
            return $res->status(200)->redirect("/organization/update?id=" . $organization_id, ["error" => "Update Credentials failed"]);
        }

        $res->status(200)->redirect("/organization/update?id=" . $organization_id, ["success" => "Update Successfull"]);
    }

    /**
     * Update user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function updatePosition(Request $req, Response $res)
    {
        $position_id = $req->query["id"];
        $credentials = $req->body;

        $position_model = new Model("POSITION");

        $position_credentials = $position_model->findOne(["ID" => $position_id]);
        if (!$position_credentials) {
            return $res->status(200)->redirect("/organization/position/update?id=" . $position_id, ["error" => "Position doesn't exist "]);
        }

        $updated_position_credentials = $position_model->updateOne([
            "NAME" => $credentials["NAME"],
            "ORGANIZATION_ID" => $credentials["ORGANIZATION_ID"],
        ], ["ID" => $position_id]);

        if (!$updated_position_credentials) {
            return $res->status(200)->redirect("/organization/position/update?id=" . $position_id, ["error" => "Update Credentials failed"]);
        }

        $res->status(200)->redirect("/organization/position/update?id=" . $position_id, ["success" => "Update Successfull"]);
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
        $organizationModel = new Model("ORGANIZATION");

        $existOrganization = $organizationModel->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$existOrganization) {
            return $res->status(400)->redirect("/organization", ["error" => "Organization doesn't exist"]);
        }

        // delete the organization
        $deletedOrganization = $organizationModel->deleteOne(["ID" => $UID]);
        if (!$deletedOrganization) {
            return $res->status(400)->redirect("/organization", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/organization", ["success" => "Deleted Successfully"]);
    }

    public static function deletePosition(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $positionModel = new Model("POSITION");

        $existPosition = $positionModel->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$existPosition) {
            return $res->status(400)->redirect("/organization", ["error" => "Position doesn't exist"]);
        }

        // delete the position
        $deletedPosition = $positionModel->deleteOne(["ID" => $UID]);
        if (!$deletedPosition) {
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
