<?php
$pageTitle = 'Home';
require 'includes/header.php';

$sql = 'SELECT a.*, c.name AS category_name FROM articles a JOIN categories c ON c.id = a.category_id ';
$featured = $pdo->query($sql . 'WHERE a.is_featured = 1 ORDER BY a.created_at DESC, a.id DESC LIMIT 3')->fetchAll();
$latest = $pdo->query($sql . 'ORDER BY a.created_at DESC, a.id DESC LIMIT 9')->fetchAll();
$popular = $pdo->query($sql . 'ORDER BY a.views DESC LIMIT 5')->fetchAll();
?>
<?php if ($featured): ?>
<section class="featured">
    <h2 class="section-title">Top Stories</h2>
    <div class="grid grid-3">
        <?php foreach ($featured as $a) include 'includes/card.php'; ?>
    </div>
</section>
<?php endif; ?>

<div class="layout">
    <section>
        <h2 class="section-title">Latest News</h2>
        <div class="grid grid-2">
            <?php foreach ($latest as $a) include 'includes/card.php'; ?>
            <?php if (!$latest): ?><p>No articles yet.</p><?php endif; ?>
        </div>
    </section>
    <aside class="sidebar">
        <h2 class="section-title">Most Read</h2>
        <ol class="popular">
            <?php foreach ($popular as $p): ?>
                <li><a href="article.php?id=<?= (int)$p['id'] ?>"><?= e($p['title']) ?></a> <small>(<?= (int)$p['views'] ?>)</small></li>
            <?php endforeach; ?>
        </ol>
    </aside>
</div>
<?php require 'includes/footer.php'; ?>
