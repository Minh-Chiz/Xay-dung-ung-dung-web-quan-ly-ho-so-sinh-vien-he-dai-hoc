<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản lý hồ sơ sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include './menu.php'; ?>
    <div class="container mt-3">
        
        <h3 class="mt-3">DANH SÁCH SINH VIÊN</h3>
        
        <?php
        // Hiển thị thông báo thành công
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($_GET['success']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        
        // Hiển thị thông báo lỗi
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
            if (alertNode) {
                // Đảm bảo thư viện Bootstrap đã được tải
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                    bsAlert.close();
                } else {
                    // Nếu Bootstrap chưa tải xong, có thể ẩn thủ công
                    alertNode.style.display = 'none';
                }
            }
        }, 3000);
        </script>
        
        <a href="student/create_student.php" class="btn btn-primary mb-3">Thêm sinh viên</a>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Mã sinh viên</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Giới tính</th>
                        <th scope="col">Khoa/Ngành</th>
                        <th scope="col">Lớp</th>
                        <th scope="col">Email</th>
                        <th scope="col">SĐT</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Bao gồm file xử lý dữ liệu sinh viên
                    require '../handle/student_process.php'; 
                    
                    // Lấy danh sách sinh viên
                    $students = handleGetAllStudents();
                    
                    // Kiểm tra nếu có dữ liệu
                    if (is_array($students) && count($students) > 0) {
                        foreach($students as $index => $stu){
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($stu["id"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["student_code"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["student_name"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["gender"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["major"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["class"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["email"] ?? '') ?></td>
                            <td><?= htmlspecialchars($stu["phone_number"] ?? '') ?></td>
                            <td>
                                <a href="student/edit_student.php?id=<?= htmlspecialchars($stu["id"] ?? '') ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="../handle/student_process.php?action=delete&id=<?= htmlspecialchars($stu["id"] ?? '') ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                        // Trường hợp không có sinh viên nào
                        echo '<tr><td colspan="9" class="text-center">Chưa có hồ sơ sinh viên nào được thêm.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>