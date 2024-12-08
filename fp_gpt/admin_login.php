<?php
session_start();
include 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查詢用戶資料
    $stmt = $pdo->prepare("
        SELECT worker_id, worker_name, password, status 
        FROM WORKERS 
        WHERE worker_name = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 登入成功，保存會話資訊
        $_SESSION['user_id'] = $user['worker_id'];
        $_SESSION['username'] = $user['worker_name'];
        $_SESSION['role'] = $user['status']; // 身份：如 "admin", "student", "mentor"

        // 跳轉到管理主頁
        header('Location: admin_main.php');
        exit();
    } else {
        // 登入失敗
        echo "<p>帳號或密碼錯誤！</p>";
        echo "<a href='admin_login.html'>返回登入頁面</a>";
    }
}
?>