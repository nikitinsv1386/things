<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Search functionality
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $pdo->prepare('SELECT * FROM items WHERE name LIKE ? ORDER BY date_added DESC');
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query('SELECT * FROM items ORDER BY date_added DESC');
}
$items = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between mb-3">
    <form class="d-flex" method="get" action="index.php">
        <input class="form-control me-2" type="search" placeholder="Поиск" name="search" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-secondary" type="submit">Найти</button>
    </form>
    <a href="add.php" class="btn btn-primary">Добавить вещь</a>
</div>
<?php if (count($items) > 0): ?>
<div class="row">
<?php foreach ($items as $item): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <?php if ($item['image_path']): ?>
            <img src="<?= htmlspecialchars($item['image_path']) ?>" class="card-img-top" alt="Фото">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                <p class="card-text"><strong>Категория:</strong> <?= htmlspecialchars($item['category']) ?></p>
                <p class="card-text"><strong>Местоположение:</strong> <?= htmlspecialchars($item['location']) ?></p>
                <a href="view.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary">Подробнее</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php else: ?>
<p>Ничего не найдено.</p>
<?php endif; ?>
<?php
require_once 'includes/footer.php';
?>
