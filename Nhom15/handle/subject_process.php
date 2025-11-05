<?php
require_once __DIR__ . '/../functions/subject_functions.php';
require_once __DIR__ . '/../functions/auth.php'; // Đảm bảo đã có dòng này cho phân quyền

// Kiểm tra action được truyền qua URL hoặc POST và khởi tạo giá trị mặc định
// Nếu không tìm thấy trong GET hoặc POST, $action sẽ là chuỗi rỗng ''
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Chuyển hướng xử lý dựa trên action
switch ($action) {
    case 'create':
        checkRole(['teacher'], '../views/subject.php', "Bạn không có quyền thêm học phần.");
        handleCreateSubject();
        break;
    case 'edit':
        checkRole(['teacher'], '../views/subject.php', "Bạn không có quyền chỉnh sửa học phần.");
        handleEditSubject();
        break;
    case 'delete':
        checkRole(['teacher'], '../views/subject.php', "Bạn không có quyền xóa học phần.");
        handleDeleteSubject();
        break;
    default:
        // Hành động mặc định hoặc không hợp lệ, không cần làm gì
        break;
}

/**
 * Lấy tất cả danh sách học phần
 * Dùng cho views/subject.php
 */
function handleGetAllSubjects() {
    return getAllSubjects();
}

/**
 * Lấy thông tin một học phần theo ID
 * Dùng cho views/subject/edit_subject.php
 */
function handleGetSubjectById($id) {
    return getSubjectById($id);
}

/**
 * Xử lý tạo học phần mới
 */
function handleCreateSubject () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/subject.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['subject_code']) || !isset($_POST['subject_name']) || !isset($_POST['credits'])) {
        header("Location: ../views/subject/create_subject.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);
    $credits = (int)$_POST['credits']; // Ép kiểu về số nguyên

    // Validate dữ liệu
    if (empty($subject_code) || empty($subject_name) || $credits <= 0) {
        header("Location: ../views/subject/create_subject.php?error=Vui lòng điền đầy đủ thông tin và số tín chỉ phải lớn hơn 0");
        exit();
    }

    // Gọi hàm thêm học phần
    $result = addSubject($subject_code, $subject_name, $credits);

    if ($result) {
        header("Location: ../views/subject.php?success=Thêm học phần thành công");
        exit();
    } else {
        // CẬP NHẬT: Thêm thông báo rõ ràng hơn về lỗi trùng mã học phần
        header("Location: ../views/subject/create_subject.php?error=Thêm học phần thất bại. Mã học phần **" . htmlspecialchars($subject_code) . "** có thể đã tồn tại.");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa học phần
 */
function handleEditSubject() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/subject.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['subject_code']) || !isset($_POST['subject_name']) || !isset($_POST['credits'])) {
        header("Location: ../views/subject.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = $_POST['id'];
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);
    $credits = (int)$_POST['credits']; // Ép kiểu về số nguyên

    // Validate dữ liệu
    if (empty($subject_code) || empty($subject_name) || $credits <= 0) {
        header("Location: ../views/subject/edit_subject.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin và số tín chỉ phải lớn hơn 0");
        exit();
    }

    // Gọi function để cập nhật học phần
    $result = updateSubject($id, $subject_code, $subject_name, $credits);

    if ($result) {
        header("Location: ../views/subject.php?success=Cập nhật học phần thành công");
        exit();
    } else {
        header("Location: ../views/subject/edit_subject.php?id=" . $id . "&error=Cập nhật học phần thất bại (Mã học phần có thể đã tồn tại)");
        exit();
    }
}

/**
 * Xử lý xóa học phần
 */
function handleDeleteSubject() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/subject.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/subject.php?error=Không tìm thấy ID học phần");
        exit();
    }

    $id = $_GET['id'];

    // Validate ID là số
    if (!is_numeric($id)) {
        header("Location: ../views/subject.php?error=ID học phần không hợp lệ");
        exit();
    }

    // Gọi function để xóa học phần
    $result = deleteSubject($id);

    if ($result) {
        header("Location: ../views/subject.php?success=Xóa học phần thành công");
    } else {
        header("Location: ../views/subject.php?error=Xóa học phần thất bại");
    }
    exit();
}
?>