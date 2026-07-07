<?php

require_once __DIR__ . '/config/config.php';

$page = $_GET['page'] ?? 'homepage';

switch ($page) {
    case 'login':
        $pagetitle = "Login";
        break;

    case 'homepage':
        $pagetitle = "Home";
        break; 
}

require __DIR__ . '/app/views/partials/header.php';

switch ($page) {
    case 'login':
        $pagetitle = "Login";
        require __DIR__ .'/app/views/login/login.php';
        break;

    case 'homepage':
        $pagetitle = "Home";
        require __DIR__ .'/app/views/homepage/homepage.php'; 
        break;  
        
}
require __DIR__ .'/app/views/partials/footer.php';