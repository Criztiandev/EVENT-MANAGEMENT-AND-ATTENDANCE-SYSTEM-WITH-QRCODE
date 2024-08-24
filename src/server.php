<?php
use lib\Mangoose\Mangoose;
use lib\Router\Express;
use config\Credentials;


$app = Express::getInstance();
$db = Mangoose::connect($_ENV['DEV_ENV'] ? Credentials::getDevelopmentDSN() : Credentials::getProductionDSN());

require from("routes/index.php");

$fullPath = BASE_PATH . ENTRY_POINT . "/views/+notfound.view.php";
$app->listen(["error" => $fullPath]);

Express::Session()->flushFlash();