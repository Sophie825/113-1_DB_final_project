<?php
include 'db.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 新增教師
    if (isset($_POST['teacher_id']) && !isset($_POST['update_teacher'])) {
        $teacher_id = $_POST['teacher_id'];
        $teacher_name = $_POST['teacher_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO TEACHER (teacher_id, teacher_name, tel, address, status)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$teacher_id, $teacher_name, $tel, $address, $status]);
        echo "<p>教師新增成功！</p>";
    }

    // 修改教師
    if (isset($_POST['update_teacher'])) {
        $teacher_id = $_POST['teacher_id'];
        $teacher_name = $_POST['teacher_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE TEACHER 
                               SET teacher_name = ?, tel = ?, address = ?, status = ? 
                               WHERE teacher_id = ?");
        $stmt->execute([$teacher_name, $tel, $address, $status, $teacher_id]);
        echo "<p>教師修改成功！</p>";
    }
}

// 查詢功能
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM TEACHER WHERE teacher_name LIKE ? OR teacher_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM TEACHER");
}
?>

<h2>教師管理</h2>
<form method="post">
    <label>ID：<input type="text" name="teacher_id" required></label><br>
    <label>姓名：<input type="text" name="teacher_name" required></label><br>
    <label>電話：<input type="text" name="tel"></label><br>
    <label>地址：<input type="text" name="address"></label><br>
    <label>狀態：<input type="text" name="status"></label><br>
    <button type="submit">新增教師</button>
</form>

<h3>教師列表</h3>
<form method="get">
    <label>查詢教師：<input type="text" name="search" placeholder="輸入姓名或ID"></label>
    <button type="submit">查詢</button>
</form>
<ul>
<?php
while ($row = $stmt->fetch()) {
    echo "<li>
            {$row['teacher_name']} - 電話：{$row['tel']} - 地址：{$row['address']}
            <form method='post' style='display:inline'>
                <input type='hidden' name='update_id' value='{$row['teacher_id']}'>
                <button type='submit'>修改</button>
            </form>
         </li>";
}
?>
</ul>
<?php include 'templates/footer.php'; ?>
