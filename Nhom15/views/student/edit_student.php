<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chỉnh sửa hồ sơ sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h3 class="mt-3 mb-4 text-center text-primary">CHỈNH SỬA HỒ SƠ SINH VIÊN</h3>
        <?php
            // Kiểm tra có ID không
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header("Location: ../student.php?error=Không tìm thấy sinh viên");
                exit;
            }
            
            $id = $_GET['id'];
            
            // Lấy thông tin sinh viên
            require_once __DIR__ . '/../../handle/student_process.php';
            // **GIẢ ĐỊNH:** Hàm này trả về đầy đủ 8 trường dữ liệu (bao gồm cả các trường mới)
            $student = handleGetStudentById($id); 

            if (!$student) {
                header("Location: ../student.php?error=Không tìm thấy sinh viên");
                exit;
            }
            
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
            <div class="row justify-content-center">
                <div class="col-md-8"> <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="../../handle/student_process.php" method="POST">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="student_code" class="form-label">Mã sinh viên</label>
                                        <input type="text" class="form-control" id="student_code" name="student_code"
                                            value="<?php echo htmlspecialchars($student['student_code'] ?? ''); ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="student_name" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" id="student_name" name="student_name"
                                            value="<?php echo htmlspecialchars($student['student_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="student_date" class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" id="student_date" name="student_date"
                                            value="<?php echo htmlspecialchars($student['student_date'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Giới tính</label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <?php $current_gender = $student['gender'] ?? ''; ?>
                                            <option value="" disabled>Chọn giới tính</option>
                                            <option value="Nam" <?php echo ($current_gender === 'Nam' ? 'selected' : ''); ?>>Nam</option>
                                            <option value="Nữ" <?php echo ($current_gender === 'Nữ' ? 'selected' : ''); ?>>Nữ</option>
                                            <option value="Khác" <?php echo ($current_gender === 'Khác' ? 'selected' : ''); ?>>Khác</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="major" class="form-label">Khoa/Ngành</label>
                                        <input type="text" class="form-control" id="major" name="major"
                                            value="<?php echo htmlspecialchars($student['major'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="class" class="form-label">Lớp</label>
                                        <input type="text" class="form-control" id="class" name="class"
                                            value="<?php echo htmlspecialchars($student['class'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                            value="<?php echo htmlspecialchars($student['phone_number'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3">
                                    <a href="../student.php" class="btn btn-secondary me-md-2">
                                        <i class="bi bi-x-circle me-1"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-floppy-fill me-1"></i> Lưu cập nhật
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>