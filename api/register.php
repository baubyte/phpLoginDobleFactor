<?php

session_start();

/**Cargamos el autoload */
require('../vendor/autoload.php');

use App\Controllers\UserController;
/**Recibimos todos los datos del formulario por POST */
$inputData = file_get_contents("php://input");

$data = json_decode($inputData, true);

$userController = new UserController();

$id = $userController->register($data['name'], $data['email'], $data['password']);

/**Si no se pudo crear el usuario retornamos el error sino nos logueamos */
if ($id === 0) {
    http_response_code(400);
    echo "Ya existe un usuario registrado con el email que ingresaste";
} else {
    $userController->login($data['email'], $data['password']);
    http_response_code(200);
    echo $id;
}