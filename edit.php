<?php
// Simple edit form for an item.
// In a real application $item would come from a database.
$item = [
    'id' => 1,
    'name' => 'Sample Item',
    'image_path' => 'uploads/sample.jpg'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
</head>
<body>
<form action="save.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
    <label>
        Name:
        <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>">
    </label>
    <!-- Hidden field to store the existing image path -->
    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($item['image_path']); ?>">
    <label>
        Image:
        <input type="file" name="image">
    </label>
    <button type="submit">Save</button>
</form>
</body>
</html>
