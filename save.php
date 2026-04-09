<?php
// Simple handler for saving an item.
// Normally you would save to a database and handle uploads securely.
$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? '';
$existingImage = $_POST['existing_image'] ?? '';

$imagePath = $existingImage;

if (!empty($_FILES['image']['name'])) {
    // Example logic to move uploaded file.
    $uploadDir = 'uploads/';
    $filename = basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $filename;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
        $imagePath = $targetPath;
    }
}

// For demo purposes output what would be saved.
echo "ID: " . htmlspecialchars($id) . "<br>";
echo "Name: " . htmlspecialchars($name) . "<br>";
echo "Image path: " . htmlspecialchars($imagePath);
?>
