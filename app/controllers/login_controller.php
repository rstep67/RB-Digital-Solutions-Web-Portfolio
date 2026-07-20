<?php
//processes login form submisison, file must run before header.php

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$email = trim($email);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

$generic_error = 'Invalid email or password!';




if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = $generic_error;
    //keep email pre filled if details incorrect so it doesnt have to be typed out again 
    $_SESSION['old_login_email'] = $email;
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}


$stmt = $pdo -> prepare('SELECT id, full_name, email, password_hash, role, password_changed, is_active FROM users WHERE email = ?');
$stmt -> execute([$email]);
$user = $stmt -> fetch();




//check user exists, password matches, and account is activr
if(!$user || !password_verify($password, $user['password_hash']) || !$user['is_active']) {
    $_SESSION['login_error'] = $generic_error;
    $_SESSION['old_login_email'] = $email;
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}


//successfil login, regenerate session id to prevent session attacks

session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['role'] = $user['role'];
$_SESSION['password_changed'] = (bool) $user['password_changed'];

//force password change on first session for security purposes 
if (!$_SESSION['password_changed']) {
    header('location: ' . BASE_URL . '/?page=change_password');
    exit;
}

//if already changed route based on role
if ($_SESSION['role'] === 'admin') {
    header('location: ' . BASE_URL . '/?page=admin_dashboard');

}
else {
    header('location: ' . BASE_URL . '/?page=dashboard');
}

exit;
