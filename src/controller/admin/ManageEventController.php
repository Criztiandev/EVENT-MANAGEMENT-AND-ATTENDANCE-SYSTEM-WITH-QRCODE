<?php

namespace controller\admin;

use lib\Mangoose\Model;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class ManageEventController
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
            $res->status(200)->render(self::BASE_URL . "/screen.view.php", ["events" => $events]);

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
            "STATUS" => "INACTIVE"
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


    protected static function userExist($email, $phone_number)
    {
        return self::getBaseModel()->findOne([
            "#or" => ["EMAIL" => $email, "PHONE_NUMBER" => $phone_number]
        ], ["select" => "ID"]);
    }
}