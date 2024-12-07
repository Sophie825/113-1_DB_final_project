<?php
session_start();
include 'db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 驗證帳號密碼
    $stmt = $pdo->prepare("SELECT worker_id, worker_name, status FROM WORKERS WHERE worker_name = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['worker_id'];
        $_SESSION['username'] = $user['worker_name'];
        $_SESSION['role'] = $user['status']; // 身份：如 "admin", "student", "mentor"
        header('Location: dashboard.php');
        exit();
    } else {
        echo "<p>帳號或密碼錯誤！</p>";
    }
}
?>