<?php
// Sử dụng __DIR__ để tính toán đường dẫn chính xác
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

// LẤY THÔNG TIN VAI TRÒ
$currentUser = getLoggedInUser();
$isTeacher = ($currentUser && $currentUser['role'] === 'teacher');
$isStudent = ($currentUser && $currentUser['role'] === 'student');
$isAdmin = ($currentUser && $currentUser['role'] === 'admin');

// Lấy tên tệp hiện tại để active link
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php
        // Tính toán đường dẫn CSS dựa trên độ sâu thư mục
        $pathPrefix = (strpos($_SERVER['PHP_SELF'], '/views/student/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/class/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/subject/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/grade/') !== false) ? '../../' : '../';
    ?>
    <link rel="stylesheet" href="<?php echo $pathPrefix; ?>css/sidebar.css">
    
    <title><?php echo $pageTitle ?? 'Quản lý Hồ sơ'; ?></title>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo $pathPrefix; ?>views/dashboard.php" class="d-flex align-items-center text-decoration-none text-dark">
                    <img src="<?php echo $pathPrefix; ?>images/fitdnu_logo.png" alt="FIT-DNU Logo" height="40" class="me-2"/>
                    <h5 class="mb-0">Quản lý Hồ sơ</h5>
                </a>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>views/dashboard.php"><i class="bi bi-grid-fill me-2"></i>Dashboard</a>
                </li>
                
                <?php // MENU QUẢN LÝ: Hiển thị cho cả Teacher và Admin ?>
                <?php if ($isTeacher || $isAdmin): ?>
                <li class="<?php echo ($currentPage == 'student.php') ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>views/student.php"><i class="bi bi-people-fill me-2"></i>Quản lý sinh viên</a>
                </li>
                <?php elseif ($isStudent): ?>
                <li class="<?php echo ($currentPage == 'student.php') ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>views/student.php"><i class="bi bi-person-fill me-2"></i>Thông tin cá nhân</a>
                </li>
                <?php endif; ?>

                <?php // MENU HỌC PHẦN: Teacher và Admin ?>
                <?php if ($isTeacher || $isAdmin): ?>
                <li class="<?php echo ($currentPage == 'subject.php') ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>views/subject.php"><i class="bi bi-book-fill me-2"></i>Quản lý học phần</a>
                </li>
                <?php endif; ?>

                <?php // MENU ĐIỂM: Đổi tên dựa trên vai trò ?>
                <li class="<?php echo ($currentPage == 'grade.php') ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>views/grade.php">
                        <i class="bi bi-pencil-square me-2"></i>
                        <?php echo ($isTeacher || $isAdmin) ? 'Quản lý điểm' : 'Xem điểm'; ?>
                    </a>
                </li>
                
                <?php // MENU LỚP HỌC: Teacher và Admin quản lý, Student xem ?>
                <li class="<?php echo ($currentPage == 'class.php') ? 'active' : ''; ?>">
                    <a href="<?php echo $pathPrefix; ?>views/class.php">
                        <i class="bi bi-collection-fill me-2"></i>
                        <?php echo ($isTeacher || $isAdmin) ? 'Quản lý lớp học' : 'Lớp của tôi'; ?>
                    </a>
                </li>
            </ul>

            <div class="user-info">
                <?php if ($currentUser): ?>
                    <div class="mb-2">
                        Chào, <strong><?= htmlspecialchars($currentUser['username']) ?></strong>
                        <br>
                        <small>(Vai trò: <?= htmlspecialchars(ucfirst($currentUser['role'])) ?>)</small>
                    </div>
                <?php endif; ?>
                <a href="<?php echo $pathPrefix; ?>handle/logout_process.php" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                </a>
            </div>
        </nav>

        <div id="content">