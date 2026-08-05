<?php
$_SESSION = [];

//destroy session cookie

if(ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 50000, 
    $params['path'], $params['domain'],$params['secure'],$params['httponly']);
}

session_destroy();
header('location:  ' . BASE_URL . '/?page=homepage');
exit;