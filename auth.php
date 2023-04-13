<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$layout = include_template('auth.php', ['title' => 'Авторизация']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    $errors =[];

    if ($user_email == '') {
        $errors['email'] = 'Введите e-mail';
    } elseif (filter_var($user_email, FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'E-mail введён некорректно';
    } else {
        $sql = "SELECT email FROM users WHERE email = ".'"'.$user_email.'"';
        $res = mysqli_query($con, $sql);
        $check_email = mysqli_fetch_assoc($res);
        $check_email = $check_email['email'];
        if (!$check_email) {
            $errors['email'] = 'Пользователь с таким e-mail не найден';
        }

    }
    if ($user_password == '') {
        $errors['password'] = 'Введите пароль';
    } else {
        $sql = "SELECT password FROM users WHERE email = ".'"'.$user_email.'"';

        $res = mysqli_query($con, $sql);
        $check_password = mysqli_fetch_assoc($res);
        $check_user_password = $check_password['password'];
        if (!password_verify($user_password, $check_user_password)) {
            $errors['password'] = 'Неверный пароль';
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $layout = include_template('auth.php', ['errors' => $errors, 'title' => 'Авторизация']);
    } else {
        $sql= "SELECT id FROM users WHERE email = ".'"'.$user_email.'"';
        $res = mysqli_query($con, $sql);
        $user = mysqli_fetch_assoc($res);
        $user_id = $user['id'];
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = $user_id;
        }
        header("Location: index.php");
    }

}

print($layout);
