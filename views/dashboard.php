<?php
$pageTitle = "Dashboard"; // Đặt tiêu đề cho trang
include './sidebar.php'; // sidebar đã có logic kiểm tra quyền

require_once __DIR__ . '/../functions/student_functions.php';
require_once __DIR__ . '/../functions/class_functions.php';
require_once __DIR__ . '/../functions/subject_functions.php';
require_once __DIR__ . '/../functions/grade_functions.php';

$studentCount = 0;
$classCount = 0;
$subjectCount = 0;
$gradeCount = 0;
$studentInfo = null;

// [SỬA] Logic hiển thị thống kê: Admin nhìn thấy giống Teacher
if ($isTeacher || $isAdmin) {
    $allStudents = getAllStudents();
    $allClasses = getAllClasses();
    $allSubjects = getAllSubjects();
    $allGrades = getAllGrades();

    $studentCount = count($allStudents);
    $classCount = count($allClasses);
    $subjectCount = count($allSubjects);
    $gradeCount = count($allGrades);
} elseif ($isStudent) {
    $studentInfo = getStudentByCode($currentUser['username']);
    $student_id_filter = $studentInfo ? $studentInfo['id'] : 0;
    $allGrades = getAllGrades($student_id_filter);
    $gradeCount = count($allGrades);
}
?>

<div class="container-fluid">
    
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($_SESSION['success']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
        unset($_SESSION['success']);
    }
    ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-1">
                Chào mừng trở lại, <?php echo htmlspecialchars($currentUser['username']); ?>!
            </h4>
            <p class="card-text text-muted">
                <?php echo ($isTeacher || $isAdmin) ? 'Bạn đang đăng nhập với quyền quản trị hệ thống.' : 'Kiểm tra thông tin cá nhân và điểm số của bạn.'; ?>
            </p>
        </div>
    </div>

    <h3 class="mt-4 mb-3">Tổng quan</h3>
    
    <div class="row">
        <?php // --- PHẦN DÀNH CHO GIÁO VIÊN VÀ ADMIN --- ?>
        <?php if ($isTeacher || $isAdmin): ?>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-primary me-3"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <h2 class="card-text mb-0 fw-bold"><?php echo $studentCount; ?></h2>
                            <div class="text-muted">Sinh viên</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="student.php" class="text-decoration-none">Quản lý sinh viên <i class="bi bi-arrow-right-circle ms-1"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-success me-3"><i class="bi bi-collection-fill"></i></div>
                        <div>
                            <h2 class="card-text mb-0 fw-bold"><?php echo $classCount; ?></h2>
                            <div class="text-muted">Lớp học</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="class.php" class="text-decoration-none text-success">Quản lý lớp học <i class="bi bi-arrow-right-circle ms-1"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-warning me-3"><i class="bi bi-book-fill"></i></div>
                        <div>
                            <h2 class="card-text mb-0 fw-bold"><?php echo $subjectCount; ?></h2>
                            <div class="text-muted">Học phần</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="subject.php" class="text-decoration-none text-warning">Quản lý học phần <i class="bi bi-arrow-right-circle ms-1"></i></a>
                </div>
            </div>
        </div>
        
        <?php endif; ?>

        <?php // --- PHẦN DÀNH CHO SINH VIÊN --- ?>
        <?php if ($isStudent): ?>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-primary me-3"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <h5 class="card-title mb-0">Thông tin cá nhân</h5>
                            <p class="text-muted mb-0">Xem hồ sơ của bạn</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="student.php" class="text-decoration-none text-primary">Xem chi tiết <i class="bi bi-arrow-right-circle ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-success me-3"><i class="bi bi-collection-fill"></i></div>
                        <div>
                            <h5 class="card-title mb-0">Lớp của tôi</h5>
                            <p class="text-muted mb-0 fw-bold"><?php echo htmlspecialchars($studentInfo['class'] ?? 'Chưa cập nhật'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="class.php" class="text-decoration-none text-success">Xem danh sách lớp <i class="bi bi-arrow-right-circle ms-1"></i></a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php // --- PHẦN CHUNG (Điểm số) --- ?>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-danger me-3"><i class="bi bi-pencil-square"></i></div>
                        <div>
                            <h2 class="card-text mb-0 fw-bold"><?php echo $gradeCount; ?></h2>
                            <div class="text-muted"><?php echo ($isTeacher || $isAdmin) ? 'Bản ghi điểm' : 'Học phần đã có điểm'; ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="grade.php" class="text-decoration-none text-danger">Xem chi tiết <i class="bi bi-arrow-right-circle ms-1"></i></a>
                </div>
            </div>
        </div>
    </div> 

    <h3 class="mt-4 mb-3">Tác vụ nhanh</h3>
    <div class="row">
        <?php if ($isTeacher || $isAdmin): ?>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
                    <h5 class="mt-2">Thêm Sinh viên</h5>
                    <a href="student/create_student.php" class="btn btn-primary">Thêm ngay</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-plus-circle-dotted fs-1 text-danger"></i>
                    <h5 class="mt-2">Nhập điểm</h5>
                    <a href="grade/create_grade.php" class="btn btn-danger">Nhập điểm</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($isStudent): ?>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-journal-text fs-1 text-danger"></i>
                    <h5 class="mt-2">Bảng điểm</h5>
                    <a href="grade.php" class="btn btn-danger">Xem bảng điểm</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include './layout_footer.php'; ?>