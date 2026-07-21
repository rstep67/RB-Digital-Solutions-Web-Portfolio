<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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

    <?php
    
    if (!empty($_SESSION['client_errors'])) {
        echo "<ul class='form-errors'>";
        foreach ($_SESSION['client_errors'] as $msg) {
            echo "<li>" . htmlspecialchars($msg) . "</li>";
        }
        echo "</ul>";
        unset ($_SESSION['client_errors']);
    }

    if (!empty($_SESSION['client_success'])) {
        echo "<p class='form-success'>" . htmlspecialchars($_SESSION['client_success']) . "</p>";
        unset($_SESSION['client_success']);
    }
    ?>

    <details class="a-dash-new-client">
        <summary>Create new client account</summary>
        <form action="<?= BASE_URL ?>/?page=new_client_controller" method="post">
            <label for="full_name"> Full name</label>
            <input type="text" id ="full_name" name ="full_name" required>
            <label for="email">Email</label>
            <input type="email" id ="email" name ="email" required>
            <label for="temp_password">Temporary Password</label>
            <input type="text" id ="temp_password" name ="temp_password" required>
            <input type="submit" value="Create client">;

        </form>
    </details>

</div>
</section>
