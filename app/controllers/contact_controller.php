<?php 

//verify turnstile token with clurflares siteverify endpoint before trusting
function verifyTurnstile($token) {
    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch,CURLOPT_POSTFIELDS, ['secret' =>TURNSTILE_SECRET_KEY, 'response' => $token,]);
    $result= curl_exec($ch);
    curl_close($ch);

    $result = json_decode($result, true);
    return $result['success'] ?? false;


    
}

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
    $turnstile_token = filter_input(INPUT_POST, 'cf-turnstile-response', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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

    if (empty($_POST['privacy_policy_agreed'])) {
        $error[] = 'Confirm privacy policy';
    }


    //cloudflare turnstile
    if (empty($turnstile_token) || !verifyTurnstile($turnstile_token)) {
        $error[]='bot verification failed, try again';
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