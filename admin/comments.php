<?php
$pageTitle = 'Comments';
require '_header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_check();
    $pdo->prepare('DELETE FROM comments WHERE id = ?')->execute([(int)$_POST['delete_id']]);
    flash('Comment deleted.');
    header('Location: comments.php');
    exit;
}
$rows = $pdo->query('SELECT c.*, a.title FROM comments c JOIN articles a ON a.id = c.article_id ORDER BY c.created_at DESC, c.id DESC')->fetchAll();
?>
<h2 class="section-title">Moderate Comments</h2>
<table class="table">
    <tr><th>Article</th><th>Name</th><th>Comment</th><th>Date</th><th>Action</th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><a href="../article.php?id=<?= (int)$r['article_id'] ?>" target="_blank"><?= e($r['title']) ?></a></td>
        <td><?= e($r['name']) ?></td>
        <td><?= nl2br(e($r['body'])) ?></td>
        <td><?= date('M d, Y H:i', strtotime($r['created_at'])) ?></td>
        <td class="actions"><form method="post" onsubmit="return confirm('Delete this comment?');">
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
            <button class="link-danger">Delete</button></form></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="5">No comments.</td></tr><?php endif; ?>
</table>
<?php require '_footer.php'; ?>
