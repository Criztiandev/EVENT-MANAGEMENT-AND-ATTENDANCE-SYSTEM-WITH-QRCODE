<?php

use lib\Router\Express;
use Symfony\Component\Dotenv\Dotenv;

session_start();

const BASE_PATH = __DIR__ . "/../";
require BASE_PATH . "vendor/autoload.php";

$app = new Express();
$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '.env');


$app->Root("server.php");
