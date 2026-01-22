<?php
require_once 'db.php';
require_once 'helpers.php';
require_once 'auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$values = [
    'username' => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    $values['username'] = $username;
    $values['email'] = $email;

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email already exists.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, $hash]);
        set_flash('success', 'Registration successful. You can now log in.');
        header('Location: login.php');
        exit;
    }
}

$page_title = 'Register';
include 'header.php';
?>
<div class="card">
    <h2>Register</h2>
    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" class="form">
        <label>
            Username
            <input type="text" name="username" value="<?php echo e($values['username']); ?>">
        </label>
        <label>
            Email
            <input type="email" name="email" value="<?php echo e($values['email']); ?>">
        </label>
        <label>
            Password
            <input type="password" name="password">
        </label>
        <label>
            Confirm password
            <input type="password" name="confirm">
        </label>
        <button type="submit" class="btn">Create account</button>
    </form>
    <p class="muted">Already have an account? <a href="login.php">Login</a></p>
</div>
<?php include 'footer.php'; ?>
