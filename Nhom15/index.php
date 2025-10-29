<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/footer.css" rel="stylesheet">
    <link href="./css/login.css" rel="stylesheet">
    <title>Quản lý hồ sơ sinh viên</title>
</head>

<body>
    <section class="vh-100 d-flex justify-contet-center align-items-center">
        <div class="container-fluid h-custome">
            <div class="d-flex flex-row align-items-center justify-content-center">
                <h2 class="text-primary" style="color: blanchedalmond;">Quản lý hồ sơ sinh viên</h2>
            </div>
            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <form action="./handle/login_process.php" method="POST">
                    <!-- Username input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="username" id="form3Example3" class="form-control form-control-lg"
                            placeholder="Nhập username" required />
                        <label class="form-label" for="form3Example3">Username</label>
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-3">
                        <input type="password" name="password" id="form3Example4" class="form-control form-control-lg"
                            placeholder="Nhập password" required />
                        <label class="form-label" for="form3Example4">Password</label>
                    </div>
                    <!-- Thông báo lỗi sử dụng session -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                        
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <button type="submit" data-mdb-button-init data-mdb-ripple-init
                            name="login"
                            class="btn btn-primary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                    </div>
                </form>
            </div>
        </div>

    </section>
</body>
<footer class="footer">
    Copyright © 2025 - Nhóm 15
</footer>
</html>