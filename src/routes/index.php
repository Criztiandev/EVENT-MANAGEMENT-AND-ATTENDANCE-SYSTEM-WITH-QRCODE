<?php
use lib\Router\Express;


$routes = [
    "operator" => 'routes/user.routes.php',
    "student" => 'routes/user.routes.php',
    "admin" => 'routes/admin.routes.php',
    'auth' => 'routes/auth.routes.php'
];

$UID = Express::Session()->get("UID");
$credentials = Express::Session()->get("credentials");

try {
    if ($UID && isset($routes[$credentials["role"]])) {
        require from($routes[$credentials["role"]]);
        require from("routes/account.routes.php");
    } else {
        require from("routes/auth.routes.php");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "An error occurred. Please try again later.";
    die();
}