<?php
$pageTitle = "Sửa Tài khoản";
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/user_functions.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['admin'], '../dashboard.php');

$id = $_GET['id'] ?? 0;
$user = getUserById($id);
if (!$user) { header("Location: ../user.php"); exit(); }

include __DIR__ . '/../sidebar.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-4">CẬP NHẬT TÀI KHOẢN</h3>
                <?php if (isset($_GET['error'])) echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>'; ?>
                
                <form action="../../handle/user_process.php" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới (Để trống nếu không đổi)</label>
                        <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu mới...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vai trò</label>
                        <select class="form-select" name="role">
                            <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Sinh viên</option>
                            <option value="teacher" <?= $user['role'] == 'teacher' ? 'selected' : '' ?>>Giáo viên</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <a href="../user.php" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout_footer.php'; ?>