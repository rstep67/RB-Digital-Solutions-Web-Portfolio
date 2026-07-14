<?php

$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

$old_email = $_SESSION['old_login_email'] ?? '';
unset($_SESSION['old_login_email']);

?>


<head>
    
</head>

<body>
    <section class="login-section">
        <div class="container">
            <h1> Login </h1>



            <?php if (!empty($login_error)): ?>
                <p class="form-error">
                    <?= htmlspecialchars($login_error) ?> 
                </p>
            <?php endif; ?>

         <form action="<?= BASE_URL ?>/?page=login" method="post" class="login-form" aria-labelledby="login-form-title">
        <div class="container">
            <div class="user-login-area">
                <label for="username">Username</label>
                <input type="text" placeholder="Enter Username" name="email" required>

                <label for="password">Password</label>
                <input type="password" placeholder="enter password" name="password" required>
        
                <button type="submit">Login</button>
                    
            </div>
            
        </div>
    </form>   



        </div>
    </section>


</body>

