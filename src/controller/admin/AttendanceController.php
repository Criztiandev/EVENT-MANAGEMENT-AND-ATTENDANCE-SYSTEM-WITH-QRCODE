<?php

namespace controller\admin;

use DateTime;
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
        $studentModel = new Model("STUDENT");
        $attendanceModel = new Model("ATTENDANCE");
        $eventModel = new Model("EVENT");
    
        $STUDENT_ID = $req->body["STUDENT_ID"];
        $EVENT_ID = $req->body["EVENT_ID"];
    
        // Validate student existence
        $existStudent = $studentModel->findOne(["STUDENT_ID" => $STUDENT_ID]);
        if (!$existStudent) {
            $res->status(400);
            echo json_encode([
                'success' => false,
                'message' => 'Student does not exist'
            ]);
            return;
        }
    
        // Validate event existence
        $existEvent = $eventModel->findOne(["ID" => $EVENT_ID]);
        if (!$existEvent) {
            $res->status(400);
            echo json_encode([
                'success' => false,
                'message' => 'Event does not exist'
            ]);
            return;
        }
    
        // Set the timezone to Philippines
        date_default_timezone_set('Asia/Manila');
    
        $currentDateTime = new DateTime();
        $currentTime = $currentDateTime->format('g:i A');
        $isAM = $currentDateTime->format('A') === 'AM';
    
        // Check if attendance record exists for this student and event
        $existingAttendance = $attendanceModel->findOne([
            "STUDENT_ID" => $STUDENT_ID,
            "EVENT_ID" => $EVENT_ID
        ]);
    
        if ($existingAttendance) {
            $updateData = [];
            if ($isAM) {
                if (empty($existingAttendance['CHECK_IN_TIME_AM'])) {
                    $updateData['CHECK_IN_TIME_AM'] = $currentTime;
                } elseif (empty($existingAttendance['CHECK_OUT_TIME_AM'])) {
                    $updateData['CHECK_OUT_TIME_AM'] = $currentTime;
                } else {
                    $res->status(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'AM check-in and check-out already recorded'
                    ]);
                    return;
                }
            } else {
                if (empty($existingAttendance['CHECK_IN_TIME_PM'])) {
                    $updateData['CHECK_IN_TIME_PM'] = $currentTime;
                } elseif (empty($existingAttendance['CHECK_OUT_TIME_PM'])) {
                    $updateData['CHECK_OUT_TIME_PM'] = $currentTime;
                } else {
                    $res->status(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'PM check-in and check-out already recorded'
                    ]);
                    return;
                }
            }
    
            $attendanceResult = $attendanceModel->updateOne(
                $updateData,
                ["ID" => $existingAttendance['ID']]
            );
    
        } else {
            // Create new attendance record
            $attendanceData = [
                'ID' => Uuid::uuid4()->toString(), // Or however you generate unique IDs
                'STUDENT_ID' => $STUDENT_ID,
                'EVENT_ID' => $EVENT_ID
            ];
    
            if ($isAM) {
                $attendanceData['CHECK_IN_TIME_AM'] = $currentTime;
            } else {
                $attendanceData['CHECK_IN_TIME_PM'] = $currentTime;
            }
            $attendanceResult = $attendanceModel->createOne($attendanceData);
        }
        

            dd($attendanceResult);
        if ($attendanceResult) {
            $res->status(200);
            echo json_encode([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'student' => [
                    'STUDENT_ID' => $existStudent['STUDENT_ID'],
                    'STUDENT_NAME' => $existStudent['STUDENT_NAME'],
                ],
                'event' => [
                    'EVENT_ID' => $existEvent['EVENT_ID'],
                    'EVENT_NAME' => $existEvent['EVENT_NAME'],
                ],
                'attendance' => [
                    'TIME' => $currentTime,
                    'PERIOD' => $isAM ? 'AM' : 'PM',
                    'TYPE' => $existingAttendance ?
                        ($isAM ?
                            (empty($existingAttendance['CHECK_OUT_TIME_AM']) ? 'CHECK_OUT' : 'CHECK_IN') :
                            (empty($existingAttendance['CHECK_OUT_TIME_PM']) ? 'CHECK_OUT' : 'CHECK_IN')
                        ) : 'CHECK_IN'
                ]
            ]);
        } else {
            $res->status(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to record attendance'
            ]);
        }
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