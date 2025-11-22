<?php
$pageTitle = "Thêm Học phần mới"; // Đặt tiêu đề
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher', 'admin'], __DIR__ . '/../subject.php', "Bạn không có quyền thêm học phần.");

include __DIR__ . '/../sidebar.php'; // Include sidebar
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            
            <div class="card-body p-4">
                <h3 class="mb-4">THÊM HỌC PHẦN MỚI</h3> <?php
                // Hiển thị thông báo lỗi (nếu có)
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../../handle/subject_process.php" method="POST" id="createSubjectForm">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Mã học phần <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" required
                            placeholder="Ví dụ: INT3002">
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Tên học phần <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required
                            placeholder="Ví dụ: Lập trình web">
                    </div>

                    <div class="mb-3">
                        <label for="credits" class="form-label">Số tín chỉ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="credits" name="credits" required min="1"
                            placeholder="Ví dụ: 3">
                    </div>
                    
                    </form>
            </div>
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <a href="../subject.php" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" form="createSubjectForm">
                    <i class="bi bi-plus-circle me-1"></i> Thêm học phần
                </button>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; // Include footer ?>