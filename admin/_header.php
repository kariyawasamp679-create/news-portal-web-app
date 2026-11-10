<?php
require_once __DIR__ . '/../config.php';
if (!is_logged_in()) {
    header('Location: ../login.php');
    exit;
}
$cur = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Admin') ?> | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin">
<header class="site-header">
    <div class="container header-top">
        <a href="index.php" class="logo">Daily<span>Pulse</span> Admin</a>
        <div class="who">Hi, <?= e($_SESSION['username']) ?> · <a href="../index.php" target="_blank">View site</a> · <a href="../logout.php">Logout</a></div>
    </div>
    <nav class="main-nav"><div class="container">
        <a href="index.php" class="<?= $cur === 'index.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="articles.php" class="<?= in_array($cur, ['articles.php','article_form.php']) ? 'active' : '' ?>">Articles</a>
        <a href="categories.php" class="<?= $cur === 'categories.php' ? 'active' : '' ?>">Categories</a>
        <a href="comments.php" class="<?= $cur === 'comments.php' ? 'active' : '' ?>">Comments</a>
        <a href="messages.php" class="<?= $cur === 'messages.php' ? 'active' : '' ?>">Messages</a>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="users.php" class="<?= $cur === 'users.php' ? 'active' : '' ?>">Users</a>
        <?php endif; ?>
    </div></nav>
</header>
<main class="container">
<?php if ($m = flash()): ?><div class="alert ok"><?= e($m) ?></div><?php endif; ?>
