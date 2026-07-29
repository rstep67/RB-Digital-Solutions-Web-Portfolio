<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] !=='admin') {
    header('location: ' . __DIR__ . '/?page=admin_dashboard');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$title = trim($title);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$status = trim($status);

$errors =[];

if (empty($user_id)) {
    $errors[]= ' select a client to assign this projec to';
}
if (empty($title)) {
    $errors[]= 'project title is blank';
}
else if (strlen($title) > 150) {
    $errors[] = 'project title cannot be longer than 150 characters';
}

if (empty($status)) {
    $status = 'Not started';
}

if (empty($errors)) {
    $check = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'client'");
    $check ->execute([$user_id]);

    if ($check ->rowCount() === 0 ) {
        $errors[] = 'selected client account not found';
    }
}

if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, description, status) VALUES(?,?,?,?)");
        $stmt ->execute([$user_id, $title, $description, $status]);
        $SESSION['flash_success'] = 'project "'.$title. '" created and assigned successfully';

    }
    catch (PDOException $e) {
        $SESSION['flash_error'] = 'Database error: ' . $e ->getMessage();
        
    }
    
}
else {
        $_SESSION['flash_error'] = implode(' ', $errors);
    }
header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;
}
?>