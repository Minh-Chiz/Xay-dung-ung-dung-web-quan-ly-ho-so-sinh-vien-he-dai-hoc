<?php
// Kiểm tra đăng nhập
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
checkRole(['teacher'], __DIR__ . '/../grade.php', "Bạn không có quyền chỉnh sửa điểm số.");

// Nhúng các hàm cần thiết
require_once __DIR__ . '/../../functions/student_functions.php';
require_once __DIR__ . '/../../functions/subject_functions.php';
require_once __DIR__ . '/../../handle/grade_process.php';

// 1. Kiểm tra ID và lấy thông tin điểm số
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../grade.php?error=ID điểm số không hợp lệ.");
    exit();
}

$grade_id = $_GET['id'];
$grade = handleGetGradeById($grade_id); // Lấy thông tin bản ghi điểm

if (!$grade) {
    header("Location: ../grade.php?error=Không tìm thấy bản ghi điểm này.");
    exit();
}

// 2. Lấy danh sách Sinh viên và Học phần cho Dropdown
$students = getAllStudents();
$subjects = getAllSubjects();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chỉnh sửa điểm số</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mt-3 mb-4">CHỈNH SỬA ĐIỂM SỐ</h3>
                
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
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($grade['id']); ?>">
                    
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Sinh viên</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">Chọn Sinh viên</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?= $student['id'] ?>" 
                                    <?php echo ($student['id'] == $grade['student_id']) ? 'selected' : ''; ?>>
                                    [<?= htmlspecialchars($student['student_code']) ?>] <?= htmlspecialchars($student['student_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Học phần</label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            <option value="">Chọn Học phần</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>" 
                                    <?php echo ($subject['id'] == $grade['subject_id']) ? 'selected' : ''; ?>>
                                    [<?= htmlspecialchars($subject['subject_code']) ?>] <?= htmlspecialchars($subject['subject_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="grade" class="form-label">Điểm số</label>
                        <input type="number" step="0.01" class="form-control" id="grade" name="grade" required min="0" max="10"
                            value="<?php echo htmlspecialchars($grade['grade']); ?>"
                            placeholder="Nhập điểm (Ví dụ: 8.5 hoặc 5.0)">
                    </div>
                    
                    <div class="mb-3">
                        <label for="term" class="form-label">Học kỳ (Không bắt buộc)</label>
                        <input type="text" class="form-control" id="term" name="term"
                            value="<?php echo htmlspecialchars($grade['term'] ?? ''); ?>"
                            placeholder="Ví dụ: HK1 (2024-2025)">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Cập nhật điểm</button>
                        <a href="../grade.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
