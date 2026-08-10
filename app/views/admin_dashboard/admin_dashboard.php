<?php
$site_content = getSiteContent($pdo);
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

        <!--ADD NEW CLIENT-->
        <?php $show_new_client_messages = !empty($_SESSION['client_errors']) || !empty($_SESSION['client_success']); ?>

        <details class="a-dash-new-client admin-accordion" <?= $show_new_client_messages ? 'open' : '' ?>>
            <summary>Create new client account</summary>
            <?php if (!empty($_SESSION['client_errors'])): ?>
        <ul class="form-errors">
            <?php foreach ($_SESSION['client_errors'] as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['client_errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['client_success'])): ?>
        <p class="form-success"><?= htmlspecialchars($_SESSION['client_success']) ?></p>
        <?php unset($_SESSION['client_success']); ?>
    <?php endif; ?>


            <form action="<?= BASE_URL ?>/?page=new_client_controller" method="post">
                <label for="full_name"> Full name</label>
                <input type="text" id="full_name" name="full_name" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="temp_password">Temporary Password</label>
                <input type="text" id="temp_password" name="temp_password" required>
                <input type="submit" value="Create client">;

            </form>
        </details>

        <!--manage existing clients area -->
        <?php
        require_once __DIR__ . '/../../models/user_model.php';
        require_once __DIR__ . '/../../models/testimonial_model.php';
        require_once __DIR__ . '/../../models/document_model.php';

        $all_clients = getAllClients($pdo);

        $selected_client = null;
        if (!empty($_GET['client_id'])) {
            $selected_client = getClientById($pdo, (int) $_GET['client_id']);
        }
        $show_manage_messages = !empty($_SESSION['manage_client_errors']) || !empty($_SESSION['manage_client_success']) || !empty($selected_client);



        ?>
        <!--MANAGE CLIENT ACCOUNTS-->
        <details class="a-dash-manage-client admin-accordion" <?= $show_manage_messages ? 'open' : '' ?>>

            <summary>Manage client accounts</summary>
            <?php

            if (!empty($_SESSION['manage_client_errors'])) {
                echo "<ul class='form-errors'>";
                foreach ($_SESSION['manage_client_errors'] as $msg) {
                    echo "<li>" . htmlspecialchars($msg) . "</li>";
                }
                echo "</ul>";
                unset($_SESSION['manage_client_errors']);
            }

            if (!empty($_SESSION['manage_client_success'])) {
                echo "<p class='form-success'>" . htmlspecialchars($_SESSION['manage_client_success']) . "</p>";
                unset($_SESSION['manage_client_success']);
            }

            ?>
            <!-- pick which client to edit, loads into form to be edited below-->
            <form action="<?= BASE_URL ?>/?page=admin_dashboard" method="get">
                <input type="hidden" name="page" value="admin_dashboard">
                <label for="client_id">Select client</label>
                <select id="client_id" name="client_id">
                    <option value="">choose client</option>
                    <?php
                    foreach ($all_clients as $client): ?>
                        <option value="<?= (int) $client['id'] ?>" <?= (!empty($selected_client) && $selected_client['id'] == $client['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($client['full_name']) ?> (<?= htmlspecialchars($client['email']) ?>) -
                            <?= $client['is_active'] ? 'active' : 'inactive' ?>
                        </option>
                    <?php endforeach; ?>

                </select>

                <input type="submit" value="load client">


            </form>
            <?php if ($selected_client): ?>
                <form action="<?= BASE_URL ?>/?page=manage_client" method="post"
    onsubmit="return confirm('Save these changes to the client account?');">
                    <input type="hidden" name="client_id" value="<?= (int) $selected_client['id'] ?>">

                    <label for="edit_full_name"> Full name</label>
                    <input type="text" id="edit_full_name" name="full_name"
                        value="<?= htmlspecialchars($selected_client['full_name']) ?>" required>
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email"
                        value="<?= htmlspecialchars($selected_client['email']) ?>" required>
                    <label for="edit_is_active">
                        <input type="checkbox" id="edit_is_active" name="is_active" value="1"
                            <?= $selected_client['is_active'] ? 'checked' : '' ?>>
                        Active
                    </label>

                    <input type="submit" value="Update client">

                </form>
                <!--delete client controller-->
                <form action="<?= BASE_URL ?>/?page=delete_client_controller" method="post"
                    onsubmit="return confirm('delete this client account? This cannot be undone.');">
                    <input type="hidden" name="client_id" value="<?= (int) $selected_client['id'] ?>">
                    <input type="submit" value="Delete client" class="btn-danger">
                </form>



            <?php endif; ?>
        </details>
        <?php



        //document upload section
        $clients = $pdo->query("SELECT id, full_name, email FROM users WHERE role = 'client'");
        $projects = $pdo->query('SELECT projects.id, projects.title, users.full_name FROM projects 
    JOIN users ON projects.user_id = users.id ORDER BY title')->fetchAll();

        $show_document_messages = !empty($_SESSION['document_success']) || !empty($_SESSION['document_errors']);
        $show_new_project_messages = !empty($_SESSION['flash_success']) || !empty($_SESSION['flash_error']);

        ?>
        <!-- CREATE NEW PROJECT-->
        <details class="admin-accordion" <?= $show_new_project_messages ? 'open' : '' ?>>
            <summary>Create New Project</summary>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <p class="flash-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></p>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <p class="flash-errors"><?= htmlspecialchars($_SESSION['flash_error']) ?></p>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>



            <form action="<?= BASE_URL ?>/?page=new_project" method="post"
    onsubmit="return confirm('Save these changes to the client account?');">
                <label for="user_id">Assign to client</label>
                <select name="user_id" id="user_id" required>
                    <option value="">Select client</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['id']) ?>">
                            <?= htmlspecialchars($client['full_name']) ?> (<?= htmlspecialchars($client['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="title">Project title</label>
                <input type="text" name="title" id="title" required maxlength="150">

                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"></textarea>

                <label for="status">Status</label>
                <input type="text" name="status" id="status" placeholder="e.g. Not Started" maxlength="150" required>

                <button type="submit">Create Project</button>
            </form>
        </details>

        

        <?php $show_delete_project_messages = !empty($_SESSION['manage_project_success']) || !empty($_SESSION['manage_project_errors']); ?>

        <!-- DELETE PROJECT-->

        <details class="admin-accordion" <?= $show_delete_project_messages ? 'open' : '' ?>>
            <summary>Delete a project</summary>

            <?php if (!empty($_SESSION['manage_project_success'])): ?>
            <p class="flash-success"> <?= htmlspecialchars($_SESSION['manage_project_success']) ?></p>
            <?php unset($_SESSION['manage_project_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['manage_project_errors'])): ?>
            <ul class="flash-errors">
                <?php foreach ($_SESSION['manage_project_errors'] as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>

            </ul>

            <?php unset($_SESSION['manage_project_errors']); ?>
        <?php endif; ?>

            <form action="<?= BASE_URL ?>/?page=delete_project_controller" method="post"
                onsubmit="return confirm('delete this project and associated documents');">
                <label for="delete_project_id">Project</label>
                <select name="project_id" id="delete_project_id" required>
                    <option value="">select projetc</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?= htmlspecialchars($project['id']) ?>">
                            <?= htmlspecialchars($project['title']) ?> - <?= htmlspecialchars($project['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="delete project" class="btn-warning">
            </form>
        </details>

        <!--MANAGE EXISTING PROJECTS-->
        <?php
        $selected_project = null;
        if (!empty($_GET['edit_project_id'])) {
            $stmt = $pdo->prepare('SELECT id, title, description, status FROM projects WHERE id = ?');
            $stmt->execute([(int) $_GET['edit_project_id']]);
            $selected_project = $stmt->fetch();
        }
        $show_edit_project_messages = !empty($_SESSION['edit_project_errors']) || !empty($_SESSION['edit_project_success']) || !empty($selected_project);
        ?>

        <details class="admin-accordion" <?= $show_edit_project_messages ? 'open' : '' ?>>
            <summary>Edit a project</summary>

            <?php if (!empty($_SESSION['edit_project_errors'])): ?>
                <ul class="flash-errors">
                    <?php foreach ($_SESSION['edit_project_errors'] as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['edit_project_errors']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['edit_project_success'])): ?>
                <p class="flash-success"><?= htmlspecialchars($_SESSION['edit_project_success']) ?></p>
                <?php unset($_SESSION['edit_project_success']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/?page=admin_dashboard" method="get">
                <input type="hidden" name="page" value="admin_dashboard">
                <label for="edit_project_id">Select project</label>
                <select id="edit_project_id" name="edit_project_id">
                    <option value="">choose project</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?= (int) $project['id'] ?>" <?= (!empty($selected_project) && $selected_project['id'] == $project['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($project['title']) ?> — <?= htmlspecialchars($project['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="load project">
            </form>

            <?php if ($selected_project): ?>
                <form action="<?= BASE_URL ?>/?page=manage_project_controller" method="post"
                    onsubmit="return confirm('Save these changes to the project?');">
                    <input type="hidden" name="project_id" value="<?= (int) $selected_project['id'] ?>">

                    <label for="edit_title">Project title</label>
                    <input type="text" id="edit_title" name="title"
                        value="<?= htmlspecialchars($selected_project['title']) ?>" required maxlength="150">

                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description"
                        rows="4"><?= htmlspecialchars($selected_project['description']) ?></textarea>

                    <label for="edit_status">Status</label>
                    <input type="text" id="edit_status" name="status"
                        value="<?= htmlspecialchars($selected_project['status']) ?>" required maxlength="150">

                    <input type="submit" value="Update project">
                </form>
            <?php endif; ?>
        </details>


        <!--UPLOAD FILE TO PROJECT-->
        <Details class="admin-accordion" <?= $show_document_messages ? 'open' : '' ?>>

            <summary>Upload project file</summary>
            <?php if (!empty($_SESSION['document_success'])): ?>
                <p class="flash-success"> <?= htmlspecialchars($_SESSION['document_success']) ?> </p>
                <?php unset($_SESSION['document_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['document_errors'])): ?>
                <ul class="flash-errors">
                    <?php foreach ($_SESSION['document_errors'] as $msg): ?>
                        <li> <?= htmlspecialchars($msg) ?> </li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['document_errors']); ?>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/?page=upload_document" enctype="multipart/form-data">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" required>
                    <option value="">Select option</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?= htmlspecialchars($project['id']) ?>">
                            <?= htmlspecialchars($project['title']) ?> — <?= htmlspecialchars($project['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>



                <label for="document">file</label>
                <input type="file" name="document" id="document" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" required>
                <button type="submit">Upload file</button>

            </form>


        </Details>

        
 
        <?php $show_delete_document_messages = !empty($_SESSION['document_delete_success']) || !empty($_SESSION['document_delete_errors']); ?>

        <!--DELETE PROJECT FILE-->
        <details class="admin-accordion" <?= $show_delete_document_messages ? 'open' : '' ?>>
            <summary>Delete project file</summary>

            <?php if (!empty($_SESSION['document_delete_success'])): ?>
                <p class="flash-success"><?= htmlspecialchars($_SESSION['document_delete_success']) ?></p>
                <?php unset($_SESSION['document_delete_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['document_delete_errors'])): ?>
                <ul class="flash-errors">
                    <?php foreach ($_SESSION['document_delete_errors'] as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['document_delete_errors']); ?>
            <?php endif; ?>

            <?php $all_documents = getAllDocumentsWithProject($pdo); ?>
            <form action="<?= BASE_URL ?>/?page=delete_document_controller" method="post"
                onsubmit="return confirm('Delete this file? This cannot be undone.');">
                <label for="delete_document_id">File</label>
                <select name="document_id" id="delete_document_id" required>
                    <option value="">Select file</option>
                    <?php foreach ($all_documents as $document): ?>
                        <option value="<?= (int) $document['id'] ?>">
                            <?= htmlspecialchars($document['file_name']) ?> — <?= htmlspecialchars($document['project_title']) ?> (<?= htmlspecialchars($document['full_name']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Delete file" class="btn-warning">
            </form>
        </details>

         

        <?php $show_manage_testimonials_messages = !empty($_SESSION['manage_testimonials_success']) || !empty($_SESSION['manage_testimonials_errors']); ?>
        <?php $show_add_testimonial_messages = !empty($_SESSION['testimonial_success']) || !empty($_SESSION['testimonial_errors']); ?>
        <!--MANAGE TESTIMONIALS-->
        <details class="admin-accordion" <?= $show_manage_testimonials_messages ? 'open' : '' ?>>
            <summary>Manage Testimonials</summary>

            <?php if (!empty($_SESSION['manage_testimonials_success'])): ?>
                <p class="flash-success"><?= htmlspecialchars($_SESSION['manage_testimonials_success']) ?></p>
                <?php unset($_SESSION['manage_testimonials_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['manage_testimonials_errors'])): ?>
                <ul class="flash-errors">
                    <?php foreach ($_SESSION['manage_testimonials_errors'] as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['manage_testimonials_errors']); ?>
            <?php endif; ?>

            <?php $testimonials = getAllTestimonials($pdo); ?>
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-admin-row">
                    <p>
                        <strong>
                            <?= htmlspecialchars($testimonial['author_name']) ?>
                        </strong>: <?= htmlspecialchars($testimonial['content']) ?>

                    </p>



                    <form action="<?= BASE_URL ?>/?page=toggle_testimonial_controller" method="post">
                        <input type="hidden" name="testimonial_id" value="<?= htmlspecialchars($testimonial['id']) ?>">
                        <input type="hidden" name="toggle_field" value="is_featured">
                        <input type="submit" value="<?= $testimonial['is_featured'] ? 'Unfeature' : 'Featured' ?>">

                        </input>
                    </form>
                </div>
            <?php endforeach; ?>
        </details>

        <!--ADD NEW TESTIMONIAL-->
        <Details class="admin-accordion" <?= $show_add_testimonial_messages ? 'open' : '' ?>>
            <summary>Add new testimonial </summary>
            <?php if (!empty($_SESSION['testimonial_errors'])): ?>
                <ul class="flash-errors">
                    <?php foreach ($_SESSION['testimonial_errors'] as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>

                </ul>

                <?php unset($_SESSION['testimonial_errors']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['testimonial_success'])): ?>
                <p class="flash-success"><?= htmlspecialchars($_SESSION['testimonial_success']) ?></p>
                <?php unset($_SESSION['testimonial_success']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/?page=new_testimonial_controller" method="post">
                <label for="author_name">Client name</label>
                <input type="text" id="author_name" name="author_name" required>

                <label for="content">Testimonial</label>
                <textarea id="content" name="content" rows="4" required></textarea>
                <button type="submit">Add testimonial</button>
            </form>
        </Details>


        <?php $show_site_content_messages = !empty($_SESSION['site_content_errors']) || !empty($_SESSION['site_contentsuccess']); ?>
        <!--change site content-->

        

        <details class="admin-accordion" <?= $show_site_content_messages ? 'open' : '' ?>>
            <summary>Site Content</summary>

            <?php if (!empty($_SESSION['site_content_errors'])): ?>
                <ul class="flash-errors">
                    <?php foreach ($_SESSION['site_content_errors'] as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['site_content_errors']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['site_content_success'])): ?>
                <p class="flash-success"><?= htmlspecialchars($_SESSION['site_content_success']) ?></p>
                <?php unset($_SESSION['site_content_success']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/?page=update_site_content" method="POST"
                onsubmit="return confirm('Save changes to site content?');">
                <label for="experience_text">Experience</label>
                <textarea name="experience_text" id="experience_text"
                    rows="5"><?= htmlspecialchars($site_content['experience_text']) ?></textarea>

                <label for="skills_text">Skills</label>
                <textarea name="skills_text" id="skills_text"
                    rows="5"><?= htmlspecialchars($site_content['skills_text']) ?></textarea>

                <label for="is_available">
                    <input type="checkbox" name="is_available" <?= $site_content['is_available'] ? 'checked' : '' ?>
                        id="is_available">
                    Currently taking new clients
                </label>

                <button type="submit">Save changes</button>
            </form>
        </details>




    </div>
</section>