<?php
use controller\admin\EventController;
use controller\user\UserController;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;


$router = Express::Router();


$router->get("/", fn(Request $req, Response $res) => UserController::renderDashboard($req, $res));

$router->get("/event/join", fn(Request $req, Response $res) => EventController::joinEvent($req, $res));
$router->get("/event/download", fn(Request $req, Response $res) => EventController::downloadQrCode($req, $res));