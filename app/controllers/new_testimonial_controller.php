<?php
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location: ' .BASE_URL .'/?page=login');
    exit;

}

require_once __DIR__.'/../models/testimonial_model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author_name = filter_input(INPUT_POST, 'author_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author_name = trim($author_name);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = trim($content);

    $errors = [];

    if(empty($author_name)) {
        $errors[] = 'client name is blank';
    }

    else if (strlen($author_name) > 150) {
        $errors[] = 'client name cannot be longer than 150 characters';
    }

    if(empty($content)) {
        $errors[] = 'testimonial is blank';
    }

    if (empty($errors)) {
        try {
            createTestimonial($pdo,$author_name,$content);
            $_SESSION['testimonial_success'] = 'Testimonial added for '. $author_name;
        }
        catch (PDOException $e) {
            $errors[]='database error: '. $e->getMessage();

        }

        
    }

    if (!empty($errors)) {
        $_SESSION['testimonial_errors'] = $errors;
    }
}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;