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
     * @param [string] $email
     * @param [string] $password
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

        /**Vemos si el usuario tiene activo el segundo factor y retornamos true pero no creamos la sesión  */
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
     * @param [int] $id
     * @param [string] $email
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
    /**
     * Permite obtener los datos del usuario logueado
     *
     * @return [array] Datos del Usuario
     */
    public function getUser() {
        $email = $_SESSION['email'];
        return (new User())->getUser($email);
    }   
    /**
     * Activa el Segundo Factor de Utenticacion
     *
     * @param [string] $secret
     * @param [int] $code
     * @return boolean
     */
    public function activateSecondFactor($secret, $code) {
        /**Comprobamos si el código es correcto */
        if ($this->checkGoogleAuthenticatorCode($secret, $code)) {
            /**Obtenemos el id y agregamos el código secreto */
            $id = $_SESSION['userId'];
            (new User())->createSecret($secret, $id);
            return true;
        }
        return false;
    }
    /**
     * Elimina el doble factor de autenticacion
     *
     * @return void
     */
    public function deactivateSecondFactor() {
        $id = $_SESSION['userId'];
        (new User())->deleteSecret($id);
    }
    /**
     * Comprueba si el código del bole factor es correcto
     *
     * @param [string] $secret
     * @param [int] $code
     * @return boolean
     */
    public function checkGoogleAuthenticatorCode($secret, $code) {
        $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        if ($g->checkCode($secret, $code)) {
            return true;
        }
        return false;
    }
    /**
     * Valida el código del doble factor de autenticacion
     *
     * @param [int] $code
     * @return boolean
     */
    public function validateCode($code) {
        /**Obtenemos el usuario logueado */
        $user = $this->getUser();
        /**Validamos el código */
        if ($this->checkGoogleAuthenticatorCode($user['two_factor_key'], $code)) {
            /**Creamos la sesión */
            $this->createSession($user['id'], $user['email']);
            return true;
        }

        return false;
    }
    
}
