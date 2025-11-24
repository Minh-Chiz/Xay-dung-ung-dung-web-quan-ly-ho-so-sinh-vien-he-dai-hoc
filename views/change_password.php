<?php
$pageTitle = "Đổi mật khẩu"; // Đặt tiêu đề trang
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

include './sidebar.php'; // Include sidebar
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            
            <div class="card-body p-4">
                <h3 class="mb-4">ĐỔI MẬT KHẨU</h3> 
                
                <?php
                // Hiển thị thông báo LỖI
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                // Hiển thị thông báo THÀNH CÔNG
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>' . htmlspecialchars($_GET['success']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../handle/change_password_process.php" method="POST" id="changePasswordForm">
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="Nhập mật khẩu cũ">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Ít nhất 6 ký tự">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="bi bi-save me-2"></i> Cập nhật mật khẩu
                        </button>
                    </div>

                </form>
            </div>
            
            <div class="card-footer bg-light border-0 text-center p-3">
                <a href="dashboard.php" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại Dashboard
                </a>
            </div>

        </div>
    </div>
</div>

<?php include './layout_footer.php'; // Include footer ?>