<?php
require 'config.php';
if (is_logged_in()) {
    header('Location: admin/index.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: admin/index.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
$pageTitle = 'Admin Login';
require 'includes/header.php';
?>
<h2 class="section-title">Admin Login</h2>
<div class="single narrow">
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <label>Username <input type="text" name="username" required autofocus></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit" class="btn">Log in</button>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
