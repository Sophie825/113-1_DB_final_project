<nav>
    <?php if (isset($_SESSION['role'])): ?>
        <span>歡迎，<?= htmlspecialchars($_SESSION['username']); ?>！</span>
        <a href="dashboard.php">主頁</a>
        <a href="logout.php">登出</a>
    <?php else: ?>
        <a href="index.php">登入</a>
    <?php endif; ?>
</nav>
<hr>