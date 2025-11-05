<?php
// Kiểm tra đăng nhập và chuyển hướng nếu cần
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
// START: LẤY VAI TRÒ NGƯỜI DÙNG
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
// END: LẤY VAI TRÒ NGƯỜI DÙNG
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản lý điểm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include './menu.php'; ?>
    <div class="container mt-3">
        
        <h3 class="mt-3">DANH SÁCH ĐIỂM SỐ</h3>
        
        <?php
        // Hiển thị thông báo thành công (từ URL)
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($_GET['success']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        
        // Hiển thị thông báo lỗi (từ URL)
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
        
        <?php if ($isTeacher): // Ẩn/hiện nút Create ?>
            <a href="grade/create_grade.php" class="btn btn-primary mb-3">Thêm điểm mới</a>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Mã sinh viên</th>
                        <th scope="col">Tên sinh viên</th>
                        <th scope="col">Mã HP</th>
                        <th scope="col">Tên học phần</th>
                        <th scope="col">Điểm số</th>
                        <th scope="col">Học kỳ</th>
                        <?php if ($isTeacher): // Ẩn/hiện cột Thao tác ?>
                            <th scope="col">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Nhúng file xử lý để lấy dữ liệu điểm
                    require_once '../handle/grade_process.php';
                    
                    $grades = handleGetAllGrades();
                    
                    foreach ($grades as $grade) {
                    ?>
                        <tr>
                            <td><?= $grade["id"] ?></td>
                            <td><?= $grade["student_code"] ?></td>
                            <td><?= $grade["student_name"] ?></td>
                            <td><?= $grade["subject_code"] ?></td>
                            <td><?= $grade["subject_name"] ?></td>
                            
                            <td><?= number_format($grade["grade"], 1) ?></td>
                            
                            <td><?= $grade["term"] ?></td>
                            <?php if ($isTeacher): // Ẩn/hiện các nút Edit/Delete ?>
                                <td>
                                    <a href="grade/edit_grade.php?id=<?= $grade['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="../handle/grade_process.php?action=delete&id=<?= $grade['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa điểm số này?');">Delete</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php } ?>
                    
                    <?php if (empty($grades)): ?>
                        <tr>
                            <td colspan="<?= $isTeacher ? 8 : 7 ?>" class="text-center">Chưa có điểm số nào được nhập.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>