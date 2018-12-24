<?php
require_once("config.php");
require_once("functions.php");
require_once("database.php");
require_once("session.php");

define('DS', DIRECTORY_SEPARATOR);
define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'intel-code4good');
define('LIB_PATH', SITE_ROOT.DS.'includes');
define('UPLOAD_PATH', SITE_ROOT.DS.'uploads');
define('PUBLIC_PATH', SITE_ROOT.DS.'public');
define('ADMIN_PATH', SITE_ROOT.DS.'admin');
define('SITE', "localhost");

$db = new Database();
$session = new Session();


?>