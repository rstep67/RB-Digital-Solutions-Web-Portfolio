<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}
?>


<section class="a-dash-sect">
    <div class="container">
        <h1> Admin Dashboard </h1>
        <p>logged in as <?= htmlspecialchars($_SESSION['full_name']) ?>
         (Admin)
    </p>

</div>
</section>
