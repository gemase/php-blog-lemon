<?php
/**
 * Es requerido tener una sesión, por lo que
 * si no existe una, la iniciamos.
 */
if (!isset($_SESSION)) session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

$_Dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__));
$_Dotenv->load();

require_once 'Config/config.php';

use App\Libraries\Request;
use App\Libraries\Core;

$_RequestUrl = Request::has('get') ? Request::load('get') : null;
$_Core = new Core($_RequestUrl);