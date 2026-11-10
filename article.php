<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT a.*, c.name AS category_name, c.slug, u.username FROM articles a JOIN categories c ON c.id = a.category_id JOIN users u ON u.id = a.author_id WHERE a.id = ?');
$stmt->execute([$id]);
$article = $stmt->fetch();

$error = '';
if ($article && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if ($name === '' || $body === '') {
        $error = 'Please enter your name and comment.';
    } else {
        // CREATE (comment)
        $pdo->prepare('INSERT INTO comments (article_id, name, body) VALUES (?, ?, ?)')->execute([$id, mb_substr($name, 0, 80), $body]);
        flash('Thanks! Your comment was posted.');
        header('Location: article.php?id=' . $id . '#comments');
        exit;
    }
} elseif ($article) {
    // UPDATE (view counter)
    $pdo->prepare('UPDATE articles SET views = views + 1 WHERE id = ?')->execute([$id]);
    $article['views']++;
}

$pageTitle = $article ? $article['title'] : 'Not found';
require 'includes/header.php';

if (!$article) {
    http_response_code(404);
    echo '<p>Article not found.</p>';
    require 'includes/footer.php';
    exit;
}
$cm = $pdo->prepare('SELECT * FROM comments WHERE article_id = ? ORDER BY created_at DESC, id DESC');
$cm->execute([$id]);
$comments = $cm->fetchAll();
$msg = flash();
?>
<article class="single">
    <span class="tag"><a href="category.php?slug=<?= e($article['slug']) ?>"><?= e($article['category_name']) ?></a></span>
    <h1><?= e($article['title']) ?></h1>
    <p class="meta">By <?= e($article['username']) ?> · <?= date('F d, Y', strtotime($article['created_at'])) ?> · <?= (int)$article['views'] ?> views</p>
    <?php if ($article['image']): ?><img class="hero" src="uploads/<?= e($article['image']) ?>" alt=""><?php endif; ?>
    <p class="lead"><?= e($article['summary']) ?></p>
    <div class="body"><?= nl2br(e($article['content'])) ?></div>
</article>

<section id="comments" class="comments">
    <h2 class="section-title">Comments (<?= count($comments) ?>)</h2>
    <?php if ($msg): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <label>Name <input type="text" name="name" maxlength="80" required></label>
        <label>Comment <textarea name="body" rows="3" required></textarea></label>
        <button type="submit" class="btn">Post comment</button>
    </form>
    <?php foreach ($comments as $c): ?>
        <div class="comment"><strong><?= e($c['name']) ?></strong> <small><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></small>
            <p><?= nl2br(e($c['body'])) ?></p></div>
    <?php endforeach; ?>
</section>
<?php require 'includes/footer.php'; ?>
