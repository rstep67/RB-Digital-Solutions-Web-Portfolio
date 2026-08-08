<section class="contact-form-section" id="contact-form-section">
    <div class="container">
        <?php
        if (!empty($_SESSION['contact_errors'])) {
            echo "<ul class='form-errors'>";

            foreach ($_SESSION['contact_errors'] as $msg) {
                echo "<li>" . htmlspecialchars($msg) . "</li>";

            }
            echo "</ul>";

            unset($_SESSION['contact_errors']);
        }

        if (!empty ($_SESSION['contact_success'])) {
            echo "<p class='form-success'>" . htmlspecialchars($_SESSION['contact_success']) . "</p>";
            unset($_SESSION ['contact_success']);
        }

        ?>



        <form action="<?= BASE_URL ?>/?page=contact" method="post">
            <h2 class="contact-form-title">Contact</h2>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required>
            <label for="cont_email">Email</label>
            <input type="email" id="cont_email" name="contact_email" placeholder="Your email" required>
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Message subject" required>
            <label for="message">Message</label>
            <textarea id="message" name="contact_message" placeholder="Your message" required> </textarea>
            
            <!--cloudflare turnstile-->
            
<div class="cf-turnstile" data-sitekey="<?=TURNSTILE_SITE_KEY?>"></div>
       <label class="checkbox-label" for="privacy_policy_agreed">
        <input type="checkbox" id="privacy_policy_agreed" name="privacy_policy_agreed" required>
        I have read and agreed to the <a href="<?= BASE_URL ?>/?page=privacy_policy" target="_blank" rel="noopener noreferrer"> privacy policy </a>
       </label>     

<button type="submit">Submit</button>
        </form>
    </div>
</section>