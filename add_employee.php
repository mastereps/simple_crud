<?php
require_once 'db.php';
require_once 'helpers.php';
require_once 'auth.php';

require_login();

$errors = [];
$values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'position' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = trim($_POST['name'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['phone'] = trim($_POST['phone'] ?? '');
    $values['position'] = trim($_POST['position'] ?? '');

    if ($values['name'] === '') {
        $errors[] = 'Name is required.';
    }

    if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO employees (user_id, name, email, phone, position) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $_SESSION['user_id'],
            $values['name'],
            $values['email'],
            $values['phone'],
            $values['position'],
        ]);
        set_flash('success', 'Employee added.');
        header('Location: index.php');
        exit;
    }
}

$page_title = 'Add Employee';
include 'header.php';
?>
<div class="card">
    <h2>Add employee</h2>
    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" class="form">
        <label>
            Full name
            <input type="text" name="name" value="<?php echo e($values['name']); ?>">
        </label>
        <label>
            Email
            <input type="email" name="email" value="<?php echo e($values['email']); ?>">
        </label>
        <label>
            Phone
            <input type="text" name="phone" value="<?php echo e($values['phone']); ?>">
        </label>
        <label>
            Position
            <input type="text" name="position" value="<?php echo e($values['position']); ?>">
        </label>
        <button type="submit" class="btn">Save</button>
        <a class="btn btn-secondary" href="index.php">Cancel</a>
    </form>
</div>
<?php include 'footer.php'; ?>
