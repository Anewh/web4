<?php

    require_once 'models/user.php';
    require_once 'validation/enterValidation.php';

    header("Content-Type: application/json");

    $data = $_POST;
    $validator = new EnterValidation($data);
    $validator->isValid();
    $errors = $validator->getErrors();

    if (!empty($errors)) {
        $response =[
            "status" =>false,
            "errors" => json_encode($errors)
        ];
        echo json_encode($response);
        die();
    }
    $user_model = new User();
    $user = $user_model->getUserByEmail($data['email']);
    $save_password = htmlspecialchars($data['password']);

    if (password_verify($save_password, $user['password'])) {
        session_start();
        $_SESSION["id"] = $user['id'];
        $_SESSION["user_name"] = $user['name'];
        $response =[
            "status" =>true,
            "user_login"=>$user['email'],
            "is_moderator"=>$user['is_moderator']
        ];
    } else {
        array_push($errors, 'wrong password');
        $response =[
            "status" =>false,
            "errors" => json_encode($errors)
        ];
    }

    echo json_encode($response);
