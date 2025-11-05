<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher'], __DIR__ . '/../subject.php', "Bạn không có quyền thêm học phần.");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thêm học phần mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mt-3 mb-4">THÊM HỌC PHẦN MỚI</h3>
                
                <?php
                // Hiển thị thông báo lỗi (nếu có)
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . htmlspecialchars($_GET['error']) . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
                }
                ?>
                <script>
                // Sau 3 giây sẽ tự động ẩn alert (Cần đảm bảo file bootstrap.bundle.min.js đã được load)
                setTimeout(() => {
                    let alertNode = document.querySelector('.alert');
                    if (typeof bootstrap !== 'undefined' && alertNode) {
                        let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                        bsAlert.close();
                    }
                }, 3000);
                </script>
                
                <form action="../../handle/subject_process.php" method="POST">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Mã học phần</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" required
                            placeholder="Ví dụ: INT3002">
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Tên học phần</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required
                            placeholder="Ví dụ: Lập trình web">
                    </div>

                    <div class="mb-3">
                        <label for="credits" class="form-label">Số tín chỉ</label>
                        <input type="number" class="form-control" id="credits" name="credits" required min="1"
                            placeholder="Ví dụ: 3">
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Thêm học phần</button>
                        <a href="../subject.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
