<nav>
    <?php if (isset($_SESSION['role'])): ?>
        <span>歡迎，<?= htmlspecialchars($_SESSION['username']); ?>！</span>
        {% comment %} <a href="dashboard.php">主頁</a> {% endcomment %}
        <a href="logout.php">登出</a>
    <?php else: ?>
        {% comment %} <a href="index.php">登入</a> {% endcomment %}
    <?php endif; ?>
</nav>
<hr>