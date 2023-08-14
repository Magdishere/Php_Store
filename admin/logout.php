<?php


session_start();

    session_unset(); //Unset the session

    session_destroy(); //Destroy the session

    header("Location: index.php");

    exit();