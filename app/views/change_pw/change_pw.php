<?php
//page requires authenticated session, redirect to login if not loggd in
if (!isset($_SESSION['user_id'])) {
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}


$change_pw_error = $_SESSION['change_pw_error'] ?? '';
unset($_SESSION['change_pw_error']);
?>


<section class="change-pw-sect">
    <div class="container">
        <h1>Change Password</h1>

        <?php if (!empty($change_pw_error)): ?>
            <p class="form-error">
                <?=htmlspecialchars($change_pw_error)?>
            </p>
        <?php endif;?>

        <form name="change_pw" action="<?= BASE_URL?>/?page=change_password" method="post" class="change-pw-form">
            <label for="new_pw">New password</label>
            <input type="password" id="new_pw" name="new_pw" required minlength="8">
            <label for="confirm_pw">Confirm password</label>
            <input type="password" id="confirm_pw" name="confirm_pw" required minlength="8">
            <button type="submit"> Update password </button>
        </form>
    </div>
</section>