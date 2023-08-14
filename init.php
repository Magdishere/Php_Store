<?php

//Error reporting

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'admin/connect.php';

$sessionUser = '';
if(isset($_SESSION['user'])){
    $sessionUser = $_SESSION['user'];
}

//Routes

$tpl    = 'include/templates/'; //templates directory
$lang   = 'include/languages/'; //Language Directory
$func   = 'include/functions/'; //Functions Directory
$css    = 'layout/css/'; //Css directory
$js     = 'layout/js/'; //Js Directory



//Include the important files

include $func . 'functions.php';
include $lang . 'en.php';
include $tpl . 'header.php';
