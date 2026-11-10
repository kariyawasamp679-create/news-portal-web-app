<?php
require 'config.php';
$stmt = $pdo->prepare('SELECT * FROM categories WHERE slug = ?');
$stmt->execute([$_GET['slug'] ?? '']);
$cat = $stmt->fetch();
if (!$cat) {
    http_response_code(404);
}
$pageTitle = $cat ? $cat['name'] : 'Not found';
require 'includes/header.php';

if (!$cat) {
    echo '<p>Category not found.</p>';
} else {
    $stmt = $pdo->prepare('SELECT a.*, c.name AS category_name FROM articles a JOIN categories c ON c.id = a.category_id WHERE a.category_id = ? ORDER BY a.created_at DESC, a.id DESC');
    $stmt->execute([$cat['id']]);
    $rows = $stmt->fetchAll();
    echo '<h2 class="section-title">' . e($cat['name']) . '</h2><div class="grid grid-3">';
    foreach ($rows as $a) include 'includes/card.php';
    echo '</div>';
    if (!$rows) echo '<p>No articles in this category yet.</p>';
}
require 'includes/footer.php';
