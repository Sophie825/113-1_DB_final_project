<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

include_once 'db/db.php';
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 管理主頁</title>
    <link rel="stylesheet" href="E:\DB_FinalProject\DB_CSS\style_admin_main.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo"><a href="web.php">BEST MATH</a></div>
            <button class="login-button">
                <a href="logout.php">登出</a>
            </button>
        </header>
        <main class="main-content">
            <h2>歡迎，<?= htmlspecialchars($_SESSION['username']); ?>！</h2>
            <h2>BEST MATH 資料管理系統</h2>
            <div class="management-options">
                <div class="option-card">
                    <h3>學生資料管理</h3>
                    <button class="click_in"><a href="modules/student.php">進入</a></button>
                </div>
                <div class="option-card">
                    <h3>老師資料管理</h3>
                    <button class="click_in"><a href="modules/teacher.php">進入</a></button>
                </div>
                <div class="option-card">
                    <h3>輔導老師資料管理</h3>
                    <button class="click_in"><a href="/modules/mentor.php">進入</a></button>
                </div>
                <div class="option-card">
                    <h3>班級資料管理</h3>
                    <button class="click_in"><a href="modules/classoom.php">進入</a></button>
                </div>
                <div class="option-card">
                    <h3>試聽資料管理</h3>
                    <button class="click_in"><a href="modules/audit.php">進入</a></button>
                </div>
            </div>
        </main>
    </div>
</body>
</html>