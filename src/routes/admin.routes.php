<?php
use controller\admin\AdminController;
use controller\admin\AttendanceController;
use controller\admin\CourseController;
use controller\admin\DepartmentController;
use controller\admin\EventController;
use controller\admin\OperatorController;
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

$router->get("/event/session-start", fn(Request $req, Response $res) => EventController::renderSessiion($req, $res));

// Event Actions
$router->post("/event/create", fn(Request $req, Response $res) => EventController::createEvent($req, $res));
$router->delete("/event/delete", fn(Request $req, Response $res) => EventController::deleteEvent($req, $res));

// Attendance
$router->get("/attendance", fn(Request $req, Response $res) => AttendanceController::renderScreen($req, $res));
$router->get("/attendance/create", fn(Request $req, Response $res) => AttendanceController::renderCreatePage($req, $res));
$router->get("/attendance/update", fn(Request $req, Response $res) => AttendanceController::renderUpdatePage($req, $res));


// Student
$router->get("/student", fn(Request $req, Response $res) => StudentController::renderScreen($req, $res));
$router->get("/student/create", fn(Request $req, Response $res) => StudentController::renderCreatePage($req, $res));
$router->get("/student/update", fn(Request $req, Response $res) => StudentController::renderUpdatePage($req, $res));

// Student Actions
$router->post("/student/create", fn(Request $req, Response $res) => StudentController::createStudent($req, $res));
$router->delete("/student/delete", fn(Request $req, Response $res) => StudentController::deleteStudent($req, $res));

// operator
$router->get("/operator", fn(Request $req, Response $res) => OperatorController::renderScreen($req, $res));
$router->get("/operator/create", fn(Request $req, Response $res) => OperatorController::renderCreatePage($req, $res));
$router->get("/operator/update", fn(Request $req, Response $res) => OperatorController::renderUpdatePage($req, $res));
$router->get("/operator/delete", fn(Request $req, Response $res) => OperatorController::renderUpdatePage($req, $res));


// operator Actions
$router->post("/operator/create", fn(Request $req, Response $res) => OperatorController::createOperator($req, $res));
$router->delete("/operator/delete", fn(Request $req, Response $res) => OperatorController::deleteOperator($req, $res));








// department
$router->get("/department", fn(Request $req, Response $res) => DepartmentController::renderScreen($req, $res));
$router->get("/department/create", fn(Request $req, Response $res) => DepartmentController::renderCreatePage($req, $res));
$router->get("/department/update", fn(Request $req, Response $res) => DepartmentController::renderUpdatePage($req, $res));


// Department Actions
$router->post("/department/create", fn(Request $req, Response $res) => DepartmentController::createDepartment($req, $res));
$router->delete("/department/delete", fn(Request $req, Response $res) => DepartmentController::deleteDepartment($req, $res));





// department
$router->get("/course", fn(Request $req, Response $res) => CourseController::renderScreen($req, $res));
$router->get("/course/create", fn(Request $req, Response $res) => CourseController::renderCreatePage($req, $res));
$router->get("/course/update", fn(Request $req, Response $res) => CourseController::renderUpdatePage($req, $res));


// Course Actions
$router->post("/course/create", fn(Request $req, Response $res) => CourseController::createCourse($req, $res));
$router->delete("/course/delete", fn(Request $req, Response $res) => CourseController::deleteCourse($req, $res));