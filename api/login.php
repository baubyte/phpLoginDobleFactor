<?php

session_start();

/**Cargamos el autoload */
require('../vendor/autoload.php');

use App\Controllers\UserController;

/**Recibimos todos los datos del formulario por POST */
$inputData = file_get_contents("php://input");

$data = json_decode($inputData, true);

$userController = new UserController();

$res = $userController->login($data['email'], $data['password']);

/**Si el Result es verdadero nos podemos logear, sino mostramos el error */
if ($res['result']) {
    http_response_code(200);
    echo json_encode($res);
} else {
    http_response_code(400);
    echo "No se puede iniciar sesión con esas credenciales";
}
