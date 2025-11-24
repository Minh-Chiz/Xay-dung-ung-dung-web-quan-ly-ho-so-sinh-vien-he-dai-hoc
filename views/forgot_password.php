<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Quản lý hồ sơ sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="main-container">
        <div class="card login-card">
            <div class="row g-0 h-100">
                <div class="col-lg-6 login-image-col">
                    <img src="../assets/images/draw2.webp" alt="Illustration" class="login-image">
                </div>
                
                <div class="col-lg-6 login-form-col">
                    <div class="logo-section text-center text-lg-start">
                        <h2 class="login-title mb-3">Khôi phục mật khẩu</h2>
                        <p class="text-muted mb-4">Nhập thông tin xác thực để đặt lại mật khẩu mới.</p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?php echo htmlspecialchars($_GET['error']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="../handle/reset_password_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Mã sinh viên</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Nhập mã sinh viên" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email đã đăng ký</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Nhập email sinh viên" required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới" required />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required />
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-login">
                                Đặt lại mật khẩu <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                            <a href="../index.php" class="btn btn-outline-secondary">
                                Quay lại Đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>