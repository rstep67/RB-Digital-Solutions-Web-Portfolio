<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/models/db_connect.php';

$page = $_GET['page'] ?? 'homepage';

//handle the login form submission so header() redirects work
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    require __DIR__ . '/app/controllers/login_controller.php';
}

elseif ($page === 'change_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        require __DIR__ . '/app/controllers/change_pw_controller.php';
    }
    
    
//pagetitle array before header.php to ensure page name can bedisplayed

$pagetitles = [
    'login' => 'Login',
    'change_pw' => 'Change password',
    'dahsboard' => 'Dashboard',
    'admin_dashboard' => 'Admin dashboard',
    'homepage' => 'Home',
];

$pagetitle = $pagetitles[$page] ?? 'Home';


require __DIR__ . '/app/views/partials/header.php';

switch ($page) {
    case 'login':
        require __DIR__ .'/app/views/login/login.php';
        break;

    case 'homepage':
        require __DIR__ .'/app/views/homepage/homepage.php'; 
        break;  

    case 'change_password':
        require __DIR__ .'/app/views/change_pw/change_pw.php';
        break;

    case 'dashboard':
        require __DIR__ .'/app/views/dashboard/dashboard.php';
        break;

    case 'admin_dashboard':
        require __DIR__ .'/app/views/admin_dashboard/admin_dashboard.php';
        break;

    

    default:
    //fallback for unknown page requests
    $pagetitle = 'Home';
    require __DIR__ .'/app/views/homepage/homepage.php';
    break;
        
}

require __DIR__ .'/app/views/partials/footer.php';