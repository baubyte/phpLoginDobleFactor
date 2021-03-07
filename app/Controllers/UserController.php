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

    /**
     * Método por el cual un Usuario puede Iniciar Sesión
     *
     * @param [type] $email
     * @param [type] $password
     * @return void
     */
    public function login($email, $password)
    {
        $user = (new User())->getUser($email);

        /**Vemos si el Usuario existe */
        if ($user === null) {
            return ['result' => false];
        }

        /**Comprobamos la contraseña */
        if (!password_verify($password, $user['password'])) {
            return ['result' => false];
        }

        /**Vemos si el usuario tiene activo el segundo factor */
        if ($user['two_factor_key'] !== null) {
            $this->createSession(null, $user['email'], false);
            return ['result' => true, 'secondfactor' => true];
        }

        $this->createSession($user['id'], $user['email']);

        return ['result' => true, 'secondfactor' => false];
    }
    /**
     * Crea las Sesiones al Iniciar sesión
     *
     * @param [type] $id
     * @param [type] $email
     * @param boolean $isLoggedIn
     * @return void
     */
    protected function createSession($id, $email, $isLoggedIn = true)
    {
        $_SESSION['isLoggedIn'] = $isLoggedIn;
        $_SESSION['email'] = $email;
        $_SESSION['userId'] = $id;
    }
    /**
     * Comprueba si el usuario se encuentra logeado
     *
     * @return boolean
     */
    public function isUserLoggedIn()
    {
        return isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'];
    }
    /**
     * Método para cerrar sesión, elimina todas la variables de sesión
     *
     * @return void
     */
    public function logout() {
        try {
            unset($_SESSION['isLoggedIn']);
            unset($_SESSION['email']); 
            unset($_SESSION['userId']);
            \session_destroy();
        }catch (\Exception $ex) {

        }
    }
}
