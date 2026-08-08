<?php 

function getSiteContent($pdo) {
    $stmt = $pdo->prepare('SELECT experience_text, skills_text, is_available FROM site_content WHERE id=1');
    $stmt ->execute();
    return $stmt->fetch();
}

function updateSiteContent($pdo, $experience_text, $skills_text, $is_available) {
    $stmt = $pdo ->prepare('UPDATE site_content SET experience_text = ?, skills_text = ?, is_available = ? WHERE id=1');
    $stmt ->execute([$experience_text, $skills_text, $is_available]);
}