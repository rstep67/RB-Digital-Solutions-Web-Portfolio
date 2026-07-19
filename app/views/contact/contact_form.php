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
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required>
            <label for="cont_email">Email</label>
            <input type="text" id="cont_email" name="contact_email" placeholder="Your email" required>
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Message subject" required>
            <label for="message">Message</label>
            <textarea id="message" name="contact_message" placeholder="Your message" required> </textarea>

            <input type="submit" value="Submit">
        </form>
    </div>
</section>