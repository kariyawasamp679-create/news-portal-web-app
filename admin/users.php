<?php
$pageTitle = 'Users';
require '_header.php';
if ($_SESSION['role'] !== 'admin') {
    die('<p>Access denied.</p>');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'add') {
            $u = trim($_POST['username'] ?? '');
            $em = trim($_POST['email'] ?? '');
            $pw = $_POST['password'] ?? '';
            $role = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor';
            if ($u === '' || !filter_var($em, FILTER_VALIDATE_EMAIL) || strlen($pw) < 6) {
                $error = 'Valid username, email and a password of at least 6 characters are required.';
            } else {
                $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?,?,?,?)')
                    ->execute([$u, $em, password_hash($pw, PASSWORD_DEFAULT), $role]);
                flash('User created.');
            }
        } elseif ($action === 'role') {
            $id = (int)$_POST['id'];
            if ($id === (int)$_SESSION['user_id']) {
                $error = 'You cannot change your own role.';
            } else {
                $role = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor';
                $pdo->prepare('UPDATE users SET role = ? WHERE id = ?')->execute([$role, $id]);
                flash('Role updated.');
            }
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            if ($id === (int)$_SESSION['user_id']) {
                $error = 'You cannot delete your own account.';
            } else {
                $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
                flash('User deleted (and their articles).');
            }
        }
        if (!$error) {
            header('Location: users.php');
            exit;
        }
    } catch (PDOException $ex) {
        $error = 'Username or email already exists.';
    }
}
$rows = $pdo->query('SELECT * FROM users ORDER BY id')->fetchAll();
?>
<h2 class="section-title">Manage Users</h2>
<?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="inline-form">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="hidden" name="action" value="add">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role"><option value="editor">Editor</option><option value="admin">Admin</option></select>
    <button class="btn">Add user</button>
</form>
<table class="table">
    <tr><th>#</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><?= (int)$r['id'] ?></td><td><?= e($r['username']) ?></td><td><?= e($r['email']) ?></td>
        <td>
            <form method="post" class="inline-form">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="role">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <select name="role" onchange="this.form.submit()">
                    <option value="editor" <?= $r['role'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?= $r['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </form>
        </td>
        <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
        <td class="actions">
            <?php if ((int)$r['id'] !== (int)$_SESSION['user_id']): ?>
            <form method="post" onsubmit="return confirm('Delete this user and all their articles?');">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <button class="link-danger">Delete</button>
            </form>
            <?php else: ?><small>(you)</small><?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require '_footer.php'; ?>
