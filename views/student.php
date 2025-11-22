<?php
$pageTitle = "Quản lý Sinh viên"; // Đặt tiêu đề cho trang
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isStudent = ($currentUser && $currentUser['role'] === 'student');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');

require_once '../handle/student_process.php';
// Thêm dòng này để lấy danh sách các lớp cho dropdown
require_once '../functions/class_functions.php'; 

// Lấy tham số tìm kiếm từ URL
$search_code = $_GET['search_code'] ?? '';
$search_class = $_GET['search_class'] ?? ''; // Lấy tham số lớp

$students = [];
$classList = []; // Danh sách lớp để hiển thị dropdown

if ($isTeacher || $isAdmin) {
    $pageTitle = "Quản lý Sinh viên";
    // Lấy danh sách tất cả lớp học để đổ vào dropdown
    $classList = getAllClasses(); 
    // Gọi hàm lấy sinh viên với cả 2 tham số tìm kiếm
    $students = handleGetAllStudents($search_code, $search_class);
} elseif ($isStudent) {
    $pageTitle = "Thông tin cá nhân";
    $students = handleGetAllStudents($currentUser['username']); // Lọc theo username
}

include './sidebar.php'; // Include sidebar
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <h3 class="mb-4"><?= $pageTitle ?></h3> <?php
        // Hiển thị thông báo (nếu có)
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($_GET['success']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($_GET['error']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        ?>

        <?php // Thanh tìm kiếm và nút Thêm
        if ($isTeacher || $isAdmin): ?>
        <div class="row mb-3">
            <div class="col-md-8">
                <form action="student.php" method="GET" class="row g-2">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="search_code"
                            placeholder="Nhập mã sinh viên..."
                            value="<?php echo htmlspecialchars($search_code); ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <select class="form-select" name="search_class">
                            <option value="">-- Tất cả các lớp --</option>
                            <?php foreach ($classList as $cl): ?>
                                <option value="<?= htmlspecialchars($cl['class_name']) ?>"
                                    <?= ($search_class === $cl['class_name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cl['class_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
                
                <?php if (!empty($search_code) || !empty($search_class)): ?>
                    <div class="mt-2">
                        <a href="student.php" class="text-secondary text-decoration-none">
                            <i class="bi bi-x-circle"></i> Xóa bộ lọc
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <a href="student/create_student.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Thêm sinh viên
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">STT</th> <th scope="col">Mã sinh viên</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Lớp</th>
                        <th scope="col" style="width: 15%;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($students)) {
                        echo '<tr><td colspan="5" class="text-center">Không tìm thấy sinh viên nào.</td></tr>';
                    } else {
                        $stt = 1; // Khởi tạo biến đếm
                        foreach($students as $index => $stu){
                    ?>
                        <tr>
                            <td><?= $stt++ ?></td> <td><?= $stu["student_code"] ?? '' ?></td>
                            <td><?= $stu["student_name"] ?? '' ?></td>
                            <td><?= $stu["class"] ?? '' ?></td>
                            <td>
                                <a href="student/student_details.php?id=<?= $stu["id"] ?? '' ?>"
                                    class="btn btn-info btn-sm" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <?php if ($isTeacher || $isAdmin): // Các nút thao tác ?>
                                    <a href="student/edit_student.php?id=<?= $stu["id"] ?? '' ?>"
                                    class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../handle/student_process.php?action=delete&id=<?= $stu["id"] ?? '' ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include './layout_footer.php'; // Include footer ?>