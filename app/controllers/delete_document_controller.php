<?php
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location: ' .BASE_URL .'/?page=login');
    exit;

}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $document_id = filter_input(INPUT_POST, 'document_id', FILTER_VALIDATE_INT);
    $errors=[];

    if (empty($document_id)) {
        $errors[] = 'no document selected';
    }
    else {
        $check = $pdo->prepare('SELECT file_path FROM DOCUMENTS WHERE id=?');
        $check ->execute([$document_id]);
        $document = $check->fetch();

        if (!$document) {
            $errors[] = 'document not found';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('DELETE FROM documents WHERE id=?');
            $stmt ->execute([$document_id]);

            $file_path = __DIR__ . '/../../' . $document['file_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $_SESSION['document_success'] = 'Document deleted successfully';
        }

        catch (PDOException $e) {
            $errors[] = 'database error: ' . $e->getMessage();
        }
    }

    if(!empty($errors)) {
        $_SESSION['document_errors'] = $errors;
    }
}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;