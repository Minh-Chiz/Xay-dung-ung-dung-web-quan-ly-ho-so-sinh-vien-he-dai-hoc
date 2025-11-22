<?php
$pageTitle = "Chỉnh sửa Lớp học"; // Đặt tiêu đề
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher', 'admin'], __DIR__ . '/../class.php', "Bạn không có quyền chỉnh sửa lớp học.");

// Kiểm tra có ID không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../class.php?error=Không tìm thấy lớp học");
    exit;
}
$id = $_GET['id'];

require_once __DIR__ . '/../../functions/class_functions.php';
$class = getClassById($id); // Lấy thông tin lớp

if (!$class) {
    header("Location: ../class.php?error=Không tìm thấy lớp học");
    exit;
}

include __DIR__ . '/../sidebar.php'; // Include sidebar
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            
            <div class="card-body p-4">
                <h3 class="mb-4">CHỈNH SỬA LỚP HỌC</h3> <?php
                // Hiển thị thông báo lỗi
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../../handle/class_process.php" method="POST" id="editClassForm">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($class['id']); ?>">

                    <div class="mb-3">
                        <label for="class_code" class="form-label">Mã lớp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="class_code" name="class_code"
                            value="<?php echo htmlspecialchars($class['class_code']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="class_name" class="form-label">Tên lớp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="class_name" name="class_name"
                            value="<?php echo htmlspecialchars($class['class_name']); ?>" required>
                    </div>

                    </form>
            </div>
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <a href="../class.php" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" form="editClassForm">
                    <i class="bi bi-save me-1"></i> Cập nhật
                </button>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; // Include footer ?>