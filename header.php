<?php
if (!isset($page_title)) {
    $page_title = 'Employee Manager';
}
$logged_in = !empty($_SESSION['user_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($page_title); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page">
    <header class="site-header">
        <div class="brand">
            <h1>Employee Manager</h1>
            <p class="tagline">Core PHP CRUD demo</p>
        </div>
        <nav class="nav">
            <?php if ($logged_in): ?>
                <a href="index.php">Employees</a>
                <a href="add_employee.php">Add</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="content">
        <?php $flash_messages = get_flash(); ?>
        <?php if ($flash_messages): ?>
            <div class="flash-stack">
                <?php foreach ($flash_messages as $flash): ?>
                    <?php
                        $flash_type = $flash['type'] ?? 'success';
                        $flash_class = $flash_type === 'error' ? 'alert-error' : 'alert-success';
                    ?>
                    <div class="alert <?php echo e($flash_class); ?>">
                        <p><?php echo e($flash['message'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
