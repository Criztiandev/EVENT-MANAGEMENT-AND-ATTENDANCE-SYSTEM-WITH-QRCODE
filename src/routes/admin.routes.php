<?php
use controller\admin\AdminController;
use controller\admin\EventController;
use controller\admin\StudentController;
use controller\admin\ManageUserController;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;

$router = Express::Router();

$router->get("/", fn(Request $req, Response $res) => AdminController::renderDashboard($req, $res));


// Events
$router->get("/event", fn(Request $req, Response $res) => EventController::renderScreen($req, $res));
$router->get("/event/create", fn(Request $req, Response $res) => EventController::renderCreatePage($req, $res));
$router->get("/event/update", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));

// Event Actions
$router->post("/event/create", fn(Request $req, Response $res) => EventController::createEvent($req, $res));
$router->delete("/event/delete", fn(Request $req, Response $res) => EventController::deleteEvent($req, $res));

// Attendance
$router->get("/attendance", fn(Request $req, Response $res) => EventController::renderScreen($req, $res));
$router->get("/attendance/create", fn(Request $req, Response $res) => EventController::renderCreatePage($req, $res));
$router->get("/attendance/update", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));
$router->get("/attendance/delete", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));


// department
$router->get("/department", fn(Request $req, Response $res) => EventController::renderScreen($req, $res));
$router->get("/department/create", fn(Request $req, Response $res) => EventController::renderCreatePage($req, $res));
$router->get("/department/update", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));
$router->get("/department/delete", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));

// course
$router->get("/course", fn(Request $req, Response $res) => EventController::renderScreen($req, $res));
$router->get("/course/create", fn(Request $req, Response $res) => EventController::renderCreatePage($req, $res));
$router->get("/course/update", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));
$router->get("/course/delete", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));


// Student
$router->get("/student", fn(Request $req, Response $res) => StudentController::renderScreen($req, $res));
$router->get("/student/create", fn(Request $req, Response $res) => StudentController::renderCreatePage($req, $res));
$router->get("/student/update", fn(Request $req, Response $res) => StudentController::renderUpdatePage($req, $res));

// Student Actions
$router->post("/student/create", fn(Request $req, Response $res) => StudentController::createStudent($req, $res));
$router->delete("/student/delete", fn(Request $req, Response $res) => StudentController::deleteStudent($req, $res));



// operator
$router->get("/operator", fn(Request $req, Response $res) => EventController::renderScreen($req, $res));
$router->get("/operator/create", fn(Request $req, Response $res) => EventController::renderCreatePage($req, $res));
$router->get("/operator/update", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));
$router->get("/operator/delete", fn(Request $req, Response $res) => EventController::renderUpdatePage($req, $res));



// Users
$router->get("/users", fn(Request $req, Response $res) => ManageUserController::renderScreen($req, $res));
$router->get("/users/create", fn(Request $req, Response $res) => ManageUserController::renderCreatePage($req, $res));
$router->get("/users/update", fn(Request $req, Response $res) => ManageUserController::renderUpdatePage($req, $res));

