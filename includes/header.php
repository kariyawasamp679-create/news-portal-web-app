<?php
require_once __DIR__ . '/../config.php';
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-top">
        <a href="index.php" class="logo">Daily<span>Pulse</span></a>
        <form action="search.php" method="get" class="search-form">
            <input type="text" name="q" placeholder="Search news..." value="<?= e($_GET['q'] ?? '') ?>" required>
            <button type="submit">Search</button>
        </form>
    </div>
    <nav class="main-nav">
        <div class="container">
            <a href="index.php">Home</a>
            <?php foreach ($cats as $c): ?>
                <a href="category.php?slug=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
            <?php endforeach; ?>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a href="login.php" class="admin-link">Admin</a>
        </div>
    </nav>
</header>
<main class="container">
