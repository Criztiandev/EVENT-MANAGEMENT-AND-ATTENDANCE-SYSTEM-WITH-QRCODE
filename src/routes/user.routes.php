<?php
use controller\user\UserController;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;


$router = Express::Router();


$router->get("/", fn(Request $req, Response $res) => UserController::renderDashboard($req, $res));

