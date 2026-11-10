<?php /* expects $a (article row with category_name) */ ?>
<article class="card">
    <?php if (!empty($a['image'])): ?>
        <a href="article.php?id=<?= (int)$a['id'] ?>"><img src="uploads/<?= e($a['image']) ?>" alt=""></a>
    <?php else: ?>
        <a href="article.php?id=<?= (int)$a['id'] ?>" class="noimg"><?= e($a['category_name']) ?></a>
    <?php endif; ?>
    <div class="card-body">
        <span class="tag"><?= e($a['category_name']) ?></span>
        <h3><a href="article.php?id=<?= (int)$a['id'] ?>"><?= e($a['title']) ?></a></h3>
        <p><?= e($a['summary']) ?></p>
        <small><?= date('M d, Y', strtotime($a['created_at'])) ?> · <?= (int)$a['views'] ?> views</small>
    </div>
</article>
