<?php
$pageTitle = "Chỉnh sửa Sinh viên"; // Đặt tiêu đề
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher','admin'], __DIR__ . '/../student.php', "Bạn không có quyền chỉnh sửa sinh viên.");

// Kiểm tra có ID không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../student.php?error=Không tìm thấy sinh viên");
    exit;
}
$id = $_GET['id'];

// [MỚI - THÊM ĐOẠN NÀY] Lấy thông tin điều hướng từ URL
$redirect = $_GET['redirect'] ?? '';
$class_id = $_GET['class_id'] ?? '';

// Lấy thông tin sinh viên
require_once __DIR__ . '/../../handle/student_process.php';
$student = handleGetStudentById($id);

if (!$student) {
    header("Location: ../student.php?error=Không tìm thấy sinh viên");
    exit;
}

include __DIR__ . '/../sidebar.php'; // Include sidebar
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            
            <div class="card-body p-4">
                
                <h3 class="mb-4">CHỈNH SỬA SINH VIÊN</h3> <?php
                // Hiển thị thông báo lỗi
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../../handle/student_process.php" method="POST" id="editStudentForm">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">

                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_code" class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_code" name="student_code"
                                value="<?php echo htmlspecialchars($student['student_code']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="student_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="student_name" name="student_name"
                                value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_date" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="student_date" name="student_date"
                                value="<?php echo htmlspecialchars($student['student_date']?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Giới tính <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="Nam" <?php if (isset($student['gender']) && $student['gender'] == 'Nam') echo 'selected'; ?>>Nam</option>
                                <option value="Nữ" <?php if (isset($student['gender']) && $student['gender'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                                <option value="Khác" <?php if (isset($student['gender']) && $student['gender'] == 'Khác') echo 'selected'; ?>>Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="major" class="form-label">Ngành <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="major" name="major"
                                value="<?php echo htmlspecialchars($student['major'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="student_class" class="form-label">Lớp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-light" id="student_class" name="student_class"
                                value="<?php echo htmlspecialchars($student['class'] ?? ''); ?>" required readonly>
                            <div class="form-text text-muted"><i class="bi bi-lock-fill"></i> Lớp học không thể thay đổi.</div>
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
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                value="<?php echo htmlspecialchars($student['phone_number'] ?? ''); ?>">
                        </div>
                    </div>

                    </form>
            </div>
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <a href="javascript:history.back()" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" form="editStudentForm">
                    <i class="bi bi-save me-1"></i> Cập nhật
                </button>
            </div>
            
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layout_footer.php'; // Include footer ?>