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
    header('Location: categories.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id=?');
    $stmt->execute([$id]);
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
<form method="post" class="mb-4">
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
  <tbody>
  <?php foreach ($categories as $cat): ?>
    <tr>
      <td><?= htmlspecialchars($cat['name']) ?></td>
      <td><?= htmlspecialchars($cat['location']) ?></td>
      <td>
        <a href="categories.php?edit=<?= $cat['id'] ?>" class="btn btn-sm btn-secondary">Ред.</a>
        <a href="categories.php?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?');">Удалить</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php
require_once 'includes/footer.php';
?>
