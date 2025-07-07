<div class="col-md-4 mb-4" data-id="<?= $item['id'] ?>">
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
