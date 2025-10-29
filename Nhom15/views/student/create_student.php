<?php
// Bảo mật: Kiểm tra trạng thái đăng nhập
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thêm sinh viên mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8"> <h3 class="mt-3 mb-4 text-primary">THÊM HỒ SƠ SINH VIÊN MỚI</h3>
                
                <?php
                // Hiển thị thông báo lỗi
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                <script>
                // Sau 3 giây sẽ tự động ẩn alert
                setTimeout(() => {
                    let alertNode = document.querySelector('.alert');
                    if (alertNode) {
                        // Đảm bảo thư viện Bootstrap đã được tải
                        if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                            let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                            bsAlert.close();
                        } else {
                            alertNode.style.display = 'none';
                        }
                    }
                }, 3000);
                </script>
                
                <form action="../../handle/student_process.php" method="POST">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_code" class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_code" name="student_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="student_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_name" name="student_name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_date" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="student_date" name="student_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled selected>Chọn giới tính</option>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="major" class="form-label">Khoa/Ngành <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="major" name="major" placeholder="Ví dụ: Công nghệ thông tin" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="class" class="form-label">Lớp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="class" name="class" placeholder="Ví dụ: K67CNTT1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus-fill me-1"></i> Thêm sinh viên
                        </button>
                        <a href="../student.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>