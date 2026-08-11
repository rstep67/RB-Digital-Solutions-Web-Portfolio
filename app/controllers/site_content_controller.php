<?php
require_once __DIR__ .'/../models/site_content_model.php';

// only runs when form submitted 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (($_SESSION['role'] ?? null) !== 'admin') {
        header('Location: ' . BASE_URL . '/?page=login');
        exit;
    }

    $experience_text = trim(filter_input(INPUT_POST,'experience_text', FILTER_UNSAFE_RAW) ?? '');
    $skills_text = trim(filter_input(INPUT_POST, 'skills_text', FILTER_UNSAFE_RAW) ?? '');
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if ($experience_text === '' || $skills_text === '') {
        $_SESSION['site_content_errors'] = ['experience and skills fields cannot be empty'];
        header('location: '.BASE_URL. '/?page=admin_dashboard');
        exit;
    }

    updateSiteContent($pdo, $experience_text, $skills_text, $is_available);
    $_SESSION['site_content_success'] = 'site content updated';
    header('Location: ' . BASE_URL . '/?page=admin_dashboard');
    exit;

}
