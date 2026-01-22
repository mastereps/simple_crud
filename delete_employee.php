<?php
require_once 'db.php';
require_once 'helpers.php';
require_once 'auth.php';

require_login();

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    $page_title = 'Employee not found';
    include 'header.php';
    echo '<div class="card"><p>Employee not found.</p></div>';
    include 'footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM employees WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$employee = $stmt->fetch();

if (!$employee) {
    http_response_code(404);
    $page_title = 'Employee not found';
    include 'header.php';
    echo '<div class="card"><p>Employee not found.</p></div>';
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM employees WHERE id = ? AND user_id = ?');
    $stmt->execute([$id, $_SESSION['user_id']]);
    set_flash('success', 'Employee deleted.');
    header('Location: index.php');
    exit;
}

$page_title = 'Delete Employee';
include 'header.php';
?>
<div class="card">
    <h2>Delete employee</h2>
    <p>Are you sure you want to delete <strong><?php echo e($employee['name']); ?></strong>?</p>
    <form method="post" class="form-inline">
        <button type="submit" class="btn btn-danger">Yes, delete</button>
        <a class="btn btn-secondary" href="index.php">Cancel</a>
    </form>
</div>
<?php include 'footer.php'; ?>
