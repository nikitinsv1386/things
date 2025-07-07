<?php
require_once 'includes/db.php';

// return single card for AJAX
if (isset($_GET['card']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM items WHERE id=?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if ($item) {
        include 'includes/item_card.php';
    }
    exit;
}

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
    <a href="add.php" class="btn btn-primary" data-modal>Добавить вещь</a>
</div>
<?php if (count($items) > 0): ?>
<div class="row">
<?php foreach ($items as $item): ?>
    <?php include 'includes/item_card.php'; ?>
<?php endforeach; ?>
</div>
<?php else: ?>
<p>Ничего не найдено.</p>
<?php endif; ?>
<?php
require_once 'includes/footer.php';
?>
