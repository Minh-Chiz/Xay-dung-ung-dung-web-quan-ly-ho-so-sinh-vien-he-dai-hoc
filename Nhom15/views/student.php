<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
require_once '../handle/student_process.php';
// Lấy giá trị tìm kiếm từ URL (GET)
$search_code = $_GET['search_code'] ?? '';

// Gọi hàm xử lý với tham số tìm kiếm
$students = handleGetAllStudents($search_code);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản lý hồ sơ sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
    <?php include './menu.php'; ?>
    <div class="container mt-3">
        
        <h3 class="mt-3">DANH SÁCH SINH VIÊN</h3>
        
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
        
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="student.php" method="GET" class="d-flex">
                    <input type="text" class="form-control me-2" name="search_code"
                        placeholder="Nhập mã sinh viên để tìm..."
                           value="<?php echo htmlspecialchars($search_code); // Hiển thị lại từ khóa đã tìm ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                    <?php if (!empty($search_code)): // Nếu đang tìm kiếm, hiển thị nút "Hủy" ?>
                        <a href="student.php" class="btn btn-secondary ms-2">Hủy</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-md-end">
                <?php if ($isTeacher): // Ẩn/hiện nút Create ?>
                    <a href="student/create_student.php" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Thêm sinh viên
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Mã sinh viên</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Giới tính</th>
                        <th scope="col">Ngành</th>
                        <th scope="col">Lớp</th>
                        <th scope="col">Email</th>
                        <th scope="col">SĐT</th>
                        <?php if ($isTeacher): // Ẩn/hiện cột Thao tác ?>
                            <th scope="col">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Dữ liệu $students đã được lấy ở đầu file
                    
                    if (empty($students)) {
                        // Hiển thị thông báo nếu không có kết quả
                        echo '<tr><td colspan="' . ($isTeacher ? 10 : 9) . '" class="text-center">Không tìm thấy sinh viên nào.</td></tr>';
                    } else {
                        foreach($students as $index => $stu){
                            $stt = $index + 1;
                    ?>
                        <tr>
                            <td><?= $stu["id"] ?? '' ?></td>
                            <td><?= $stu["student_code"] ?? '' ?></td>
                            <td><?= $stu["student_name"] ?? '' ?></td>
                            <td><?= isset($stu["student_date"]) && !empty($stu["student_date"]) ? date('d-m-Y', strtotime($stu["student_date"])) : '' ?></td>
                            <td><?= $stu["gender"] ?? '' ?></td>
                            <td><?= $stu["major"] ?? '' ?></td>
                            <td><?= $stu["class"] ?? '' ?></td>
                            <td><?= $stu["email"] ?? '' ?></td>
                            <td><?= $stu["phone_number"] ?? '' ?></td>
                            <?php if ($isTeacher): // Ẩn/hiện các nút Edit/Delete ?>
                                <td>
                                    <a href="student/edit_student.php?id=<?= $stu["id"] ?? '' ?>"
                                    class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="../handle/student_process.php?action=delete&id=<?= $stu["id"] ?? '' ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php 
                        } // Kết thúc vòng lặp
                    } // Kết thúc else
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>