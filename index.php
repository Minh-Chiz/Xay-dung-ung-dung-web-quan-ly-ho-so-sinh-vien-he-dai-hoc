<?php
session_start();
// Nếu người dùng đã đăng nhập, chuyển hướng họ đến dashboard
if (isset($_SESSION['user_id']) && !empty($_SESSION['username'])) {
    header('Location: ./views/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống - Quản lý hồ sơ sinh viên</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    
    <div class="main-container">
        <div class="card login-card">
            <div class="row g-0 h-100">
                
                <div class="col-lg-6 login-image-col">
                    <img src="./images/draw2.webp" alt="Login illustration" class="login-image">
                </div>
                
                <div class="col-lg-6 login-form-col">
                    
                    <div class="logo-section text-center text-lg-start">
                        <img src="./images/fitdnu_logo.png" alt="Logo FIT DNU">
                        <h1 class="login-title">Chào mừng trở lại!</h1>
                        <p class="text-muted mb-4">Vui lòng đăng nhập để quản lý hồ sơ.</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="./handle/login_process.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label" for="username">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Nhập tên đăng nhập của bạn" required />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="password">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Nhập mật khẩu" required />
                            </div>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit" name="login" class="btn btn-primary btn-login">
                                Đăng nhập <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center copyright">
                        Copyright © 2025 - Nhóm 15 - FITDNU <br>
                        <small class="text-muted">Khoa Công nghệ Thông tin - Đại học Đại Nam</small>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>