<?php
    define("DATA_LAYER_CONFIG1",
        [
            "host" => "localhost",
            "port" => null,
            "dbname" => "datalayer",
            "username" => "root",
            "passwd" => "",
            "options" => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ]
        ]);

    define("DATA_LAYER_CONFIG",
        [
            "host" => "186.202.41.211",
            "port" => "1433",
            "dbname" => "acaocompras1",
            "username" => "sql_admin",
            "passwd" => "TGAAGT@loc2018@",
            "options" => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_CASE => PDO::CASE_NATURAL
            ]
        ]);




