<?php
$pageTitle = 'Dashboard';
require '_header.php';
$count = fn($t) => (int)$pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
$stats = [
    'Articles' => $count('articles'), 'Categories' => $count('categories'),
    'Comments' => $count('comments'), 'Messages' => $count('messages'), 'Users' => $count('users'),
];
$recent = $pdo->query('SELECT a.id, a.title, a.views, a.created_at, c.name AS cat FROM articles a JOIN categories c ON c.id = a.category_id ORDER BY a.created_at DESC, a.id DESC LIMIT 5')->fetchAll();
?>
<h2 class="section-title">Dashboard</h2>
<div class="stats">
    <?php foreach ($stats as $label => $n): ?>
        <div class="stat"><strong><?= $n ?></strong><span><?= $label ?></span></div>
    <?php endforeach; ?>
</div>
<p><a class="btn" href="article_form.php">+ Add new article</a></p>
<h3>Recent articles</h3>
<table class="table">
    <tr><th>Title</th><th>Category</th><th>Views</th><th>Date</th></tr>
    <?php foreach ($recent as $r): ?>
        <tr><td><?= e($r['title']) ?></td><td><?= e($r['cat']) ?></td><td><?= (int)$r['views'] ?></td><td><?= date('M d, Y', strtotime($r['created_at'])) ?></td></tr>
    <?php endforeach; ?>
</table>
<?php require '_footer.php'; ?>
