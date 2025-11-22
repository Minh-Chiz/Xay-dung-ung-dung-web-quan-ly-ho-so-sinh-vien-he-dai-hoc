<?php
$pageTitle = "Chi tiết Lớp học";
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');

// Kiểm tra ID lớp học
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../class.php?error=Không tìm thấy ID lớp học");
    exit;
}
$id = $_GET['id'];

// Lấy thông tin lớp học
require_once __DIR__ . '/../../functions/class_functions.php';
$class = getClassById($id);

if (!$class) {
    header("Location: ../class.php?error=Không tìm thấy lớp học");
    exit;
}

// Lấy danh sách sinh viên
require_once __DIR__ . '/../../functions/student_functions.php';
$students = getStudentsByClassAttributes($class['class_name'], $class['class_code']);
$studentCount = count($students);

// --- PHÂN QUYỀN ---
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');

include __DIR__ . '/../sidebar.php';
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <?php
        // Hiển thị thông báo lỗi/thành công (NẾU CÓ)
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>' . htmlspecialchars($_GET['success']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>' . htmlspecialchars($_GET['error']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Lớp: <?php echo htmlspecialchars($class['class_name']); ?></h3>
                <p class="text-muted mb-0">Mã lớp: <?php echo htmlspecialchars($class['class_code']); ?></p>
            </div>
            
            <div class="d-flex gap-2">
                <?php if ($isTeacher || $isAdmin): ?>
                    <a href="../student/create_student.php?prefill_class=<?php echo urlencode($class['class_name']); ?>" 
                        class="btn btn-success h-100 d-flex align-items-center">
                        <i class="bi bi-person-plus-fill me-2"></i> Thêm Sinh Viên vào lớp này
                    </a>
                <?php endif; ?>

                <div class="bg-primary text-white rounded p-3 text-center" style="min-width: 150px;">
                    <h2 class="m-0 fw-bold"><?php echo $studentCount; ?></h2>
                    <small>Sinh viên</small>
                </div>
            </div>
        </div>

        <h5 class="mb-3 border-bottom pb-2">Danh sách thành viên</h5>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">STT</th>
                        <th scope="col">Mã SV</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Ngày sinh</th>
                        <th scope="col">Giới tính</th>
                        <?php if ($isTeacher || $isAdmin): ?>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="<?php echo ($isTeacher || $isAdmin) ? 7 : 5; ?>" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                Lớp này chưa có sinh viên nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $stt = 1;
                        foreach ($students as $stu): ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo htmlspecialchars($stu['student_code']); ?></td>
                            <td><?php echo htmlspecialchars($stu['student_name']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($stu['student_date']))); ?></td>
                            <td><?php echo htmlspecialchars($stu['gender']); ?></td>
                            
                            <?php if ($isTeacher || $isAdmin): ?>
                                <td><?php echo htmlspecialchars($stu['phone_number'] ?? '---'); ?></td>
                                <td style="white-space: nowrap;">
                                    <a href="../student/student_details.php?id=<?php echo $stu['id']; ?>" 
                                        class="btn btn-info btn-sm text-white" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="../student/edit_student.php?id=<?php echo $stu['id']; ?>&redirect=class&class_id=<?php echo $class['id']; ?>" 
                                        class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="../../handle/student_process.php?action=delete&id=<?php echo $stu['id']; ?>" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Cảnh báo: Bạn có chắc muốn xóa sinh viên này khỏi hệ thống?');" 
                                        title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light border-0 text-end p-3">
        <a href="../class.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Quay lại
        </a>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>