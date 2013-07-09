<?php

session_start();

if (isset($_SESSION['Zend_Auth'])) {
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['user']       = 'admin';

    header("location: " . $_GET['return_url']);
}
