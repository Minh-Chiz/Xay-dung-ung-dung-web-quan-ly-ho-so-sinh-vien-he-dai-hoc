<?php
$pageTitle = "Thêm Lớp học mới"; // Đặt tiêu đề
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher', 'admin'], __DIR__ . '/../student.php', "Bạn không có quyền thêm lớp học.");

include __DIR__ . '/../sidebar.php'; // Include sidebar
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            
            <div class="card-body p-4">
                <h3 class="mb-4">THÊM LỚP HỌC MỚI</h3> <?php
                // Hiển thị thông báo lỗi
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../../handle/class_process.php" method="POST" id="createClassForm">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="class_code" class="form-label">Mã lớp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="class_code" name="class_code" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="class_name" class="form-label">Tên lớp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="class_name" name="class_name" required>
                    </div>
                    
                    </form>
            </div>
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <a href="../class.php" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" form="createClassForm">
                    <i class="bi bi-plus-circle me-1"></i> Thêm lớp học
                </button>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; // Include footer ?>