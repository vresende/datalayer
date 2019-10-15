<?php

    require 'db_config.php';
    require '../vendor/autoload.php';

    require 'Models/Category.php';

    $Cat = (new \Example\Models\Category())->find()->fetch();

    var_dump($Cat->save());


