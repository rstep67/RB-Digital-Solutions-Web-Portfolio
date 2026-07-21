<?php

$error=[];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $full_name = trim($full_name);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = trim($email);
    $temp_password = $_POST['temp_password'] ?? '';
    $temp_password = trim($temp_password);

    if (empty($full_name)) {
        $error[] ='Full name is blank';
    }
    if (empty($email)) {
        $error[] ='Email is blank';
    }
    else if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Email address not valid';

    }

    if (empty($temp_password)) {
        $error[] ='Temporary password is blank';
    }
    else if (strlen($temp_password) < 8) {
        $error[] ='Temporary password needs to be at least 8 characters';

    }
    

    if (empty($full_name)) {
        $error[] ='Full name is blank';
    }

    if (empty($error)) {
       try {
        $check = $pdo ->prepare('SELECT id FROM users WHERE email = ?');
        $check ->execute([$email]);
        if ($check ->fetch()){
        $error[] = 'Account with same email already exists';

        
    } 
    else {
        $password_hash = password_hash($temp_password, PASSWORD_DEFAULT);
        $stmt = $pdo ->prepare('INSERT INTO users (full_name,email,password_hash,role,password_changed,is_active) VALUES (?,?,?,?,?,?)');
        $stmt ->execute([$full_name,$email,$password_hash,'client', false,true]);

        $_SESSION['client_success'] ='Client account created for ' . $full_name;
    } 
    }

    catch (PDOException $e) {
        $error[] = 'database error: ' . $e -> getMessage();

    }
    

    
    }

    if (!empty($error)) {
        $_SESSION['client_errors'] = $error;
    }

}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;
