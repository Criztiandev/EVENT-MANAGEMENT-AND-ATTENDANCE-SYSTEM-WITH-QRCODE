<?php
use controller\auth\AuthController;
use lib\Router\Express;

$router = Express::Router();

$router->get("/", fn($req, $res) => AuthController::loginScreen($req, $res));
$router->get("/login", fn($req, $res) => AuthController::loginScreen($req, $res));
$router->post("/auth/login", fn($req, $res) => AuthController::authenticateUser($req, $res));

$router->get("/register", fn($req, $res) => AuthController::registerScreen($req, $res));
$router->post("/auth/register", fn($req, $res) => AuthController::registerStudent($req, $res));



