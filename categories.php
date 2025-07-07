<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE categories SET name=?, location=? WHERE id=?');
        $stmt->execute([$name, $location, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO categories (name, location) VALUES (?, ?)');
        $stmt->execute([$name, $location]);
    }
    if (isset($_POST['ajax'])) {
        echo json_encode(['success' => true]);
        exit;
    }
    header('Location: categories.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id=?');
    $stmt->execute([$id]);
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => true]);
        exit;
    }
    header('Location: categories.php');
    exit;
}

$editCategory = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id=?');
    $stmt->execute([$id]);
    $editCategory = $stmt->fetch();
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
?>
<h2>Категории</h2>
<form method="post" class="mb-4 ajax-form" action="categories.php">
    <input type="hidden" name="id" value="<?= $editCategory['id'] ?? '' ?>">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Название</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Локация</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($editCategory['location'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary"><?= $editCategory ? 'Сохранить' : 'Добавить' ?></button>
        </div>
    </div>
</form>
<table class="table table-striped">
  <thead>
    <tr><th>Название</th><th>Локация</th><th></th></tr>
  </thead>
  <tbody id="catBody">
  <?php foreach ($categories as $cat): ?>
    <tr data-id="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['name']) ?>" data-location="<?= htmlspecialchars($cat['location']) ?>">
      <td><?= htmlspecialchars($cat['name']) ?></td>
      <td><?= htmlspecialchars($cat['location']) ?></td>
      <td>
        <button type="button" class="btn btn-sm btn-secondary edit-category">Ред.</button>
        <a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger delete-link">Удалить</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php
require_once 'includes/footer.php';
?>
