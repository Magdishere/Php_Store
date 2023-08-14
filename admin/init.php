<?php

include 'connect.php';
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

//Include Navbar in all pages execpt the ones that doesnt have the $noNavbar variable

if(!isset($noNavbar)){

    include $tpl . 'navbar.php';

}