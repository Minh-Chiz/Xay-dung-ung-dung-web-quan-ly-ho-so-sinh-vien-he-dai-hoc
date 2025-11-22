<?php
$pageTitle = "Quản lý Điểm số";
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

// LẤY VAI TRÒ NGƯỜI DÙNG
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isStudent = ($currentUser && $currentUser['role'] === 'student');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');

if ($isStudent) {
    $pageTitle = "Bảng điểm cá nhân";
}

// Load các file cần thiết
require_once '../handle/grade_process.php';
require_once '../functions/class_functions.php'; // Để lấy danh sách lớp cho dropdown

// Lấy tham số tìm kiếm từ URL
$search_code = $_GET['search_code'] ?? '';
$search_class = $_GET['search_class'] ?? '';

// Lấy danh sách lớp để hiển thị vào Dropdown
$classList = [];
if ($isTeacher || $isAdmin) {
    $classList = getAllClasses();
}

include './sidebar.php';
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <h3 class="mb-4"><?= $pageTitle ?></h3>
        
        <?php
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
        
        <?php if ($isTeacher || $isAdmin): // THANH TÌM KIẾM ?>
            <div class="row mb-3">
                <div class="col-md-9">
                    <form action="grade.php" method="GET" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search_code"
                                placeholder="Nhập mã sinh viên..."
                                value="<?= htmlspecialchars($search_code) ?>">
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
                        
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Tìm kiếm
                            </button>
                            <?php if (!empty($search_code) || !empty($search_class)): ?>
                                <a href="grade.php" class="btn btn-outline-secondary ms-1">Hủy</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-3 text-end"> 
                    <a href="grade/create_grade.php" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Nhập điểm
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">STT</th>
                        <?php if ($isTeacher || $isAdmin): ?>
                        <th scope="col">Mã sinh viên</th>
                        <th scope="col">Tên sinh viên</th>
                        <th scope="col">Lớp</th> <?php endif; ?>
                        <th scope="col">Mã HP</th>
                        <th scope="col">Tên học phần</th>
                        <th scope="col">Điểm số</th>
                        <th scope="col">Học kỳ</th>
                        <?php if ($isTeacher || $isAdmin): ?>
                            <th scope="col">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grades = [];
                    if ($isTeacher || $isAdmin) {
                        // Gọi hàm với tham số tìm kiếm
                        $grades = handleGetAllGrades(null, $search_code, $search_class);
                    } elseif ($isStudent) {
                        // Sinh viên chỉ xem điểm của mình
                        require_once '../handle/student_process.php';
                        $student = handleGetStudentByCode($currentUser['username']);
                        
                        if ($student) {
                            $grades = handleGetAllGrades($student['id'], $search_code, $search_class);
                        }
                    }
                    
                    if (empty($grades)): ?>
                        <tr>
                            <td colspan="<?= $isTeacher ? 9 : 5 ?>" class="text-center">Chưa có điểm số nào được nhập.</td>
                        </tr>
                    <?php else:
                        $stt = 1; // KHỞI TẠO biến đếm STT
                        foreach ($grades as $grade) {
                    ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            
                            <?php if ($isTeacher || $isAdmin): ?>
                            <td><?= $grade["student_code"] ?></td>
                            <td><?= $grade["student_name"] ?></td>
                            <td><?= $grade["class"] ?? '' ?></td>
                            <?php endif; ?>
                            <td><?= $grade["subject_code"] ?></td>
                            <td><?= $grade["subject_name"] ?></td>
                            <td class="grade-column <?php echo ($grade["grade"] < 4.0) ? 'text-danger' : 'text-success'; ?>">
                                <?= number_format($grade["grade"], 1) ?>
                            </td>
                            <td><?= $grade["term"] ?></td>
                            <?php if ($isTeacher): ?>
                                <td>
                                    <a href="grade/edit_grade.php?id=<?= $grade['id'] ?>" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../handle/grade_process.php?action=delete&id=<?= $grade['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa điểm số này?');" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php
                        }
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include './layout_footer.php'; ?>