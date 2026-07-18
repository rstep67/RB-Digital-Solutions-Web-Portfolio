<?php

function get_portfolio_entries ($pdo) {
    $stmt=$pdo -> prepare('SELECT id,title,description,media_url,created_at FROM portfolio_entries ORDER BY id ASC');
    $stmt -> execute();
    return $stmt -> fetchAll();
}

function get_portfolio_entry_by_id($pdo,$id) {
    $stmt = $pdo -> prepare('SELECT id,title,description,media_url,created_at FROM portfolio_entries WHERE id = ?');
    $stmt -> execute([$id]);
    return $stmt -> fetch();

}