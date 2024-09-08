<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class DepartmentController
{

    private const BASE_URL = "views/admin/department";
    private const BASE_MODEL = "DEPARTMENT";
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
            $departmentModel = self::getBaseModel();
            $departments = $departmentModel->find();
            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["departments" => $departments]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Departments: " . $e->getMessage()]);
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
            $departmentID = $req->query["id"];
            $departmentModel = new Model("DEPARTMENT");

            $credentials = $departmentModel->findOne(["ID" => $departmentID]);


            if (!$credentials) {
                $res->status(400)->redirect("/users/update?id=" . $departmentID, ["error" => "Department doesn't exist"]);
            }


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $departmentID,
                    "details" => $credentials,
                    "roles" => self::ROLES
                ]
            );



        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch users: " . $e->getMessage()]);
        }
    }


    public static function renderSessiion(Request $req, Response $res)
    {
        try {

            $res->status(200)->render(self::BASE_URL . "/pages/session-start.php");
        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Departments: " . $e->getMessage()]);
        }
    }


    // ================================= Actions ======================================================

    /**
     * Create users Handler
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function createDepartment(Request $req, Response $res)
    {
        $credentials = $req->body;
        $departmentModel = new Model("DEPARTMENT");

        $existingDepartment = $departmentModel->findOne(["NAME" => $credentials["NAME"]]);

        if ($existingDepartment) {
            return $res->status(400)->redirect("/department/create", ["error" => "Department already exists"]);
        }

        $UID = Uuid::uuid4()->toString();
        $createdDepartment = $departmentModel->createOne([
            "ID" => $UID,
            ...$credentials,
            "STATUS" => "ACTIVE"
        ]);

        if (!$createdDepartment) {
            return $res->status(400)->redirect("/department/create", ["error" => "Creating department went wrong"]);
        }

        return $res->status(200)->redirect("/department/create", ["success" => "Department created successfully"]);
    }

    /**
     * Update user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function updateDepartment(Request $req, Response $res)
    {
        $department_id = $req->query["id"];
        $credentials = $req->body;

        $department_model = new Model("DEPARTMENT");
        $department_credentials = $department_model->findOne(["ID" => $department_id]);

        if (!$department_credentials) {
            return $res->status(400)->redirect("/department/update?id=" . $department_id, ["error" => "Update department went wrong"]);
        }

        $updated_course_credentials = $department_model->updateOne([
            "NAME" => $credentials["NAME"],
            "COLOR" => $credentials["COLOR"],

        ], ["ID" => $department_id]);

        if (!$updated_course_credentials) {
            return $res->status(400)->redirect("/department/update?id=" . $department_id, ["error" => "Update department went wrong"]);
        }

        $res->status(200)->redirect("/department/update?id=" . $department_id, ["success" => "Update Successfully"]);
    }

    /**
     * Delete user controller
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function deleteDepartment(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $departmentModel = new Model("DEPARTMENT");

        $existDepartment = $departmentModel->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$existDepartment) {
            return $res->status(400)->redirect("/department", ["error" => "Department doesn't exist"]);
        }


        // delete the department
        $deletedDepartment = $departmentModel->deleteOne(["ID" => $UID]);
        if (!$deletedDepartment) {
            return $res->status(400)->redirect("/department", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/department", ["success" => "Deleted Successfully"]);


    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}