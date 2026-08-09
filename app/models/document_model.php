<?php

function getDocumentByID($pdo, $document_id) {
    $stmt = $pdo ->prepare("SELECT documents.id, documents.file_name, documents.file_path, projects.user_id FROM documents JOIN projects ON documents.project_id = projects.id WHERE documents.id = ?");
    $stmt ->execute([$document_id]);
    return $stmt ->fetch();

}

function getDocumentsByProjectID($pdo, $project_id) {
    $stmt = $pdo ->prepare('SELECT id, file_name, uploaded_at FROM documents WHERE project_id =? ORDER BY uploaded_at DESC');
    $stmt ->execute([$project_id]);
    return $stmt ->fetchAll();
}

//delete documents - returns all documents with project title and client name
function getAllDocumentsWithProject($pdo) {
    $stmt = $pdo ->prepare('SELECT documents.id, documents.file_name, documents.uploaded_at, projects.title AS project_title, users.full_name
        FROM documents
        
        JOIN projects ON documents.project_id = projects.id
        JOIN users ON projects.user_id = users.id
        ORDER BY documents.uploaded_at DESC');
    $stmt ->execute();
    return $stmt ->fetchAll();
}
