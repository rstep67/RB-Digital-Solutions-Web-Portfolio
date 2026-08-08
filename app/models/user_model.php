<?php 

function getAllClients($pdo) {
    $stmt = $pdo ->prepare('SELECT id,full_name,email,is_active FROM users WHERE role = ? ORDER BY full_name ASC');
    $stmt ->execute(['client']);
    return $stmt ->fetchAll();
}

function getClientById($pdo,$id) {
    $stmt = $pdo ->prepare('SELECT id, full_name, email, is_active FROM users WHERE id = ? AND role = ?');
    $stmt ->execute([$id,'client']);
    return $stmt ->fetch();
}