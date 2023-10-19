<?php

session_start();

try {

    require_once('controller.php');

    $route = $_GET['action'] ?? null;

    switch ($route) {
        case 'register':
            if (isset($_SESSION['loggedIn'])) {
                chat();
            } else {
                register();
            }
            break;
        case 'login':
            if (isset($_SESSION['loggedIn']) && !isset($_GET['logOut'])) {
                chat();
            } else {
                login();
            }
            break;
        case 'chat':
            if (isset($_SESSION['loggedIn'])) {
                chat();
            } else {
                defaultPage();
            }
            break;
        default:
            defaultPage();
            break;
    }

} catch (Throwable $e) {
    // displayError($e->getMessage());
    // echo $e->getMessage();
    // die;
    displayTheError($e);
}