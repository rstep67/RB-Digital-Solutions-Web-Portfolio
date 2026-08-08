<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $errors = [];

    if (empty($project_id)) {
        $errors[] = 'no project selected';
    }

    if (empty($title)) {
        $errors[] = 'project title is blank';

    } 
    elseif (strlen($title) > 150) {
        $errors[] = 'project title cannot be longer than 150 characters';
    }
    
    if (empty($status)) {
        $errors[] = 'status is blank';
        
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('UPDATE projects SET title = ?, description = ?, status = ? WHERE id = ?');
            $stmt ->execute([$title, $description, $status, $project_id]);
            $_SESSION['edit_project_success'] = 'Project "' . $title . '" updated successfully';
        } 
        catch (PDOException $e) {
            $errors[] = 'database error: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $_SESSION['edit_project_errors'] = $errors;
    }
}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;