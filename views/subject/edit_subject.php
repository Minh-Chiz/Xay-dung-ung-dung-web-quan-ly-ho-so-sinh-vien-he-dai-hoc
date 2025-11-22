<?php
$pageTitle = "Chỉnh sửa Học phần"; // Đặt tiêu đề
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher', 'admin'], __DIR__ . '/../subject.php', "Bạn không có quyền chỉnh sửa học phần.");

// Kiểm tra có ID không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../subject.php?error=Không tìm thấy ID học phần");
    exit;
}
$id = $_GET['id'];

// Lấy thông tin lớp học
require_once __DIR__ . '/../../handle/subject_process.php';
$subject = handleGetSubjectById($id);

if (!$subject) {
    header("Location: ../subject.php?error=Không tìm thấy học phần");
    exit;
}

include __DIR__ . '/../sidebar.php'; // Include sidebar
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            
            <div class="card-body p-4">
                <h3 class="mb-4">CHỈNH SỬA HỌC PHẦN</h3> <?php
                // Hiển thị thông báo lỗi (từ URL)
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../../handle/subject_process.php" method="POST" id="editSubjectForm">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($subject['id']); ?>">

                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Mã học phần <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code"
                            value="<?php echo htmlspecialchars($subject['subject_code']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Tên học phần <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name"
                            value="<?php echo htmlspecialchars($subject['subject_name']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="credits" class="form-label">Số tín chỉ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="credits" name="credits"
                            value="<?php echo htmlspecialchars($subject['credits']); ?>" required min="1">
                    </div>

                    </form>
            </div>
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <a href="../subject.php" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" form="editSubjectForm">
                    <i class="bi bi-save me-1"></i> Cập nhật
                </button>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; // Include footer ?>