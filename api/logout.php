<?php

session_start();

require('../vendor/autoload.php');

$userController = new App\Controllers\UserController();

/**Cerramos la sesión y volvemos al login */
$userController->logout();

header('Location: ../login.php');