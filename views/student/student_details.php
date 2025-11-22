<?php
$pageTitle = "Chi tiết Sinh viên"; // Đặt tiêu đề
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');

// 1. Lấy thông tin người dùng và ID sinh viên
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isStudent = ($currentUser && $currentUser['role'] === 'student');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../student.php?error=ID sinh viên không hợp lệ.");
    exit;
}
$id = $_GET['id'];

// 2. Lấy thông tin sinh viên từ CSDL
require_once __DIR__ . '/../../handle/student_process.php';
$student = handleGetStudentById($id);

if (!$student) {
    header("Location: ../student.php?error=Không tìm thấy sinh viên.");
    exit;
}

// 3. KIỂM TRA QUYỀN:
// Chỉ cho phép Giáo viên, hoặc chính sinh viên đó xem
// SỬA ĐỔI: Thêm trim() vào cả hai biến để đảm bảo so sánh chính xác
if (!$isAdmin && !$isTeacher && !($isStudent && trim($currentUser['username']) === trim($student['student_code']))) {
    header("Location: ../student.php?error=Bạn không có quyền xem thông tin này.");
    exit;
}

// Nếu tất cả đều ổn, include sidebar
include __DIR__ . '/../sidebar.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                
                <h3 class="mb-4">Thông tin chi tiết: <?php echo htmlspecialchars($student['student_name']); ?></h3>

                <div class="list-group list-group-flush">
                    
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Mã sinh viên</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['student_code']); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Họ và tên</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['student_name']); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Ngày sinh</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars(date('d/m/Y', strtotime($student['student_date']))); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Giới tính</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['gender']); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Ngành</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['major']); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Lớp</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['class']); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Email</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['email'] ?? 'Chưa cập nhật'); ?></span>
                    </div>

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong class="text-muted" style="width: 30%;">Số điện thoại</strong>
                        <span style="width: 70%;"><?php echo htmlspecialchars($student['phone_number'] ?? 'Chưa cập nhật'); ?></span>
                    </div>

                </div>

            </div>
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <a href="../student.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Quay lại danh sách
                </a>
                
                <?php if ($isTeacher): // Chỉ giáo viên mới thấy nút sửa ở đây ?>
                <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Chỉnh sửa
                </a>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; // Include footer ?>