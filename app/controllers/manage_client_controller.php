<?php
$error=[];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = filter_input(INPUT_POST, 'client_id', FILTER_VALIDATE_INT);
    $full_name = filter_input(INPUT_POST,'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $full_name = trim($full_name);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = trim($email);

    $is_active = isset($_POST['is_active']) ? 1:0;

    if (empty($client_id)) {
        $error[] = 'No client selected';
    }
    if (empty($full_name)) {
        $error[] = 'Full name is blank ';
    }
    if (empty($email)) {
        $error[] = 'Email address is blank';
    }
    
    if (empty($error)) {
        try {
            $check = $pdo ->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $check ->execute([$email, $client_id]);

            if($check ->fetch ()) {
                $error[] = 'another account is already using that email ';
            }
            else {
                $stmt = $pdo ->prepare('UPDATE users SET full_name = ?, email = ?, is_active =? WHERE id= ? AND role=?');
                $stmt ->execute([$full_name, $email, $is_active, $client_id, 'client']);
                $_SESSION['manage_client_success'] = 'client account updated';
            }
        }
        catch (PDOException $e) {
            $error[] ='database error:' . $e->getMessage();
        }
    }
    if (!empty($error)) {
        $_SESSION['manage_client_errors'] = $error;
    }


}

header('location:' . BASE_URL .'/?page=admin_dashboard');
exit;