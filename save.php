<?php
require_once 'includes/db.php';
require_once 'includes/phpqrcode.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$name = $_POST['name'] ?? '';
$category = $_POST['category'] ?? '';
$location = $_POST['location'] ?? '';
$note = $_POST['note'] ?? '';

// handle image upload
$imagePath = null;
if (!empty($_FILES['image']['name'])) {
    $targetDir = 'uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $filename = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $filename;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    }
}

if ($id > 0) {
    // update existing
    $stmt = $pdo->prepare('UPDATE items SET name=?, category=?, location=?, note=?, image_path=? WHERE id=?');
    $stmt->execute([$name, $category, $location, $note, $imagePath ?? $_POST['existing_image'], $id]);
    $itemId = $id;
} else {
    // insert
    $stmt = $pdo->prepare('INSERT INTO items (name, category, location, note, image_path) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $category, $location, $note, $imagePath]);
    $itemId = $pdo->lastInsertId();
}

// generate QR code
$qrDir = 'qr/';
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0777, true);
}
$qrPath = $qrDir . $itemId . '.png';
$qrUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/view.php?id=$itemId";
QRcode::png($qrUrl, $qrPath, QR_ECLEVEL_L, 4);

$stmt = $pdo->prepare('UPDATE items SET qr_code=? WHERE id=?');
$stmt->execute([$qrPath, $itemId]);

if (isset($_POST['ajax'])) {
    echo json_encode(['success' => true, 'id' => $itemId, 'qr' => $qrPath]);
    exit;
}

header('Location: view.php?id=' . $itemId);
exit;
