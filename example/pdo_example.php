<?php

    require 'db_config.php';
    require '../vendor/autoload.php';

    use Vresende\DataLayer\Connect;

    /*
     * GET PDO instance AND errors
     */
    $connect = Connect::getInstance();
    $error = Connect::getError();

    /*
     * CHECK connection/errors
     */
    if ($error) {
        echo $error->getMessage();
        exit;
    }

    /*
     * FETCH DATA
     */
    $users = $connect->query("SELECT TOP 5 * FROM users");
    var_dump($users->fetchAll());