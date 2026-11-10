<?php
$pageTitle = 'Search';
require 'includes/header.php';
$q = trim($_GET['q'] ?? '');
$rows = [];
if ($q !== '') {
    $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';
    $stmt = $pdo->prepare('SELECT a.*, c.name AS category_name FROM articles a JOIN categories c ON c.id = a.category_id WHERE a.title LIKE ? OR a.summary LIKE ? OR a.content LIKE ? ORDER BY a.created_at DESC');
    $stmt->execute([$like, $like, $like]);
    $rows = $stmt->fetchAll();
}
?>
<h2 class="section-title">Search results for "<?= e($q) ?>" (<?= count($rows) ?>)</h2>
<div class="grid grid-3">
    <?php foreach ($rows as $a) include 'includes/card.php'; ?>
</div>
<?php if (!$rows): ?><p>No results found.</p><?php endif; ?>
<?php require 'includes/footer.php'; ?>
