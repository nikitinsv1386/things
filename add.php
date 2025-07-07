<?php
require_once 'includes/header.php';
?>
<h2>Добавить вещь</h2>
<form class="needs-validation" novalidate method="post" action="save.php" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="name" class="form-label">Название</label>
    <input type="text" class="form-control" id="name" name="name" required>
  </div>
  <div class="mb-3">
    <label for="category" class="form-label">Категория</label>
    <input type="text" class="form-control" id="category" name="category">
  </div>
  <div class="mb-3">
    <label for="location" class="form-label">Местоположение</label>
    <input type="text" class="form-control" id="location" name="location">
  </div>
  <div class="mb-3">
    <label for="note" class="form-label">Комментарий</label>
    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
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
