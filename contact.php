<?php
require 'config.php';
$error = '';
$old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($old as $k => $_) $old[$k] = trim($_POST[$k] ?? '');
    if (in_array('', $old, true)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // CREATE (contact message)
        $pdo->prepare('INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)')
            ->execute([mb_substr($old['name'], 0, 80), mb_substr($old['email'], 0, 100), mb_substr($old['subject'], 0, 150), $old['message']]);
        flash('Thank you! Your message has been sent.');
        header('Location: contact.php');
        exit;
    }
}
$pageTitle = 'Contact Us';
require 'includes/header.php';
$msg = flash();
?>
<h2 class="section-title">Contact Us</h2>
<div class="single">
    <?php if ($msg): ?><div class="alert ok"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <label>Name <input type="text" name="name" value="<?= e($old['name']) ?>" required></label>
        <label>Email <input type="email" name="email" value="<?= e($old['email']) ?>" required></label>
        <label>Subject <input type="text" name="subject" value="<?= e($old['subject']) ?>" required></label>
        <label>Message <textarea name="message" rows="5" required><?= e($old['message']) ?></textarea></label>
        <button type="submit" class="btn">Send message</button>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
