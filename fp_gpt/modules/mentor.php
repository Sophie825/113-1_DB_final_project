<?php
include 'db.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 新增導師
    if (isset($_POST['mentor_id']) && !isset($_POST['update_mentor'])) {
        $mentor_id = $_POST['mentor_id'];
        $mentor_name = $_POST['mentor_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO MENTOR (mentor_id, mentor_name, tel, address, status)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$mentor_id, $mentor_name, $tel, $address, $status]);
        echo "<p>導師新增成功！</p>";
    }

    // 修改導師
    if (isset($_POST['update_mentor'])) {
        $mentor_id = $_POST['mentor_id'];
        $mentor_name = $_POST['mentor_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE MENTOR 
                               SET mentor_name = ?, tel = ?, address = ?, status = ? 
                               WHERE mentor_id = ?");
        $stmt->execute([$mentor_name, $tel, $address, $status, $mentor_id]);
        echo "<p>導師修改成功！</p>";
    }
}

// 查詢功能
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM MENTOR WHERE mentor_name LIKE ? OR mentor_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM MENTOR");
}
?>

<h2>導師管理</h2>
<form method="post">
    <label>ID：<input type="number" name="mentor_id" required></label><br>
    <label>姓名：<input type="text" name="mentor_name" required></label><br>
    <label>電話：<input type="text" name="tel"></label><br>
    <label>地址：<input type="text" name="address"></label><br>
    <label>狀態：<input type="text" name="status"></label><br>
    <button type="submit">新增導師</button>
</form>

<h3>導師列表</h3>
<form method="get">
    <label>查詢導師：<input type="text" name="search" placeholder="輸入姓名或ID"></label>
    <button type="submit">查詢</button>
</form>
<ul>
<?php
while ($row = $stmt->fetch()) {
    echo "<li>
            {$row['mentor_name']} - 電話：{$row['tel']} - 地址：{$row['address']}
            <form method='post' style='display:inline'>
                <input type='hidden' name='update_id' value='{$row['mentor_id']}'>
                <button type='submit'>修改</button>
            </form>
         </li>";
}
?>
</ul>
<?php include 'templates/footer.php'; ?>
