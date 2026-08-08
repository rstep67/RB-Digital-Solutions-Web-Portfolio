<?php

function getPortfolioEntries ($pdo) {
    $stmt=$pdo -> prepare('SELECT id,title,description,media_url,created_at FROM portfolio_entries ORDER BY id ASC');
    $stmt -> execute();
    return $stmt -> fetchAll();
}

function getPortfolioEntryById($pdo,$id) {
    $stmt = $pdo -> prepare('SELECT id,title,description,media_url,created_at FROM portfolio_entries WHERE id = ?');
    $stmt -> execute([$id]);
    return $stmt -> fetch();

}

function getPortfolioMedia($pdo, $entry_id) {
    $stmt = $pdo->prepare("SELECT media_url FROM portfolio_media WHERE entry_id = ? ORDER BY display_order ASC");
    $stmt ->execute([$entry_id]);
    return $stmt ->fetchAll(PDO::FETCH_COLUMN);
}