<?php
//Configuración de base de datos
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);

//Dirección de archivos
define('DR_ARCHIVO', $_ENV['DR_ARCHIVO']);

//Url
define('APPROOT', dirname(dirname(__FILE__)));

//Url raíz
define('URLROOT', $_ENV['URLROOT']);

//Versión
define('APPVERSION', '1.0.0');