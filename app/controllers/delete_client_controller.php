<?php
$error =[];

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('location: ' .BASE_URL .'/?page=login');
    exit;

}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = filter_input(INPUT_POST,'client_id', FILTER_VALIDATE_INT);

    if(empty($client_id)) {
        $error[] = 'no client selected';
    }

    if (empty($error)) {
        try {
            $check = $pdo->prepare('SELECT id FROM users WHERE id = ? AND role = ?');
            $check->execute([$client_id,'client']);

            if(!$check->fetch()) {
                $error[]='client not found';
            }
            else {
                //stop client account deletion if projects exist to avoid orphaned entries
                $project_check = $pdo->prepare('SELECT COUNT(*) FROM projects WHERE user_id = ?');
                $project_check->execute([$client_id]);
                $project_count = $project_check->fetchColumn();

                if ($project_count > 0) {
                    $error[] = 'cant delete client: ' .$project_count . 'projects still assigned to this account, remove first!';

                }
                else {
                    $stmt = $pdo->prepare('DELETE FROM users WHERE id= ? AND role = ?');
                    $stmt->execute([$client_id,'client']);
                    $_SESSION['manage_client_success'] = 'client account deleted';

                }
                
            }
        }
        catch(PDOException $e) {
            $error[] = 'Database error: ' . $e->getMessage();
            
        }
    }
    if (!empty($error)) {
        $_SESSION['manage_client_errors'] = $error;
    }
}

header('location: ' . BASE_URL . '/?page=admin_dashboard');
exit;