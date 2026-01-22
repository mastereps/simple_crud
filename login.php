<?php
require_once 'db.php';
require_once 'helpers.php';
require_once 'auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$login_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_value = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login_value === '') {
        $errors[] = 'Username or email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$login_value, $login_value]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            set_flash('success', 'Welcome back.');
            header('Location: index.php');
            exit;
        }

        $errors[] = 'Invalid credentials.';
    }
}

$page_title = 'Login';
include 'header.php';
?>
<div class="card">
    <h2>Login</h2>
    <?php if ($errors): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" class="form">
        <label>
            Username or email
            <input type="text" name="login" value="<?php echo e($login_value); ?>">
        </label>
        <label>
            Password
            <input type="password" name="password">
        </label>
        <button type="submit" class="btn">Login</button>
    </form>
    <p class="muted">No account? <a href="register.php">Register</a></p>
</div>
<?php include 'footer.php'; ?>
