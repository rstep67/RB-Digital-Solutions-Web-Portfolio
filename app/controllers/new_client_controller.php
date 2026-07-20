<?php

$error=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $full_name = trim($full_name);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = trim($email);
    $temp_password = $_POST['temp_password'] ?? '';
    $temp_password = trim($temp_password);

    try {
        $check = $pdo ->prepare('SELECT id FROM users WHERE email = ?');
        $check ->execute([$email]);
        if ($check ->fetch()){
        $error[] = 'Account with same email already exists';
    } 
    else {
        $password_hash = password_hash($temp_pass, PASSWORD_DEFAULT);
        $stmt = $pdo ->prepare('INSERT INTO users (full_name,email,password_hash,role,password_changed,is_active) VALUES (?,?,?,?,?,?)');
        $stmt ->execute([$full_name,$email,$password_hash,'client', false,true]);

        print 'Success';
    }



    }

    

    catch (PDOException $e) {
        $error[] = 'database error: ' . $e -> getMessage();

    }
    if (!empty($error)) {
        print_r($error);
    }

    header('location: ' . BASE_URL . '/?page=admin_dashboard');
    exit;

}