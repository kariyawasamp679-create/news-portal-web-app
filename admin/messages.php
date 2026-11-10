<?php
$pageTitle = 'Messages';
require '_header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    csrf_check();
    $pdo->prepare('DELETE FROM messages WHERE id = ?')->execute([(int)$_POST['delete_id']]);
    flash('Message deleted.');
    header('Location: messages.php');
    exit;
}
$rows = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC, id DESC')->fetchAll();
?>
<h2 class="section-title">Contact Messages</h2>
<table class="table">
    <tr><th>From</th><th>Subject</th><th>Message</th><th>Date</th><th>Action</th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><?= e($r['name']) ?><br><small><a href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></small></td>
        <td><?= e($r['subject']) ?></td>
        <td><?= nl2br(e($r['message'])) ?></td>
        <td><?= date('M d, Y H:i', strtotime($r['created_at'])) ?></td>
        <td class="actions"><form method="post" onsubmit="return confirm('Delete this message?');">
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
            <button class="link-danger">Delete</button></form></td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="5">No messages yet.</td></tr><?php endif; ?>
</table>
<?php require '_footer.php'; ?>
