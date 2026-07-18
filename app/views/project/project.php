<?php
$entry_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$entry_id) {
    header('location: ' . BASE_URL . '/?page=homepage');
    exit;

}

require_once __DIR__ . '/../../models/portfolio_model.php';
$entry = get_portfolio_entry_by_id($pdo,$entry_id);

if (!$entry) {
    header('location: ' . BASE_URL . '/?page=homepage');
    exit;
}
?>

<section class="project-detail-section">
    div class
    <div class="container">
        <a href="<?= BASE_URL ?>/?page=homepage" class ="backlink"> &larr; Back to Projects</a>

        <?php if (!empty ($entry['media_url'])): ?>
            <img src="<?=htmlspecialchars($entry['media_url']) ?>" alt="<?=htmlspecialchars($entry['title']) ?>" class="project-detail-image">
            <?php endif; ?>

            <h1> <?=htmlspecialchars($entry['title'])?></h1>
            <p><?=htmlspecialchars($entry['description'])?></p>
    </div>
</section>