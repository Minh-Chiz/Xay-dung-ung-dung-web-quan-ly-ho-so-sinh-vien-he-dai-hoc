<?php
// Sử dụng __DIR__ để tính toán đường dẫn chính xác từ vị trí file hiện tại
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
$currentUser = getLoggedInUser();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php
    // Lấy tên tệp hiện tại (ví dụ: "index.php", "ve-chung-toi.php")
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button data-mdb-collapse-init class="navbar-toggler" type="button"
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img src="../images/fitdnu_logo.png" height="40"
                        alt="FIT-DNU Logo" loading="lazy" />
                </a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="student.php">Quản lý sinh viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="subject.php">Quản lý học phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="grade.php">Quản lý điểm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="class.php">Quản lý lớp học</a>
                    </li>
                </ul>
                </div>
            <div class="d-flex align-items-center">
                
                <?php if ($currentUser): ?>
                    <span class="text-dark me-3">
                        Chào, **<?= htmlspecialchars($currentUser['username']) ?>** <?php 
                            if (isset($currentUser['role'])) {
                                echo '(' . htmlspecialchars($currentUser['role']) . ')';
                            } 
                        ?>
                    </span>
                <?php endif; ?>
                
                <img src="../images/aiotlab_logo.png" class="rounded-circle me-2" height="25"
                    alt="AVT" loading="lazy" />

                <a class="btn btn-outline-secondary btn-sm" href="../handle/logout_process.php">
                    Logout
                </a>

            </div>
            </div>
        </nav>
    </body>

</html>
