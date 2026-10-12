<?php
// AJAX endpoint used by the rich-text editor's image button. Returns JSON {url: "..."}.
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');
if (!current_user()) {
    http_response_code(401);
    exit(json_encode(['error' => 'Please log in again.']));
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'POST only.']));
}
csrf_check();
try {
    $name = store_image($_FILES['image'] ?? []);
    echo json_encode(['url' => upload_url($name)]);
} catch (RuntimeException $ex) {
    http_response_code(422);
    echo json_encode(['error' => $ex->getMessage()]);
}
