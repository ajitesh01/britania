<?php
require "constants.php";
ini_set('memory_limit', '-1');

if(DISPLAYERRORS) {
    ini_set('display_errors', DISPLAYERRORS);
    ini_set('display_startup_errors', 1);
    if (ERRORTYPE === 0) error_reporting(E_ALL);
    else if (ERRORTYPE === 1) error_reporting(E_WARNING);
    else if (ERRORTYPE === 2) error_reporting(E_NOTICE);
}


date_default_timezone_set('Asia/Kolkata');
require "config.php";
require 'utils/msdb.php';
require 'utils/functions.php';
date_default_timezone_set('Asia/Kolkata');

$dbw = new db(DBWHOST, DBWUSER, DBWPASS, DBWDB);
$dbr = new db(DBRHOST, DBRUSER, DBRPASS, DBRDB);


$action = (isset($_GET["action"])?$_GET["action"]:(isset($_POST["action"])?$_POST["action"]:"main"));
$action = underscoreToCamelCase($action);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    corsOption();
}
if (function_exists($action)) {
    $args = (isset($_GET["arg"])?explode("/",$_GET["arg"]):null);
    // print_r($_GET["arg"]);
    $action($args);
} else {
    //no such method
    setResponse("no such method exist ".$action);
}

// spl_autoload_register(function($className) {
//     echo 'classes/'.$className.'.php';
//     require_once 'classes/'.$className.'.php';
// });

function __autoload($className)
{
    $filename = 'classes/'.$className.'.php';
    if (file_exists($filename) == false) {
        return false;
    }
    require_once 'classes/'.$className.'.php';
}