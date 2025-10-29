<?php

function getDbConnection(){
    $servername = "localhost";
    $username = "root";
    $password = "Chi@2005";
    $dbname = "qlhsv";
    $port = 3306;

    // Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    
    // Kiểm tra kết nối
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }
    // Thiết lập charset cho kết nối (quan trọng để hiển thị tiếng Việt đúng)
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}

?>