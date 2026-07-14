<?php
if (!isset ($_SESSION['user_id'])) {
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}

?>

<section class="dash-section">
    <div class="container">
        <h1> Client Dashboard </h1>
        <p>
            Logged in as <?= htmlspecialchars($_SESSION['full_name']) ?> 
            (<?= htmlspecialchars($_SESSION['role']) ?>). 
        </p>
    </div>
