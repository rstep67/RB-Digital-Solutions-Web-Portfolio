<?php 


$error=[];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name =filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = trim($name);
    $email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = trim($email);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $subject =trim($subject);
    $message =filter_input(INPUT_POST, 'contact_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = trim($message);

    if (empty($name)) {
        $error[] = 'Name is blank';
    }

    if (empty($email)) {
        $error[] = 'Email is blank';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Email address is not valid';
    }

    if (empty($subject)) {
        $error[] = 'Subject is blank';
    }

    if (empty($error)) {
        try {
            $stmt = $pdo -> prepare("INSERT INTO contact_submissions (name, email, subject, message) VALUES (?,?,?,?)");
            $stmt   -> execute([$name,$email,$subject,$message]);
            $_SESSION['contact_success'] = 'Thanks for your message, you will recieve a response as soon as possible';
        }
        catch (PDOException $e) {
            $error[] = 'database error' . $e -> getMessage();
        }
    }

    if(!empty($error)) {
        $_SESSION['contact_errors'] = $error;
    }

    //stops resubmission error PRG
    header('location: ' . BASE_URL .'/?page=homepage#contact-form-section');
    exit;
}