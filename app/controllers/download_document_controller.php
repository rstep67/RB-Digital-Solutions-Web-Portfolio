<?php
require_once __DIR__ . '/../models/document_model.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('location: ' . BASE_URL . '/?page=login');
    exit;
}

$document_id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

if(empty($document_id)) {
    die('invalid document requested');
}
$document = getDocumentByID($pdo,$document_id);

if(!$document) {
    die('document notn found');
}

//check if admin or client whos id matches 
$is_admin = ($_SESSION['role'] === 'admin');
$is_owner = ($_SESSION['role'] === 'client' && $_SESSION['user_id'] == $document['user_id']);
if (!$is_admin && !$is_owner) {
    die ('you dont have permission to access this file');
}

$full_path = __DIR__ . '/../../' . $document['file_path'];
if(!file_exists($full_path)) {
    die('file not on server');
}

//map extensions to content type
$mime_types = [
    'jpg' =>'image/jpeg',
    'jpeg' =>'image/jpeg',
    'png' =>'image/png',
    'gif' =>'image/gif',
    'pdf' =>'application/pdf',
    'doc' =>'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

//make lowercase 
$extension = strtolower(pathinfo($full_path, PATHINFO_EXTENSION));
//octet stream fallback in case file type isnt mapped still download it
/**
 * MIME types (IANA media types) - HTTP | MDN. (2025). [online] MDN Web Docs. 
 * Available at: https://developer.mozilla.org/en-US/docs/Web/HTTP/Guides/MIME_types 
 */
$content_type = $mime_types[$extension] ?? 'application/octet-stream';

header('content-type: ' . $content_type);
/**
 * Content-Disposition - HTTP | MDN. (2025). [online] MDN Web Docs.
 *  Available at: https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Content-Disposition [Accessed 14 Aug. 2026].
 */
header('content-disposition: attachment;filename="' . basename($document['file_name']) . '"');
header('content-length: ' . filesize($full_path));
readfile($full_path);
exit;
?>