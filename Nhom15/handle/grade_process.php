<?php
// Nhúng file functions cho grade và các file cần thiết khác (nếu cần cho dropdown)
require_once __DIR__ . '/../functions/grade_functions.php';
require_once __DIR__ . '/../functions/auth.php'; 

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

// Chuyển hướng xử lý dựa trên action
switch ($action) {
    case 'create':
        checkRole(['teacher'], '../views/grade.php', "Bạn không có quyền thêm điểm số."); 
        handleCreateGrade();
        break;
    case 'edit':
        checkRole(['teacher'], '../views/grade.php', "Bạn không có quyền chỉnh sửa điểm số."); 
        handleEditGrade();
        break;
    case 'delete':
        checkRole(['teacher'], '../views/grade.php', "Bạn không có quyền xóa điểm số."); 
        handleDeleteGrade();
        break;
    default:
        // Nếu không có action, có thể là trường hợp chỉ lấy dữ liệu để hiển thị
        break;
}

/**
 * Lấy tất cả danh sách điểm số (bao gồm tên sinh viên, học phần)
 * Dùng cho views/grade.php
 */
function handleGetAllGrades() {
    return getAllGrades();
}

/**
 * Lấy thông tin một bản ghi điểm số theo ID
 * Dùng cho views/grade/edit_grade.php
 */
function handleGetGradeById($id) {
    return getGradeById($id);
}

/**
 * Xử lý tạo điểm số mới
 */
function handleCreateGrade () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/grade.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['student_id']) || !isset($_POST['subject_id']) || !isset($_POST['grade'])) {
        header("Location: ../views/grade/create_grade.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    
    $student_id = (int)$_POST['student_id'];
    $subject_id = (int)$_POST['subject_id'];
    $grade = floatval($_POST['grade']);
    $term = trim($_POST['term'] ?? '');

    if ($student_id <= 0 || $subject_id <= 0 || $grade < 0 || $grade > 10) {
        header("Location: ../views/grade/create_grade.php?error=Dữ liệu không hợp lệ. Vui lòng kiểm tra Sinh viên, Học phần và Điểm (0-10)");
        exit();
    }

    $result = addGrade($student_id, $subject_id, $grade, $term);

    if ($result['success']) {
        header("Location: ../views/grade.php?success=Thêm điểm số thành công");
        exit();
    } else {
        // Hiển thị thông báo lỗi chi tiết
        $errorMessage = "Thêm điểm số thất bại. Lý do: " . $result['message'];
        header("Location: ../views/grade/create_grade.php?error=" . urlencode($errorMessage));
    }
    exit();
}

/**
 * Xử lý chỉnh sửa điểm số
 */
function handleEditGrade() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/grade.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['student_id']) || !isset($_POST['subject_id']) || !isset($_POST['grade'])) {
        header("Location: ../views/grade.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = (int)$_POST['id'];
    $student_id = (int)$_POST['student_id'];
    $subject_id = (int)$_POST['subject_id'];
    $grade = floatval($_POST['grade']);
    $term = trim($_POST['term'] ?? '');

    if ($id <= 0 || $student_id <= 0 || $subject_id <= 0 || $grade < 0 || $grade > 10) {
        header("Location: ../views/grade/edit_grade.php?id=" . $id . "&error=Dữ liệu không hợp lệ. Vui lòng kiểm tra ID, Sinh viên, Học phần và Điểm (0-10)");
        exit();
    }

    $result = updateGrade($id, $student_id, $subject_id, $grade, $term);

    if ($result['success']) {
        header("Location: ../views/grade.php?success=Cập nhật điểm số thành công");
        exit();
    } else {
        // Hiển thị thông báo lỗi chi tiết
        $errorMessage = "Cập nhật điểm số thất bại. Lý do: " . $result['message'];
        header("Location: ../views/grade/edit_grade.php?id=" . $id . "&error=" . urlencode($errorMessage));
        exit();
    }
}

/**
 * Xử lý xóa điểm số
 */
function handleDeleteGrade() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/grade.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/grade.php?error=Không tìm thấy ID điểm số");
        exit();
    }

    $id = $_GET['id'];

    // Validate ID là số
    if (!is_numeric($id)) {
        header("Location: ../views/grade.php?error=ID điểm số không hợp lệ");
        exit();
    }

    // Gọi function để xóa điểm số (vẫn trả về bool)
    $result = deleteGrade($id);

    if ($result) {
        header("Location: ../views/grade.php?success=Xóa điểm số thành công");
    } else {
        header("Location: ../views/grade.php?error=Xóa điểm số thất bại");
    }
    exit();
}
?>
