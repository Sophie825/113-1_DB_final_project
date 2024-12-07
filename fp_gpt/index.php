<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
include 'templates/header.php';
?>
<h1>補習班管理系統 - 登入</h1>
<form action="login.php" method="post">
    <label>帳號：<input type="text" name="username" required></label><br>
    <label>密碼：<input type="password" name="password" required></label><br>
    <button type="submit">登入</button>
</form>
<?php include 'templates/footer.php'; ?>