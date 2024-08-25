<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class AttendanceController
{

    private const BASE_URL = "views/admin/attendance";
    private const BASE_MODEL = "ATTENDANCE";
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
            $attendanceModel = self::getBaseModel();
            $attendances = $attendanceModel->find();
            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["attendances" => $attendances]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Attendances: " . $e->getMessage()]);
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
            $attendanceID = $req->query["id"];
            $attendanceModel = new Model("ATTENDANCE");

            $credentials = $attendanceModel->findOne(["ID" => $attendanceID]);


            if (!$credentials) {
                $res->status(400)->redirect("/users/update?id=" . $attendanceID, ["error" => "Attendance doesn't exist"]);
            }


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $attendanceID,
                    "details" => $credentials,
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
    public static function createAttendance(Request $req, Response $res)
    {
        $credentials = $req->body;
        $attendanceModel = new Model("ATTENDANCE");

        $existingAttendance = $attendanceModel->findOne(["NAME" => $credentials["NAME"]]);

        if ($existingAttendance) {
            return $res->status(400)->redirect("/attendance/create", ["error" => "Attendance already exists"]);
        }

        // Check if the dates are in the past or exceed 7 days in the future
        $currentDate = new \DateTime();
        $startDate = new \DateTime($credentials["START_DATE"]);
        $endDate = new \DateTime($credentials["END_DATE"]);
        $maxFutureDate = (new \DateTime())->modify('+7 days');

        if ($startDate < $currentDate || $endDate < $currentDate) {
            return $res->status(400)->redirect("/attendance/create", ["error" => "Attendance dates cannot be in the past"]);
        }

        if ($startDate > $maxFutureDate || $endDate > $maxFutureDate) {
            return $res->status(400)->redirect("/attendance/create", ["error" => "Attendance dates cannot exceed 7 days in the future"]);
        }

        $UID = Uuid::uuid4()->toString();
        $createdAttendance = $attendanceModel->createOne([
            "ID" => $UID,
            ...$credentials,
            "STATUS" => "INACTIVE"
        ]);

        if (!$createdAttendance) {
            return $res->status(400)->redirect("/attendance/create", ["error" => "Creating attendance went wrong"]);
        }

        return $res->status(200)->redirect("/attendance/create", ["success" => "Attendance created successfully"]);
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
    public static function deleteAttendance(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $attendanceModel = new Model("ATTENDANCE");

        $existAttendance = $attendanceModel->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$existAttendance) {
            return $res->status(400)->redirect("/attendance", ["error" => "Attendance doesn't exist"]);
        }


        // delete the attendance
        $deletedAttendance = $attendanceModel->deleteOne(["ID" => $UID]);
        if (!$deletedAttendance) {
            return $res->status(400)->redirect("/attendance", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/attendance", ["success" => "Deleted Successfully"]);


    }


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}