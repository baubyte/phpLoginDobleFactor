<?php

session_start();

require('../vendor/autoload.php');

use App\Controllers\UserController;

$userController = new UserController();

/**Si el Usuario no esta logueado */
if (!$userController->isUserLoggedIn()) {
    http_response_code(401);
    echo "No existe Autenticacion";
    exit;
}

$userController->deactivateSecondFactor();
