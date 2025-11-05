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
    <title>Quản lý học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include './menu.php'; ?>
    <div class="container mt-3">
        
        <h3 class="mt-3">DANH SÁCH HỌC PHẦN</h3>
        
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
            // Kiểm tra xem bootstrap có sẵn chưa
            if (typeof bootstrap !== 'undefined' && alertNode) {
                let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                bsAlert.close();
            }
        }, 3000);
        </script>
        
        <?php if ($isTeacher): // Ẩn/hiện nút Create ?>
            <a href="subject/create_subject.php" class="btn btn-primary mb-3">Thêm học phần</a>
        <?php endif; ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Mã học phần</th>
                    <th scope="col">Tên học phần</th>
                    <th scope="col">Số tín chỉ</th>
                    <?php if ($isTeacher): // Ẩn/hiện cột Thao tác ?>
                        <th scope="col">Thao tác</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Nhúng file functions đã tạo trước đó
                require_once '../functions/subject_functions.php';
                
                $subjects = getAllSubjects();
                
                foreach ($subjects as $subject) {
                ?>
                    <tr>
                        <td><?= $subject["id"] ?></td>
                        <td><?= $subject["subject_code"] ?></td>
                        <td><?= $subject["subject_name"] ?></td>
                        <td><?= $subject["credits"] ?></td>
                        <?php if ($isTeacher): // Ẩn/hiện các nút Edit/Delete ?>
                            <td>
                                <a href="subject/edit_subject.php?id=<?= $subject['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="../handle/subject_process.php?action=delete&id=<?= $subject['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa học phần này?');">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php } ?>
                
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="<?= $isTeacher ? 5 : 4 ?>" class="text-center">Chưa có học phần nào trong hệ thống.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
