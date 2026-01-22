<?php
require_once 'db.php';
require_once 'helpers.php';
require_once 'auth.php';

require_login();

$stmt = $pdo->prepare('SELECT * FROM employees WHERE user_id = ? ORDER BY created_at DESC, id DESC');
$stmt->execute([$_SESSION['user_id']]);
$employees = $stmt->fetchAll();

$page_title = 'Employees';
include 'header.php';
?>
<div class="card">
    <div class="card-header">
        <h2>Employees</h2>
        <a class="btn" href="add_employee.php">Add employee</a>
    </div>
    <?php if (!$employees): ?>
        <p class="muted">No employees yet. Add your first one.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo e($employee['name']); ?></td>
                        <td><?php echo e($employee['email']); ?></td>
                        <td><?php echo e($employee['phone']); ?></td>
                        <td><?php echo e($employee['position']); ?></td>
                        <td class="actions">
                            <a href="view_employee.php?id=<?php echo e($employee['id']); ?>">View</a>
                            <a href="edit_employee.php?id=<?php echo e($employee['id']); ?>">Edit</a>
                            <a href="delete_employee.php?id=<?php echo e($employee['id']); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
