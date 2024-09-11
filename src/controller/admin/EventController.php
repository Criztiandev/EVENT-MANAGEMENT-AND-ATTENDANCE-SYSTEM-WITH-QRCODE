<?php

namespace controller\admin;

use DateTime;
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
            $departmentModel = new Model("DEPARTMENT");
            $courseModel = new Model("COURSE");

            $department_list = $departmentModel->find([]);
            $course_list = $courseModel->find([]);


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


            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["department_list" => $department_list, "course_list" => $course_list, "events" => array_reverse($transformed_event)]);

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
        $organizationModel = new Model("ORGANIZATION");

        $departmentCredentials = $departmentModel->find([]);
        $courseCredentials = $courseModel->find([]);
        $organization_list = $organizationModel->find([]);


        $res->status(200)->render(self::BASE_URL . "/pages/create.page.php", ["departmentList" => $departmentCredentials, "courseList" => $courseCredentials, "organization_list" => $organization_list, "roles" => self::ROLES]);
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
            $organizationModel = new Model("ORGANIZATION");
            $departmentModel = new Model("DEPARTMENT");
            $courseModel = new Model("COURSE");
            $credentials = $eventModel->findOne(["ID" => $eventID]);

            $organization_list = $organizationModel->find([]);
            $department_list = $departmentModel->find([]);
            $course_list = $courseModel->find([]);

            if (!$credentials) {
                $res->status(400)->redirect("/users/update?id=" . $eventID, ["error" => "Event doesn't exist"]);
            }


            $res->status(200)->render(
                self::BASE_URL . "/pages/update.page.php",
                [
                    "UID" => $eventID,
                    "details" => $credentials,
                    "organization_list" => $organization_list,
                    "department_list" => $department_list,
                    "course_list" => $course_list,
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
    public static function updateEvent(Request $req, Response $res)
    {
        $credentials = $req->body;
        $event_id = $req->query["id"];
        $eventModel = new Model("EVENT");

        $event_credentials = $eventModel->findOne(["ID" => $event_id]);
        if (!$event_credentials) {
            $res->status(400)->redirect("/event/update?id=" . $event_id, ["error" => "Event doesn't exist"]);
        }


        unset($credentials["_method"]);
        $update_event = $eventModel->updateOne([...$credentials], ["ID" => $event_id]);
        if (!$update_event) {
            $res->status(400)->redirect("/event/update?id=" . $event_id, ["error" => "Event doesn't exist"]);
        }


        $res->status(200)->redirect("/event/update?id=" . $event_id, ["success" => "Update Successful"]);
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
        $attendanceModel = new Model("ATTENDANCE");
        $departmentModel = new Model("DEPARTMENT");
        $courseModel = new Model("COURSE");
        $organizationModel = new Model("ORGANIZATION");


        $event_credentials = $eventModel->findOne(["ID" => $EVENT_ID, "STATUS" => "END"]);
        if (!$event_credentials) {
            $res->status(400)->redirect("/event/session-start?id=" . $EVENT_ID, ["error" => "Event doesn't exist"]);
        }


        $event_department_name = $departmentModel->findOne(["ID" => $event_credentials["DEPARTMENT_ID"]], ["select" => "NAME"]);
        $event_course_name = $courseModel->findOne(["ID" => $event_credentials["COURSE_ID"]], ["select" => "NAME"]);
        $event_organization_name = $organizationModel->findOne(["ID" => $event_credentials["ORGANIZATION_ID"]], ["select" => "NAME"]);



        // get all attendance related to this event
        $attendees_list = $attendanceModel->find([]);

        $filtered_attendees_list = array_filter($attendees_list, function ($attendee) use ($EVENT_ID) {
            return $EVENT_ID === $attendee["EVENT_ID"];
        });

        $transformed_attendees_list = array_map(function ($attendee) use ($event_credentials) {
            $studentModel = new Model("STUDENT");
            $accountModel = new Model("USERS");
            $courseModel = new Model("COURSE");
            $departmentModel = new Model("DEPARTMENT");


            $student_credentials = $studentModel->findOne(["STUDENT_ID" => $attendee["STUDENT_ID"]]);
            $account_credentials = $accountModel->findOne(["ID" => $student_credentials["USER_ID"]]);
            $course_credentials = $courseModel->findOne(["ID" => $student_credentials["COURSE_ID"]]);
            $department_credentials = $departmentModel->findOne(["ID" => $student_credentials["DEPARTMENT_ID"]]);



            return [
                ...$attendee,
                "STUDENT_ID" => $attendee["STUDENT_ID"],
                "FULLNAME" => $account_credentials["FIRST_NAME"] . "" . $account_credentials["LAST_NAME"],
                "COURSE_NAME" => $course_credentials["NAME"],
                "DEPARTMENT_NAME" => $department_credentials["NAME"],
                "YEAR_LEVEL" => $student_credentials["YEAR_LEVEL"],
                "CHECK_IN_TIME_AM" => $attendee["CHECK_IN_TIME_AM"],
                "CHECK_OUT_TIME_AM" => $attendee["CHECK_OUT_TIME_AM"],
                "CHECK_IN_TIME_PM" => $attendee["CHECK_IN_TIME_PM"],
                "CHECK_OUT_TIME_PM" => $attendee["CHECK_OUT_TIME_PM"],
            ];

        }, $filtered_attendees_list);


        $updated_event_details = [
            ...$event_credentials,
            "DEPARTMENT_NAME" => $event_department_name["NAME"],
            "COURSE_NAME" => $event_course_name["NAME"],
            "ORGANIZATION_NAME" => $event_organization_name["NAME"],
        ];



        $res->status(200)->render(self::BASE_URL . "/pages/print.attendance.php", ["details" => $updated_event_details, "attendees_list" => $transformed_attendees_list]);
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


        $res->status(200)->redirect("/event/qr-code?id=" . $EVENT_ID . "&UID=" . $UID . "&action=display", [
            "message" => "Event joined successfully",
            "qr_code_path" => $qrCodePath,
            "join_link" => $joinLink
        ]);
    }

    public static function handleQrCode(Request $req, Response $res)
    {
        $EVENT_ID = $req->query["id"];
        $UID = $req->query["UID"];
        $action = $req->query["action"] ?? 'display'; // Default to display if not specified

        $eventModel = new Model("EVENT");
        $organizationModel = new Model("ORGANIZATION");

        $event_credentials = $eventModel->findOne(["ID" => $EVENT_ID]);

        if (!$event_credentials) {
            $res->status(404)->redirect("/", ["error" => "Event doesnt exist"]);
        }

        $organization_credentials = $organizationModel->findOne(["ID" => $event_credentials["ORGANIZATION_ID"]]);

        if (!$organization_credentials) {
            $res->status(404)->redirect("/", ["error" => "Organization doesnt exist"]);
        }

        // make sure the current date is not passed withe event_credentials[END_DATE] which is 2024-08-31
        $currentDate = new DateTime();
        $endDate = new DateTime($event_credentials["END_DATE"]);


        if ($currentDate > $endDate) {
            $res->status(400)->render("views/error.page.php", ["error" => "Event has ended. QR code is no longer available."]);
            return;
        }

        $directory = __DIR__ . "/../../assets/qr_codes/events";
        $qrCodePath = $directory . "/" . $EVENT_ID . "_user_" . $UID . ".svg";

        if (file_exists($qrCodePath)) {
            $svgContent = file_get_contents($qrCodePath);

            if ($action === 'download') {
                $res->header("Content-Type", "image/svg+xml");
                $res->header("Content-Disposition", "attachment; filename=\"qr_code.svg\"");
                $res->header("Content-Length", strlen($svgContent));
                echo $svgContent;
                exit;
            } else {
                // Display action
                $res->status(200)->render(
                    "views/user/pages/download.page.php",
                    [
                        "eventName" => $event_credentials["NAME"],
                        "organization" => $organization_credentials["NAME"],
                        "message" => "Event joined successfully",
                        "qr_code_content" => $svgContent,
                        "download_url" => "/event/qr-code?id=" . $EVENT_ID . "&UID=" . $UID . "&action=download"
                    ]
                );
            }
        } else {
            $res->status(404)->render("views/error.page.php", ["error" => "QR code not found"]);
        }
    }





    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}