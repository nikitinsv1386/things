<tr data-id="<?= (int)$user['id'] ?>">
  <td><?= htmlspecialchars($user['name']) ?></td>
  <td><?= htmlspecialchars($user['email']) ?></td>
  <td><?= htmlspecialchars($user['role']) ?></td>
  <td class="text-nowrap">
    <button type="button" class="btn btn-sm btn-secondary edit-user"
      data-id="<?= (int)$user['id'] ?>"
      data-name="<?= htmlspecialchars($user['name']) ?>"
      data-email="<?= htmlspecialchars($user['email']) ?>"
      data-role="<?= htmlspecialchars($user['role']) ?>">
      Ред.
    </button>
    <a href="users.php?delete=<?= (int)$user['id'] ?>" class="btn btn-sm btn-danger delete-user">Удалить</a>
  </td>
</tr>
