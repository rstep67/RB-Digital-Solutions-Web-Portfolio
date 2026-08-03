<?php

$error = [];

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location: ' .BASE_URL .'/?page=login');
    exit;

}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = filter_input(INPUT_POST, 'project_id', FILTER_VALIDATE_INT);

    if (empty($project_id)) {
        $error[] = 'no project selected';
    }

    if (empty($error)) {
        try {
            $check = $pdo->prepare('SELECT id FROM projects WHERE id = ?');
            $check->execute([$project_id]);

            if (!$check->fetch()) {
                $error[] = 'project not found';
            }
            else {
                //get file paths before deleting rows so they can be removed from disc after
                $doc_stmt = $pdo ->prepare('SELECT id, file_path FROM documents WHERE project_id=?');
                $doc_stmt ->execute([$project_id]);
                $documents = $doc_stmt ->fetchAll();

                $pdo ->beginTransaction();

                $delete_docs = $pdo ->prepare('DELETE FROM documents WHERE project_id =?');
                $delete_docs ->execute([$project_id]);
                $delete_project = $pdo ->prepare('DELETE FROM projects WHERE id=?');
                $delete_project ->execute([$project_id]);
                $pdo ->commit();

                //db rows gone, remove actual files from disc
                foreach ($documents as $document) {
                    $full_path = __DIR__ . '/../../' . $document['file_path'];

                    if (file_exists($full_path)) {
                        unlink($full_path);
                    }
                }

                $_SESSION['manage_project_success'] = 'project and ' .count($documents) . 'associated documents deleted';
            }
        }
        catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo ->rollBack();
            }
            $error[] = 'database error: ' .$e->getMessage();
        }
        
    }
    if (!empty($error)) {
    $_SESSION['manage_project_errors'] = $error;

    }
}

header('location: ' .BASE_URL . '/?page=admin_dashboard');
exit;
