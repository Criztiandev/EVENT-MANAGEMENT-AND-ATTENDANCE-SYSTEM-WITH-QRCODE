<?php
use controller\operator\OperatorEventController;
use controller\operator\OperatorRoleController;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;

$router = Express::Router();

$router->get("/", fn(Request $req, Response $res) => OperatorRoleController::renderDashboard($req, $res));
$router->get("/dashboard", fn(Request $req, Response $res) => OperatorRoleController::renderDashboard($req, $res));


// Events
$router->get("/event", fn(Request $req, Response $res) => OperatorEventController::renderScreen($req, $res));
$router->get("/event/create", fn(Request $req, Response $res) => OperatorEventController::renderCreatePage($req, $res));
$router->get("/event/update", fn(Request $req, Response $res) => OperatorEventController::renderUpdatePage($req, $res));

$router->get("/event/session-start", fn(Request $req, Response $res) => OperatorEventController::renderSession($req, $res));

// Event Actions
$router->post("/event/create", fn(Request $req, Response $res) => OperatorEventController::createEvent($req, $res));
$router->delete("/event/delete", fn(Request $req, Response $res) => OperatorEventController::deleteEvent($req, $res));

$router->post("/event/session-start", fn(Request $req, Response $res) => OperatorEventController::startEvent($req, $res));
$router->post("/event/session-end", fn(Request $req, Response $res) => OperatorEventController::endEvent($req, $res));
$router->get("/event/session-print", fn(Request $req, Response $res) => OperatorEventController::printEvent($req, $res));
