<?php

namespace controller\admin;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Exception;
use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class EventController
{

    private const BASE_URL = "views/admin/event";
    private const BASE_MODEL = "EVENT";
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
            $eventModel = self::getBaseModel();
            $events = $eventModel->find();

            $transformed_event = array_map(function ($event) {
                $departmentModel = new Model("DEPARTMENT");
                $courseModel = new Model("COURSE");

                $departmentName = $departmentModel->findOne(["ID" => $event["DEPARTMENT_ID"]], ["select" => "NAME"]);
                $courseName = $courseModel->findOne(["ID" => $event["COURSE_ID"]], ["select" => "NAME"]);


                return [
                    ...$event,
                    "DEPARTMENT" => $departmentName,
                    "COURSE" => $courseName,
                ];

            }, $events);


            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["events" => array_reverse($transformed_event)]);

        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Events: " . $e->getMessage()]);
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


    /**
     * Display the Update Page
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function renderUpdatePage(Request $req, Response $res)
    {
        try {
            $eventID = $req->query["id"];
            $eventModel = new Model("EVENT");

            $credentials = $eventModel->findOne(["ID" => $eventID]);


            if (!$credentials) {
                $res->status(400)->redirect("/users/update?id=" . $eventID, ["error" => "Event doesn't exist"]);
            }


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $eventID,
                    "details" => $credentials,
                    "roles" => self::ROLES
                ]
            );



        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch users: " . $e->getMessage()]);
        }
    }


    public static function renderSession(Request $req, Response $res)
    {
        try {
            $eventModel = new Model("EVENT");
            $attendanceModel = new Model("Attendance");
            $event_id = $req->query["id"];

            $event_credentials = $eventModel->findOne(["ID" => $event_id]);

            // get all the attendance related
            $attendance_related = $attendanceModel->find(["EVENT_ID" => $event_id]);

            $transformed_attendees_list = array_map(function ($items) {
                $studentModel = new Model("STUDENT");
                $accountModel = new Model("USERS");

                $studentCredentials = $studentModel->findOne(["STUDENT_ID" => $items["STUDENT_ID"]]);
                $accountCredentials = $accountModel->findOne(["ID" => $studentCredentials["USER_ID"]], ["select" => "FIRST_NAME, LAST_NAME"]);


                return [
                    ...$items,
                    "STUDENT_NAME" => $accountCredentials["FIRST_NAME"] . " " . $accountCredentials["LAST_NAME"],
                ];


            }, $attendance_related);

            $routes = [
                "ACTIVE" => "/pages/session-preparation.php",
                "START" => "/pages/session-start.php"
            ];

            $res->status(200)->render(self::BASE_URL . $routes[$event_credentials["STATUS"]], ["EVENT_ID" => $event_id, "student_list" => $transformed_attendees_list]);
        } catch (\Exception $e) {
            $res->status(500)->json(["error" => "Failed to fetch Events: " . $e->getMessage()]);
        }
    }


    // ================================= Actions ======================================================

    /**
     * Create users Handler
     * @param \lib\Router\classes\Request $req
     * @param \lib\Router\classes\Response $res
     * @return void
     */
    public static function createEvent(Request $req, Response $res)
    {
        $credentials = $req->body;
        $eventModel = new Model("EVENT");

        $existingEvent = $eventModel->findOne(["NAME" => $credentials["NAME"]]);

        if ($existingEvent) {
            return $res->status(400)->redirect("/event/create", ["error" => "Event already exists"]);
        }

        // Check if the dates are in the past or exceed 7 days in the future
        $currentDate = new \DateTime();
        $startDate = new \DateTime($credentials["START_DATE"]);
        $endDate = new \DateTime($credentials["END_DATE"]);
        $maxFutureDate = (new \DateTime())->modify('+7 days');

        if ($startDate < $currentDate || $endDate < $currentDate) {
            return $res->status(400)->redirect("/event/create", ["error" => "Event dates cannot be in the past"]);
        }

        if ($startDate > $maxFutureDate || $endDate > $maxFutureDate) {
            return $res->status(400)->redirect("/event/create", ["error" => "Event dates cannot exceed 7 days in the future"]);
        }

        $UID = Uuid::uuid4()->toString();
        $createdEvent = $eventModel->createOne([
            "ID" => $UID,
            ...$credentials,
            "STATUS" => "ACTIVE"
        ]);

        if (!$createdEvent) {
            return $res->status(400)->redirect("/event/create", ["error" => "Creating event went wrong"]);
        }

        return $res->status(200)->redirect("/event/create", ["success" => "Event created successfully"]);
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
    public static function deleteEvent(Request $req, Response $res)
    {
        $UID = $req->query["id"];
        $eventModel = new Model("EVENT");

        $existEvent = $eventModel->findOne(["ID" => $UID], ["select" => "ID"]);
        if (!$existEvent) {
            return $res->status(400)->redirect("/event", ["error" => "Event doesn't exist"]);
        }


        // delete the event
        $deletedEvent = $eventModel->deleteOne(["ID" => $UID]);
        if (!$deletedEvent) {
            return $res->status(400)->redirect("/event", ["error" => "Deletion Failed"]);
        }

        $res->status(200)->redirect("/event", ["success" => "Deleted Successfully"]);


    }


    public static function startEvent(Request $req, Response $res)
    {
        $eventModel = new Model("EVENT");
        $EVENT_ID = $req->body["EVENT_ID"];

        $event_credentials = $eventModel->findOne(["ID" => $EVENT_ID]);
        if (!$event_credentials) {
            $res->status(400)->redirect("/event/session-start?id=" . $EVENT_ID, ["error" => "Event doesn't exist"]);
        }

        // Update the session status to start
        $started_session = $eventModel->updateOne(["STATUS" => "START"], ["ID" => $EVENT_ID], );
        if (!$started_session) {
            $res->status(400)->redirect("/event/session-start?id=" . $EVENT_ID, ["error" => "Event doesn't update"]);
        }

        $res->status(200)->redirect("/event/session-start?id=" . $EVENT_ID, ["success" => "Event Started"]);

    }


    public static function endEvent(Request $req, Response $res)
    {
        $eventModel = new Model("EVENT");
        $EVENT_ID = $req->body["EVENT_ID"];

        $event_credentials = $eventModel->findOne(["ID" => $EVENT_ID]);
        if (!$event_credentials) {
            $res->status(400)->redirect("/event/session-start?id=" . $EVENT_ID, ["error" => "Event doesn't exist"]);
        }

        // Update the session status to start
        $started_session = $eventModel->updateOne(["STATUS" => "END"], ["ID" => $EVENT_ID], );
        if (!$started_session) {
            $res->status(400)->redirect("/event/session-start?id=" . $EVENT_ID, ["error" => "Event doesn't update"]);
        }
        $res->status(200)->redirect("/event", ["success" => "Event Ended"]);

    }


    public static function printEvent(Request $req, Response $res)
    {
        $EVENT_ID = $req->query["id"];
        $eventModel = new Model("EVENT");


        $event_credentials = $eventModel->findOne(["ID" => $EVENT_ID, "STATUS" => "END"]);
        if (!$event_credentials) {
            $res->status(400)->redirect("/event/session-start?id=" . $EVENT_ID, ["error" => "Event doesn't exist"]);
        }
        dd($event_credentials);

    }

    public static function joinEvent(Request $req, Response $res)
    {
        $EVENT_ID = $req->query["id"];
        $studentModel = new Model("STUDENT");
        $eventModel = new Model("EVENT");
        $UID = Express::Session()->get("UID");

        $event_credentials = $eventModel->findOne(["ID" => $EVENT_ID, "STATUS" => "ACTIVE"]);
        if (!$event_credentials) {
            return $res->status(400)->redirect("/" . $EVENT_ID, ["error" => "Event doesn't exist"]);
        }

        $credentials = $studentModel->findOne(["USER_ID" => $UID]);
        if (!$credentials) {
            return $res->status(400)->redirect("/", ["error" => "Student doesnt exist"]);
        }

        // Generate a unique join link
        $joinLink = "https://yourdomain.com/join-event/" . $EVENT_ID . "?user=" . $UID;

        $writer = new SvgWriter();

        // Generate QR code
        $qrCode = QrCode::create($credentials["STUDENT_ID"])
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $label = Label::create('Scan to join event')
            ->setTextColor(new Color(255, 0, 0));

        $result = $writer->write($qrCode, null, $label);

        // Prepare directory and save the SVG content to a file
        $directory = __DIR__ . "/../../assets/qr_codes/events";
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!is_writable($directory)) {
            throw new Exception("Directory is not writable: " . $directory);
        }

        $qrCodePath = $directory . "/" . $EVENT_ID . "_user_" . $UID . ".svg";
        error_log("Attempting to save QR code to: " . $qrCodePath);


        $svgContent = $result->getString();
        if (file_put_contents($qrCodePath, $svgContent) === false) {
            throw new Exception("Failed to write QR code to file: " . $qrCodePath);
        }


        $res->status(200)->redirect("/event/download?id=" . $EVENT_ID . "&UID=" . $UID, [
            "message" => "Event joined successfully",
            "qr_code_path" => $qrCodePath,
            "join_link" => $joinLink
        ]);
    }

    public static function downloadQrCode(Request $req, Response $res)
    {
        $EVENT_ID = $req->query["id"];
        $UID = Express::Session()->get("UID");


        $directory = __DIR__ . "/../../assets/qr_codes/events";
        $qrCodePath = $directory . "/" . $EVENT_ID . "_user_" . $UID . ".svg";

        if (file_exists($qrCodePath)) {
            $fileName = "event_" . $EVENT_ID . "_qr_code.svg";

            $res->header("Content-Type", "image/svg+xml");
            $res->header("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
            $res->header("Content-Length", filesize($qrCodePath));

            readfile($qrCodePath);
            exit;
        } else {
            $res->status(200)->redirect("/event", ["error" => "Something went wrong"]);
        }
    }





    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}