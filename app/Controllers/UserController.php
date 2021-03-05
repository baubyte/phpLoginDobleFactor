<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class UserController
{

    /**
     * Método para dar de Alta un Usuario
     *
     * @param [string] $name
     * @param [string] $email
     * @param [string] $password
     * @return id del usuario
     */
    public function register($name, $email, $password)
    {
        $id = (new User())->createUser($name, $email, $password);
        return $id;
    }
}
