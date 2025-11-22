<?php
$pageTitle = "Quản lý Học phần"; // Đặt tiêu đề
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
// START: LẤY VAI TRÒ NGƯỜI DÙNG
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');
// END: LẤY VAI TRÒ NGƯỜI DÙNG

include './sidebar.php'; // Include sidebar
?>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        <h3 class="mb-4">DANH SÁCH HỌC PHẦN</h3> <?php
        // Hiển thị thông báo
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
        
        <?php if ($isTeacher || $isAdmin): // Ẩn/hiện nút Create ?>
            <div class="text-end mb-3"> <a href="subject/create_subject.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Thêm học phần
                </a>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 5%;">STT</th> <th scope="col">Mã học phần</th>
                        <th scope="col">Tên học phần</th>
                        <th scope="col">Số tín chỉ</th>
                        <?php if ($isTeacher || $isAdmin): // Ẩn/hiện cột Thao tác ?>
                            <th scope="col">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../functions/subject_functions.php';
                    
                    $subjects = getAllSubjects();
                    $stt = 1; // Khởi tạo biến đếm
                    
                    foreach ($subjects as $subject) {
                    ?>
                        <tr>
                            <td><?= $stt++ ?></td> <td><?= $subject["subject_code"] ?></td>
                            <td><?= $subject["subject_name"] ?></td>
                            <td><?= $subject["credits"] ?></td>
                            <?php if ($isTeacher || $isAdmin): // Ẩn/hiện các nút Edit/Delete ?>
                                <td>
                                    <a href="subject/edit_subject.php?id=<?= $subject['id'] ?>" class="btn btn-warning btn-sm" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../handle/subject_process.php?action=delete&id=<?= $subject['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa học phần này?');" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
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
    </div>
</div>
<?php include './layout_footer.php'; // Include footer ?>