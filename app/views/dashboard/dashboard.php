<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}
require_once __DIR__ . '/../../models/document_model.php';

$stmt = $pdo->prepare('SELECT id, title, description, status, updated_at FROM projects     WHERE user_id =? ORDER BY updated_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$my_projects = $stmt->fetchAll();

?>

<section class="dash-section">
    <div class="container">
        <h1> Client Dashboard </h1>
        <p>
            Logged in as <?= htmlspecialchars($_SESSION['full_name']) ?>
            (<?= htmlspecialchars($_SESSION['role']) ?>).
        </p>

        <section id="my-projects">
            <h2>My Projects</h2>

            <?php if (empty($my_projects)): ?>
                <p>You don't have any projects yet</p>

            <?php else: ?>
                <?php foreach ($my_projects as $project): ?>
                    <?php $project_documents = getDocumentsByProjectID($pdo, $project['id']); ?>
                    <details class="client-project-accordion">
                        <summary><?= htmlspecialchars($project['title']) ?>         
                        <span class="project-status-badge"><?= htmlspecialchars($project['status']) ?>
                        </summary>
                        <p style="white-space: pre-line;"><?= htmlspecialchars($project['description']) ?></p>
                        <p><small>last updated at: <?= htmlspecialchars($project['updated_at']) ?></small></p>

                        <h4>files</h4>
                        <?php if (empty($project_documents)): ?>
                            <p>No files have been uploaded yet </p>
                        <?php else: ?>
                            <ul>
                                <?php foreach ($project_documents as $doc): ?>
                                    <li>
                                        <a href="<?= BASE_URL ?>/?page=download_document&id=<?= htmlspecialchars($doc['id']) ?>">
                                            <?= htmlspecialchars($doc['file_name']) ?>

                                        </a>
                                        <small>(uploaded <?= htmlspecialchars($doc['uploaded_at']) ?>)</small>
                                    </li>

                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </details>


                <?php endforeach; ?>
            <?php endif; ?>


        </section>


    </div>