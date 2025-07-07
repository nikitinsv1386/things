<?php
require_once 'includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM items WHERE id = ?');
    $stmt->execute([$id]);
}
if (isset($_GET['ajax'])) {
    echo json_encode(['success' => true]);
    exit;
}
header('Location: index.php');
exit;
