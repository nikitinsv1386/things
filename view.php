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
<div class="row">
  <div class="col-md-6">
    <?php if ($item['image_path']): ?>
    <img src="<?= htmlspecialchars($item['image_path']) ?>" class="img-fluid" alt="Фото">
    <?php endif; ?>
  </div>
  <div class="col-md-6">
    <h2><?= htmlspecialchars($item['name']) ?></h2>
    <p><strong>Категория:</strong> <?= htmlspecialchars($item['category']) ?></p>
    <p><strong>Местоположение:</strong> <?= htmlspecialchars($item['location']) ?></p>
    <p><strong>Комментарий:</strong> <?= nl2br(htmlspecialchars($item['note'])) ?></p>
    <p><strong>Дата добавления:</strong> <?= $item['date_added'] ?></p>
    <?php if ($item['qr_code']): ?>
    <p><img src="<?= htmlspecialchars($item['qr_code']) ?>" alt="QR"></p>
    <?php endif; ?>
    <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-primary" data-modal>Редактировать</a>
    <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-danger delete-link">Удалить</a>
  </div>
</div>
<?php
require_once 'includes/footer.php';
?>
