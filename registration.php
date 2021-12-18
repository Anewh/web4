<?php

    //require "validation/redirect.php";
    //redirectIfUserLogged();

    require_once "validation/registrationValidation.php";
    require_once "models/User.php";

    header("Content-Type: application/json");

    $data = $_POST;
    $validator = new RegistrationValidation($data);
    $validator->isValid();
    //$errors = $validator->getErrors();
    $errors = [];

    if (!empty($errors)) {
        $response =[
            "status" =>false,
            "errors" => json_encode($errors)
        ];
        echo json_encode($response);
        die();
    }

    $user_model = new User();

    if (!$user_model->isFreeEmail($data['email'])) {
        array_push($errors, 'This email is used by another user');
        $response =[
            "status" =>false,
            "errors" => json_encode($errors)
        ];
        echo json_encode($response);
        die();
    }

    $user_model->addUser($data['name'], $data['email'], $data['password'], $data['phone_number']);

    session_start();
    $_SESSION["id"]=$user['id'];
    $response =[
        "status" =>true,
        "user_login"=>$data['email'],
        "is_moderator"=>0
    ];
    echo json_encode($response);
