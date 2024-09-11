<?php
use controller\admin\EventController;
use controller\user\UserController;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;


$router = Express::Router();


$router->get("/", fn(Request $req, Response $res) => UserController::renderDashboard($req, $res));
$router->get("/profile", fn(Request $req, Response $res) => UserController::renderUpdatePage($req, $res));

$router->get("/event/join", fn(Request $req, Response $res) => EventController::joinEvent($req, $res));
$router->get("/event/qr-code", fn(Request $req, Response $res) => EventController::handleQrCode($req, $res));

$router->put("/profile/update", fn(Request $req, Response $res) => UserController::updateStudent($req, $res));
