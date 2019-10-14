<?php

    require 'db_config.php';
    require '../vendor/autoload.php';

    require 'Models/User.php';

    use Example\Models\User;

    /*
     * MODEL
     */
    print "model";
    $model = new User();
    // var_dump($model);

    print "findById";
    $user = $model->findById(10);


    /**
     * FIND EXAMPLE
     */
    print "find";
    $users = $model->limit(2)->find("email = :email", "email=consultoria2@agrotama.com.br")->fetch(true);

    var_dump($users);
//$users = $model->find()->limit(2)->fetch(true);
//$users = $model->find()->limit(2)->offset(2)->fetch(true);
//$users = $model->find()->limit(2)->offset(2)->order("first_name ASC")->fetch(true);

    //   $totalUsers = $model->find("email = :email", "email=consultoria2@agrotama.com.br")->count();
    //   var_dump($users);

    if ($users) {
        foreach ($users as $user) {
            var_dump($user, $model->fail());
        }
    } else {
        echo "<h2>Not Users</h2>";
    }


