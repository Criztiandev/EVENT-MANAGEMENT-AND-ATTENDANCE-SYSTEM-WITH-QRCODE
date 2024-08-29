<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class CourseController
{

    private const BASE_URL = "views/admin/course";
    private const BASE_MODEL = "COURSE";
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
            $courseModel = self::getBaseModel();
            $courses = $courseModel->find();

            $transformed_course = array_map(function ($items) {
                $departmentModel = new Model("DEPARTMENT");
                $departmentName = $departmentModel->findOne(["ID" => $items["DEPARTMENT_ID"], "STATUS" => "ACTIVE"], ["select" => "NAME"]);

                return [
                    "ID" => $items["ID"],
                    "NAME" => $items["NAME"],
                    "DEPARTMENT" => $departmentName["NAME"],
                    "STATUS" => $items["STATUS"]
                ];
            }, $courses);
            
            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["courses" => $transformed_course]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Courses: " . $e->getMessage()]);
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
        $credentials = $departmentModel->find([]);

        $res->status(200)->render(self::BASE_URL . "/pages/create.page.php", ["roles" => self::ROLES, "department" => $credentials]);
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
            $courseID = $req->query["id"];
            $courseModel = new Model("COURSE");
            $departmentModel = new Model("DEPARTMENT");
            $credentials = $courseModel->findOne(["ID" => $courseID]);

            $department_list = $departmentModel->find([]);
            if (!$credentials) {
                $res->status(400)->redirect("/users/update?id=" . $courseID, ["error" => "Course doesn't exist"]);
            }


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $courseID,
                    "details" => $credentials,
                    "department" => $department_list,
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
            $res->status(500)->json(["error" => "Failed to fetch Courses: " . $e->getMessage()]);
        }
    }


    // ================================= Actions ======================================================

    /**
     * Create users Handler
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function createCourse(Request $req, Response $res)
    {
        $credentials = $req->body;
        $courseModel = new Model("COURSE");

        $existingCourse = $courseModel->findOne(["#and" => ["NAME" => $credentials["NAME"], "DEPARTMENT_ID" => $credentials["DEPARTMENT"]]]);

        if ($existingCourse) {
            return $res->status(400)->redirect("/course/create", ["error" => "Course already exists"]);
        }

        $UID = Uuid::uuid4()->toString();
        $createdCourse = $courseModel->createOne([
            "ID" => $UID,
            "NAME" => $credentials["NAME"],
            "DEPARTMENT_ID" => $credentials["DEPARTMENT"],
            "STATUS" => "ACTIVE"
        ]);

        if (!$createdCourse) {
            return $res->status(400)->redirect("/course/create", ["error" => "Creating course went wrong"]);
        }

        return $res->status(200)->redirect("/course/create", ["success" => "Course created successfully"]);
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
    public static function deleteCourse(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $courseModel = new Model("COURSE");

        $existCourse = $courseModel->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$existCourse) {
            return $res->status(400)->redirect("/course", ["error" => "Course doesn't exist"]);
        }


        // delete the course
        $deletedCourse = $courseModel->deleteOne(["ID" => $UID]);
        if (!$deletedCourse) {
            return $res->status(400)->redirect("/course", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/course", ["success" => "Deleted Successfully"]);


    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}