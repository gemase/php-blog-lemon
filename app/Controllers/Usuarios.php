<?php
class Usuarios {
    public function registro() {
        require_once APPROOT . '/Views/Usuarios/registro.php';
    }

    public function login() {
        require_once APPROOT . '/Views/Usuarios/login.php';
    }
}