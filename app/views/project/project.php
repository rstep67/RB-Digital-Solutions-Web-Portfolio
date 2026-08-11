<?php


if (!$entry) {
    header('location: ' . BASE_URL . '/?page=homepage');
    exit;
}
?>

<section class="project-detail-section">
    
    <div class="container">
        <a href="<?= BASE_URL ?>/?page=homepage" class ="backlink"> &larr; Back to Projects</a>

        <?php if (!empty ($entry['media_url'])): ?>
            <img src="<?=htmlspecialchars($entry['media_url']) ?>" alt="<?=htmlspecialchars($entry['title']) ?>" class="project-detail-image" loading="lazy">
            <?php endif; ?>

            <h1> <?=htmlspecialchars($entry['title'])?></h1>
            <p><?=htmlspecialchars($entry['description'])?></p>
        
            <?php if (!empty($gallery_images)): ?>
            <div class="project-gallery">
                <?php foreach ($gallery_images as $image_url):?>
                    <img src="<?=htmlspecialchars($image_url)?>" alt="<?=htmlspecialchars($entry['title']) ?> screenshot" class="project-gallery-image" loading="lazy">
                    <?php endforeach;?>
            </div>
            <?php endif;?>
    </div>
</section>