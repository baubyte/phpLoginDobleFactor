<?php

session_start();

require('../vendor/autoload.php');

use App\Controllers\UserController;

$userController = new UserController();

if (!$userController->isUserLoggedIn()) {
    http_response_code(401);
    echo "No existe autenticacion";
    exit;
}

$data = file_get_contents("php://input");

$data = json_decode($data, true);

$res = $userController->activateSecondFactor($data['secret'], $data['code']);

if (!$res) {
    http_response_code(400);
    echo "Código Incorrecto";
} else {
    http_response_code(200);
}