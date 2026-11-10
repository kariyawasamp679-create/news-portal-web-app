<?php
$pageTitle = 'Articles';
require '_header.php';

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_check();
    $st = $pdo->prepare('SELECT image FROM articles WHERE id = ?');
    $st->execute([(int)$_POST['delete_id']]);
    $img = $st->fetchColumn();
    $pdo->prepare('DELETE FROM articles WHERE id = ?')->execute([(int)$_POST['delete_id']]);
    if ($img && is_file(__DIR__ . '/../uploads/' . $img)) {
        unlink(__DIR__ . '/../uploads/' . $img);
    }
    flash('Article deleted.');
    header('Location: articles.php');
    exit;
}

// READ
$rows = $pdo->query('SELECT a.id, a.title, a.is_featured, a.views, a.created_at, c.name AS cat, u.username FROM articles a JOIN categories c ON c.id = a.category_id JOIN users u ON u.id = a.author_id ORDER BY a.created_at DESC, a.id DESC')->fetchAll();
?>
<h2 class="section-title">Manage Articles</h2>
<p><a class="btn" href="article_form.php">+ Add new article</a></p>
<table class="table">
    <tr><th>#</th><th>Title</th><th>Category</th><th>Author</th><th>Featured</th><th>Views</th><th>Date</th><th>Actions</th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?= e($r['title']) ?></td>
        <td><?= e($r['cat']) ?></td>
        <td><?= e($r['username']) ?></td>
        <td><?= $r['is_featured'] ? 'Yes' : 'No' ?></td>
        <td><?= (int)$r['views'] ?></td>
        <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
        <td class="actions">
            <a href="../article.php?id=<?= (int)$r['id'] ?>" target="_blank">View</a>
            <a href="article_form.php?id=<?= (int)$r['id'] ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Delete this article?');">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
                <button class="link-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="8">No articles yet.</td></tr><?php endif; ?>
</table>
<?php require '_footer.php'; ?>
