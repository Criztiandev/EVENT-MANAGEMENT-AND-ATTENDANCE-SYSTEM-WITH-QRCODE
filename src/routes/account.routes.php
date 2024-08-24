<?php
use controller\general\AccountController;
use lib\Router\classes\Request;
use lib\Router\classes\Response;
use lib\Router\Express;


$router = Express::Router();

$router->delete("/account/logout", fn(Request $req, Response $res) => AccountController::logout($req, $res));
