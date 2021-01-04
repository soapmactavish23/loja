<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';
// require_once 'classes/recaptcha.php';
require_once 'classes/jwt.php';
require_once 'classes/ConnectionDB.php';
require_once 'classes/Rest.php';

echo Rest::open();
?>