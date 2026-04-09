<?php
require_once 'includes/db.php';

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        role VARCHAR(100) DEFAULT "Пользователь",
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'Пользователь');

    if ($name === '' || $email === '') {
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Заполните имя и email']);
            exit;
        }
        header('Location: users.php');
        exit;
    }

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?');
            $stmt->execute([$name, $email, $role, $id]);
            $userId = $id;
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, role) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $role]);
            $userId = (int)$pdo->lastInsertId();
        }

        if (isset($_POST['ajax'])) {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            ob_start();
            include 'includes/user_row.php';
            $rowHtml = ob_get_clean();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'id' => $userId, 'rowHtml' => $rowHtml]);
            exit;
        }

        header('Location: users.php');
        exit;
    } catch (PDOException $e) {
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Не удалось сохранить пользователя']);
            exit;
        }
        header('Location: users.php');
        exit;
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$id]);

    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'id' => $id]);
        exit;
    }

    header('Location: users.php');
    exit;
}

require_once 'includes/header.php';
$users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
?>
<h2 class="mb-3">Пользователи</h2>
<form method="post" action="users.php" class="users-form mb-4" id="usersForm">
    <input type="hidden" name="id" id="user_id" value="">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label" for="user_name">Имя</label>
            <input id="user_name" type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_email">Email</label>
            <input id="user_email" type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="user_role">Роль</label>
            <input id="user_role" type="text" name="role" class="form-control" value="Пользователь">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="user_submit">Сохранить</button>
            <button type="button" class="btn btn-outline-secondary" id="user_cancel" hidden>Отмена</button>
        </div>
    </div>
</form>

<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody id="usersBody">
        <?php foreach ($users as $user): ?>
            <?php include 'includes/user_row.php'; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once 'includes/footer.php'; ?>
