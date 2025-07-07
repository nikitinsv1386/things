<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM items WHERE id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) {
    echo '<p>Вещь не найдена</p>';
    require 'includes/footer.php';
    exit;
}
?>
<h2>Редактировать вещь</h2>
<form class="needs-validation" novalidate method="post" action="save.php?id=<?= $item['id'] ?>" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="name" class="form-label">Название</label>
    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="category" class="form-label">Категория</label>
    <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($item['category']) ?>">
  </div>
  <div class="mb-3">
    <label for="location" class="form-label">Местоположение</label>
    <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($item['location']) ?>">
  </div>
  <div class="mb-3">
    <label for="note" class="form-label">Комментарий</label>
    <textarea class="form-control" id="note" name="note" rows="3"><?= htmlspecialchars($item['note']) ?></textarea>
  </div>
  <div class="mb-3">
    <label for="image" class="form-label">Фото</label>
    <input class="form-control" type="file" id="image" name="image">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
<?php
require_once 'includes/footer.php';
?>
