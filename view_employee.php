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

$page_title = 'View Employee';
include 'header.php';
?>
<div class="card">
    <h2>Employee details</h2>
    <dl class="details">
        <dt>Name</dt>
        <dd><?php echo e($employee['name']); ?></dd>
        <dt>Email</dt>
        <dd><?php echo e($employee['email']); ?></dd>
        <dt>Phone</dt>
        <dd><?php echo e($employee['phone']); ?></dd>
        <dt>Position</dt>
        <dd><?php echo e($employee['position']); ?></dd>
        <dt>Created</dt>
        <dd><?php echo e($employee['created_at']); ?></dd>
        <dt>Updated</dt>
        <dd><?php echo e($employee['updated_at']); ?></dd>
    </dl>
    <a class="btn" href="edit_employee.php?id=<?php echo e($employee['id']); ?>">Edit</a>
    <a class="btn btn-secondary" href="index.php">Back</a>
</div>
<?php include 'footer.php'; ?>
