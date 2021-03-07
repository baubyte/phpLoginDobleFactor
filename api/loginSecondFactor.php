<?php

session_start();

require('../vendor/autoload.php');

use App\Controllers\UserController;

/**Recibimos todos los I/O */
$data = file_get_contents("php://input");

$data = json_decode($data, true);

$userController = new UserController();

/**Validamos el código */
$res = $userController->validateCode($data['code']);

/**Comprobamos la repuesta */
if (!$res) {
    http_response_code(400);
    echo "Código incorrecto";
} else {    
    http_response_code(200);
}