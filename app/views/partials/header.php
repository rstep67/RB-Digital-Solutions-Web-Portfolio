<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width", initial-scale="1.0">
        
        <!--google fonts-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

        <link rel="stylesheet" href="/Web-Portfolio/public/css/style.css?v=4">
        
        <title><?= $pagetitle;?></title>

        
        
    </head>
    <body>
        <header class="site-header">
            <div class="container inner-header">
                <img src="/Web-Portfolio/public/images/RBDS Logo New.png" class="logo" alt="RB Digital Solutions Logo">

                <nav class="site-nav">
                    <ul>
                        
                            <li><a href="http://localhost/Web-Portfolio/?page=homepage">Home</a> </li>
                            <li><a href="http://localhost/Web-Portfolio/?page=contact-form.php"> Contact </a></li>
                            <?php if (isset($_SESSION['role'])): ?>
                                <?php if ($_SESSION['role'] === 'admin'):?>
                                <li><a href="<?= BASE_URL?>/?page=admin_dashboard">Dashboard</a></li>

                                <?php else: ?>
                                    <li><a href="<?= BASE_URL?>/?page=dashboard">Dashboard</a></li>
                                    <?php endif; ?>
                                    <li><a href="<?= BASE_URL?>/?page=logout">Logout</a></li>
                                    <?php else: ?>
                                        <li><a href="<?= BASE_URL?>/?page=login">Login</a></li>
                                    <?php endif; ?>
                                    
                           

                    
                        
                    </ul>
                </nav>
            </div>
        </header>
    <main>

