<?php
//processes the change-pw form submission, run before header.php so redirect works appropriately
//check to see if user is logged in before pass change, redirects to login so no errors 
if (!isset($_SESSION['user_id'])) {
    header('location: '. BASE_URL .'/?page=login');
    exit;
}

$new_pw = filter_input(INPUT_POST, 'new_pw', FILTER_UNSAFE_RAW);
$confirm_pw = filter_input(INPUT_POST, 'confirm_pw', FILTER_UNSAFE_RAW);



if (empty($new_pw) || empty($confirm_pw)) {
    $_SESSION['change_pw_error'] = 'Both fields not filled in';
    header('location: ' . BASE_URL . '/?page=change_password');
    exit;
}

if ($new_pw !== $confirm_pw) {
    $_SESSION['change_pw_error'] = "Password do not match!";
    header('location: ' . BASE_URL . '/?page=change_password');
    exit;

}

if (strlen($new_pw) < 8) {
    $_SESSION['change_pw_error'] = 'password must be more than 8 characters long';
    header('location: ' . BASE_URL . '/?page=change_password');
    exit;
}



$newpw_hash = password_hash($new_pw, PASSWORD_DEFAULT);

$stmt = $pdo -> prepare('UPDATE users SET password_hash = ?, password_changed = TRUE WHERE id = ?' );
$stmt -> execute([$newpw_hash,$_SESSION['user_id']]);

//updates browser session after DB 
$_SESSION['password_changed'] =true;



if ($_SESSION['role'] === 'Admin') {
    header('location:' . BASE_URL . '/?page=admin_dashboard');
} 
else {
    header('location: ' . BASE_URL . '/?page=dashboard');
}


exit;