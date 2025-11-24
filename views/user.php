<?php
// Nhom15/views/user.php
$pageTitle = "Quản lý Tài khoản";
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

// KIỂM TRA QUYỀN ADMIN
checkRole(['admin'], 'dashboard.php', "Chức năng chỉ dành cho Admin.");

require_once '../functions/user_functions.php';
$users = getAllUsers();

include './sidebar.php';
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <h3 class="mb-4">DANH SÁCH TÀI KHOẢN HỆ THỐNG</h3>
        
        <?php
        if (isset($_GET['success'])) echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
        if (isset($_GET['error'])) echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
        ?>

        <div class="text-end mb-3">
            <a href="user/create_user.php" class="btn btn-success">
                <i class="bi bi-person-plus-fill me-1"></i> Thêm tài khoản mới
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">STT</th>
                        <th>Tên đăng nhập</th>
                        <th>Vai trò</th>
                        <th style="width: 15%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stt = 1;
                    foreach ($users as $u): ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td>
                            <?php 
                            if($u['role'] == 'admin') echo '<span class="badge bg-danger">Admin</span>';
                            elseif($u['role'] == 'teacher') echo '<span class="badge bg-primary">Giáo viên</span>';
                            else echo '<span class="badge bg-secondary">Sinh viên</span>';
                            ?>
                        </td>
                        <td>
                            <a href="user/edit_user.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                            <a href="../handle/user_process.php?action=delete&id=<?= $u['id'] ?>" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include './layout_footer.php'; ?>