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

    <!--manage existing clients area -->
    <?php 
    require_once __DIR__ . '/../../models/user_model.php';

    $all_clients = get_all_clients($pdo);

    $selected_client = null;
    if(!empty($_GET['client_id'])) {
        $selected_client = get_client_by_id($pdo, (int)$_GET['client_id']);
    }
    $show_manage_messages = !empty($_SESSION['manage_client_errors']) || !empty($_SESSION['manage_client_success']) || !empty($selected_client);



    ?>

    <details class="a-dash-manage-client" <?=$show_manage_messages ? 'open' : ''?>>
        
        <summary>Manage client accounts</summary>
        <?php
    
    if (!empty($_SESSION['manage_client_errors'])) {
        echo "<ul class='form-errors'>";
        foreach ($_SESSION['manage_client_errors'] as $msg) {
            echo "<li>" . htmlspecialchars($msg) . "</li>";
        }
        echo "</ul>";
        unset ($_SESSION['manage_client_errors']);
    }

    if (!empty($_SESSION['manage_client_success'])) {
        echo "<p class='form-success'>" . htmlspecialchars($_SESSION['manage_client_success']) . "</p>";
        unset($_SESSION['manage_client_success']);
    }
    ?>
    <!-- pick which client to edit, loads into form to be edited below-->
     <form action ="<?= BASE_URL ?>/?page=admin_dashboard" method="get">
        <input type="hidden" name="page" value="admin_dashboard">
        <label for="client_id">Select client</label>
        <select id="client_id" name="client_id">
            <option value="">choose client</option>
            <?php
            foreach ($all_clients as $client): ?>
            <option value="<?= (int) $client['id']?>" <?=(!empty($selected_client) && $selected_client['id'] == $client['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($client['full_name']) ?> (<?=htmlspecialchars($client['email'])?>) - <?= $client['is_active'] ? 'active' : 'inactive' ?>
            </option>
            <?php endforeach; ?>

        </select>

        <input type="submit" value="load client">

        
     </form>
     <?php if ($selected_client): ?>
        <form action="<?= BASE_URL?>/?page=manage_client" method="post">
            <input type="hidden" name="client_id" value="<?= (int) $selected_client['id']?>">

            <label for="edit_full_name"> Full name</label>
            <input type="text" id="full_name" name="full_name" value ="<?= htmlspecialchars($selected_client['full_name']) ?>" required>
             <label for="edit_email">Email</label>
            <input type="email" id="edit_name" name="email" value ="<?= htmlspecialchars($selected_client['email']) ?>" required>
            <label for="edit_is_active">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" <?= $selected_client['is_active'] ? 'checked' : '' ?>>
                    Active
                </label>

                <input type="submit" value="Update client">

        </form>
        <?php endif;?>
    </details>


</div>
</section>
