<?php
$pageTitle = "Lớp học"; 
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

// LẤY VAI TRÒ NGƯỜI DÙNG
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isStudent = ($currentUser && $currentUser['role'] === 'student');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');

// Load các hàm xử lý cần thiết
require_once '../functions/class_functions.php';
require_once '../functions/student_functions.php';

include './sidebar.php';
?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <h3 class="mb-4">
            <?php echo $isStudent ? 'LỚP HỌC CỦA TÔI' : 'DANH SÁCH LỚP HỌC'; ?>
        </h3>
        
        <?php
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
        
        <?php if ($isTeacher || $isAdmin): ?>
            <div class="text-end mb-3">
                <a href="class/create_class.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Thêm lớp học
                </a>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">STT</th>
                        <th scope="col">Mã lớp</th>
                        <th scope="col">Tên lớp</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 1. Lấy danh sách tất cả lớp học ban đầu
                    $classes = getAllClasses();
                    
                    // Biến để lưu tên lớp trong hồ sơ SV (dùng để hiển thị thông báo debug nếu cần)
                    $studentClassInfo = '';

                    // 2. Nếu là Sinh viên, thực hiện lọc danh sách
                    if ($isStudent) {
                        // Lấy thông tin sinh viên dựa trên mã đăng nhập (username)
                        $studentInfo = getStudentByCode($currentUser['username']);
                        
                        if ($studentInfo) {
                            // Hàm chuẩn hóa chuỗi để so sánh (bỏ khoảng trắng thừa, chuyển về chữ thường)
                            $normalize = function($str) {
                                if (!$str) return '';
                                return mb_strtolower(trim($str), 'UTF-8');
                            };

                            // Lấy thông tin lớp từ bảng students
                            $myClassRaw = $studentInfo['class'] ?? '';
                            $studentClassInfo = $myClassRaw;
                            $myClass = $normalize($myClassRaw);
                            
                            // Lọc danh sách $classes: Chỉ giữ lại lớp có Tên hoặc Mã trùng với hồ sơ SV
                            $classes = array_filter($classes, function($c) use ($myClass, $normalize) {
                                $className = $normalize($c['class_name']);
                                $classCode = $normalize($c['class_code']);
                                
                                // Trả về true nếu khớp một trong hai
                                return ($myClass === $className || $myClass === $classCode);
                            });
                        } else {
                            // Trường hợp hy hữu: Không tìm thấy thông tin sinh viên trong bảng students
                            $classes = [];
                        }
                    }
                    
                    // 3. Hiển thị dữ liệu ra bảng
                    if (empty($classes)) {
                        echo '<tr><td colspan="4" class="text-center py-4">';
                        echo '<i class="bi bi-search text-muted fs-2 d-block mb-2"></i>';
                        
                        if ($isStudent) {
                            echo 'Bạn chưa được phân vào lớp học nào hoặc tên lớp không khớp với hệ thống.<br>';
                            if (!empty($studentClassInfo)) {
                                echo '<small class="text-muted">Lớp trong hồ sơ của bạn là: "<strong>' . htmlspecialchars($studentClassInfo) . '</strong>"</small>';
                            }
                        } else {
                            echo 'Chưa có lớp học nào trong hệ thống.';
                        }
                        echo '</td></tr>';
                    } else {
                        $stt = 1;
                        foreach ($classes as $class) {
                    ?>
                        <tr>
                            <td><?= $stt++ ?></td>
                            <td><?= htmlspecialchars($class["class_code"]) ?></td>
                            <td><?= htmlspecialchars($class["class_name"]) ?></td>
                            
                            <td>
                                <a href="class/class_details.php?id=<?= $class['id'] ?>"
                                    class="btn btn-info btn-sm text-white" title="Xem danh sách thành viên">
                                    <i class="bi bi-eye"></i> Xem danh sách
                                </a>

                                <?php if ($isTeacher || $isAdmin): // Các nút sửa/xóa chỉ dành cho GV ?>
                                    <a href="class/edit_class.php?id=<?= $class['id'] ?>" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="../handle/class_process.php?action=delete&id=<?= $class['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?');" title="Xóa">
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

<?php include './layout_footer.php'; ?>