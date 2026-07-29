<?php


if (!isset($_SESSION['role']) || $_SESSION['role'] !=='admin') {
    header('location: ' . BASE_URL . '/?page=admin_dashboard');
    exit;
}
//confirm project selected and that it exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);
    $errors =[];
    if (empty($project_id)) {
        $errors[] = 'file must be attached to a project, select one';
    }
    else {
        $check = $pdo ->prepare("SELECT id FROM projects WHERE id = ?");
        $check ->execute([$project_id]);

        if ($check ->rowCount() === 0) {
            $errors[] = 'selected project not found';
        }
    }
    if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'choose a file to upload';
    }

    else {
        $file = $_FILES['document'];
        $allowed_types = ['image/jpeg', "image/png", "image/gif", 'application/pdf', 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (!in_array($file['type'], $allowed_types)) {
            $errors[] = 'Only images, PDF, and Word documents are allowed.';
        }
        $max_size = 5*1024*1024;
        if($file['size'] > $max_size) {
            $errors[] ='file must be smaller than 5MB';
        }
        
    }

    if (empty($errors)) {
        try {
            //generate unique filename to precent overwrites/erros, keep original display name 
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safe_name =uniqid('doc', true) . '.' . $extension;
            $destination = __DIR__ .'/uploads/documents/' . $safe_name;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $stmt = $pdo ->prepare('INSERT INTO documents (project_id, file_name, file_path) VALUES (?,?,?)');
                $stmt ->execute([$project_id, $file['name'], 'uploads/documents/' . $safe_name]);
                $_SESSION['document_success'] = 'File "' . $file['name'] . '" uploaded successfully.';
            }
            else {
                $errors[] = 'file upload failed';
            }
            
        }
        catch (PDOException $e) {
                $errors[] = 'database error: ' . $e ->getMessage();
            }
    }
    if (!empty($errors)) {
        $_SESSION['document_errors'] = $error;
    }

}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;
?>