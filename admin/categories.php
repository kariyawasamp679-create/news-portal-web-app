<?php
$pageTitle = 'Categories';
require '_header.php';
$error = '';
$editId = (int)($_GET['edit'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $name = trim($_POST['name'] ?? '');
    try {
        if ($action === 'add' && $name !== '') {
            $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)')->execute([$name, slugify($name)]);
            flash('Category added.');
        } elseif ($action === 'update' && $name !== '') {
            $pdo->prepare('UPDATE categories SET name = ?, slug = ? WHERE id = ?')->execute([$name, slugify($name), (int)$_POST['id']]);
            flash('Category updated.');
        } elseif ($action === 'delete') {
            $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([(int)$_POST['id']]);
            flash('Category deleted (its articles were removed too).');
        } else {
            $error = 'Category name is required.';
        }
        if (!$error) {
            header('Location: categories.php');
            exit;
        }
    } catch (PDOException $ex) {
        $error = 'That category name already exists.';
    }
}
$rows = $pdo->query('SELECT c.*, (SELECT COUNT(*) FROM articles a WHERE a.category_id = c.id) AS n FROM categories c ORDER BY c.name')->fetchAll();
?>
<h2 class="section-title">Manage Categories</h2>
<?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="inline-form">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="action" value="add">
    <input type="text" name="name" placeholder="New category name" required>
    <button class="btn">Add</button>
</form>
<table class="table">
    <tr><th>#</th><th>Name</th><th>Slug</th><th>Articles</th><th>Actions</th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><?= (int)$r['id'] ?></td>
        <?php if ($editId === (int)$r['id']): ?>
            <td colspan="3">
                <form method="post" class="inline-form">
                    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <input type="text" name="name" value="<?= e($r['name']) ?>" required>
                    <button class="btn">Save</button> <a href="categories.php">Cancel</a>
                </form>
            </td>
            <td></td>
        <?php else: ?>
            <td><?= e($r['name']) ?></td><td><?= e($r['slug']) ?></td><td><?= (int)$r['n'] ?></td>
            <td class="actions">
                <a href="categories.php?edit=<?= (int)$r['id'] ?>">Edit</a>
                <form method="post" onsubmit="return confirm('Delete this category and ALL its articles?');">
                    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <button class="link-danger">Delete</button>
                </form>
            </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>
<?php require '_footer.php'; ?>
