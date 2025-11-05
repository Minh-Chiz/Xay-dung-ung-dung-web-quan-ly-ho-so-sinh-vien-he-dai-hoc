<?php
// Kiểm tra đăng nhập
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher'], __DIR__ . '/../grade.php', "Bạn không có quyền thêm điểm số.");

// Nhúng các hàm cần thiết để lấy danh sách Sinh viên và Học phần cho Dropdown
require_once __DIR__ . '/../../functions/student_functions.php';
require_once __DIR__ . '/../../functions/subject_functions.php';

// Lấy danh sách Sinh viên và Học phần
$students = getAllStudents();
$subjects = getAllSubjects();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thêm điểm mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mt-3 mb-4">THÊM ĐIỂM SỐ MỚI</h3>
                
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
                // Sau 3 giây sẽ tự động ẩn alert
                setTimeout(() => {
                    let alertNode = document.querySelector('.alert');
                    if (typeof bootstrap !== 'undefined' && alertNode) {
                        let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                        bsAlert.close();
                    }
                }, 3000);
                </script>
                
                <form action="../../handle/grade_process.php" method="POST">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Sinh viên</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">Chọn Sinh viên</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>">
                                    [<?= htmlspecialchars($student['student_code']) ?>] <?= htmlspecialchars($student['student_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($students)): ?>
                            <small class="text-danger">Chưa có sinh viên. Vui lòng thêm sinh viên trước.</small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Học phần</label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">Chọn Học phần</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>">
                                    [<?= htmlspecialchars($subject['subject_code']) ?>] <?= htmlspecialchars($subject['subject_name']) ?> (<?= $subject['credits'] ?> TC)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (empty($subjects)): ?>
                            <small class="text-danger">Chưa có học phần. Vui lòng thêm học phần trước.</small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="grade" class="form-label">Điểm số</label>
                        <input type="number" step="0.01" class="form-control" id="grade" name="grade" required min="0" max="10"
                            placeholder="Nhập điểm (Ví dụ: 8.5 hoặc 5.0)">
                    </div>
                    
                    <div class="mb-3">
                        <label for="term" class="form-label">Học kỳ (Không bắt buộc)</label>
                        <input type="text" class="form-control" id="term" name="term"
                            placeholder="Ví dụ: HK1 (2024-2025)">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" <?php if (empty($students) || empty($subjects)) echo 'disabled'; ?>>Thêm điểm</button>
                        <a href="../grade.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
