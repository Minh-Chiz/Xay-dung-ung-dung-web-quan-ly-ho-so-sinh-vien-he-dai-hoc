<?php
$pageTitle = "Dashboard"; // Đặt tiêu đề cho trang
include './sidebar.php'; 
// Bạn có thể thêm code PHP ở đây để lấy số liệu thống kê (ví dụ: đếm số SV, số Lớp)
// $studentCount = ...;
// $classCount = ...;
?>

<div class="container-fluid">
    <h3 class="mt-3 mb-4">Dashboard</h3>
    
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($_SESSION['success']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
        unset($_SESSION['success']); // Xóa session sau khi hiển thị
    }
    ?>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill me-2"></i> Sinh viên</h5>
                    <h2 class="card-text">0</h2> 
                    <p class="card-text"><small>Tổng số sinh viên</small></p>
                </div>
                <a href="student.php" class="card-footer text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-collection-fill me-2"></i> Lớp học</h5>
                    <h2 class="card-text">0</h2>
                    <p class="card-text"><small>Tổng số lớp học</small></p>
                </div>
                <a href="class.php" class="card-footer text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-book-fill me-2"></i> Học phần</h5>
                    <h2 class="card-text">0</h2>
                    <p class="card-text"><small>Tổng số học phần</small></p>
                </div>
                <a href="subject.php" class="card-footer text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-pencil-square me-2"></i> Điểm số</h5>
                    <h2 class="card-text">0</h2>
                    <p class="card-text"><small>Tổng số bản ghi điểm</small></p>
                </div>
                <a href="grade.php" class="card-footer text-white text-decoration-none">
                    Xem chi tiết <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h4>Chào mừng bạn đến với hệ thống quản lý!</h4>
        <p>Sử dụng thanh điều hướng bên trái để truy cập các chức năng.</p>
    </div>
</div>

<?php include './layout_footer.php'; ?>