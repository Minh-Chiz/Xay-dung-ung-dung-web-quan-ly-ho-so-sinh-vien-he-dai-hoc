<?php
$pageTitle = "Thêm Sinh viên mới";
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher', 'admin'], __DIR__ . '/../student.php', "Bạn không có quyền thêm sinh viên.");

// --- LOGIC MỚI: Lấy tên lớp từ URL nếu có ---
$prefillClass = isset($_GET['prefill_class']) ? trim($_GET['prefill_class']) : '';

include __DIR__ . '/../sidebar.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4"> 
                <h3 class="mb-4">THÊM SINH VIÊN MỚI</h3> 
                
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                
                <form action="../../handle/student_process.php" method="POST" id="createStudentForm">
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
                            <label for="major" class="form-label">Ngành <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="major" name="major" placeholder="Ví dụ: Công nghệ thông tin" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="class" class="form-label">Lớp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo !empty($prefillClass) ? 'bg-light' : ''; ?>" 
                                    id="class" name="class" 
                                    placeholder="Ví dụ: K67CNTT1" 
                                    required
                                    value="<?php echo htmlspecialchars($prefillClass); ?>"
                                    <?php echo !empty($prefillClass) ? 'readonly' : ''; ?> 
                            >
                            <?php if(!empty($prefillClass)): ?>
                                <div class="form-text text-success"><i class="bi bi-lock-fill"></i> Đang thêm vào lớp: <strong><?php echo htmlspecialchars($prefillClass); ?></strong></div>
                            <?php endif; ?>
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
                    
                </form> 
            </div> 
            
            <div class="card-footer bg-light border-0 text-end p-3">
                <?php 
                    $cancelLink = "../student.php"; // Mặc định về danh sách chung
                    if(!empty($prefillClass)) {
                        // Nếu đang thêm từ lớp cụ thể, quay lại đúng lớp đó (chúng ta không có ID ở đây nên quay về danh sách lớp là an toàn nhất hoặc dùng javascript history)
                        $cancelLink = "javascript:history.back()";
                    }
                ?>
                <a href="<?php echo $cancelLink; ?>" class="btn btn-secondary me-2">
                    <i class="bi bi-x-circle me-1"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary" form="createStudentForm">
                    <i class="bi bi-plus-circle me-1"></i> Thêm sinh viên
                </button>
            </div>
            
        </div> 
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>