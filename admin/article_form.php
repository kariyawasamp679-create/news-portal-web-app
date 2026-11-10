<?php
$pageTitle = 'Article';
require '_header.php';

$id = (int)($_GET['id'] ?? 0);
$article = ['title' => '', 'summary' => '', 'content' => '', 'category_id' => '', 'is_featured' => 0, 'image' => null];
if ($id) {
    $st = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $st->execute([$id]);
    $article = $st->fetch();
    if (!$article) {
        flash('Article not found.');
        header('Location: articles.php');
        exit;
    }
}
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $article['title'] = trim($_POST['title'] ?? '');
    $article['summary'] = trim($_POST['summary'] ?? '');
    $article['content'] = trim($_POST['content'] ?? '');
    $article['category_id'] = (int)($_POST['category_id'] ?? 0);
    $article['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;

    if ($article['title'] === '' || $article['summary'] === '' || $article['content'] === '' || !$article['category_id']) {
        $error = 'Title, summary, content and category are required.';
    } else {
        $newImage = upload_image('image', $error);
        if (!$error) {
            $image = $newImage ?: $article['image'];
            if ($id) {
                // UPDATE
                $pdo->prepare('UPDATE articles SET title=?, summary=?, content=?, category_id=?, is_featured=?, image=? WHERE id=?')
                    ->execute([mb_substr($article['title'], 0, 200), mb_substr($article['summary'], 0, 300), $article['content'], $article['category_id'], $article['is_featured'], $image, $id]);
                flash('Article updated.');
            } else {
                // CREATE
                $pdo->prepare('INSERT INTO articles (title, summary, content, category_id, author_id, is_featured, image) VALUES (?,?,?,?,?,?,?)')
                    ->execute([mb_substr($article['title'], 0, 200), mb_substr($article['summary'], 0, 300), $article['content'], $article['category_id'], $_SESSION['user_id'], $article['is_featured'], $image]);
                flash('Article created.');
            }
            header('Location: articles.php');
            exit;
        }
    }
}
?>
<h2 class="section-title"><?= $id ? 'Edit' : 'Add' ?> Article</h2>
<?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form wide">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <label>Title <input type="text" name="title" maxlength="200" value="<?= e($article['title']) ?>" required></label>
    <label>Category
        <select name="category_id" required>
            <option value="">-- choose --</option>
            <?php foreach ($cats as $c): ?>
                <option value="<?= (int)$c['id'] ?>" <?= $article['category_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Summary (max 300 characters) <textarea name="summary" rows="2" maxlength="300" required><?= e($article['summary']) ?></textarea></label>
    <label>Content <textarea name="content" rows="10" required><?= e($article['content']) ?></textarea></label>
    <label>Image (optional, jpg/png/gif/webp, max 2MB) <input type="file" name="image" accept="image/*"></label>
    <?php if ($article['image']): ?><p>Current image: <img src="../uploads/<?= e($article['image']) ?>" alt="" height="60"></p><?php endif; ?>
    <label class="check"><input type="checkbox" name="is_featured" <?= $article['is_featured'] ? 'checked' : '' ?>> Show in Top Stories</label>
    <button type="submit" class="btn"><?= $id ? 'Update' : 'Publish' ?></button>
    <a href="articles.php">Cancel</a>
</form>
<?php require '_footer.php'; ?>
