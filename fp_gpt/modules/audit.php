<?php
include 'db.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 新增審核記錄
    if (isset($_POST['audit_id']) && !isset($_POST['update_audit'])) {
        $audit_id = $_POST['audit_id'];
        $audit_name = $_POST['audit_name'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO AUDIT (audit_id, audit_name, school, grade, tel, address, status)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$audit_id, $audit_name, $school, $grade, $tel, $address, $status]);
        echo "<p>審核記錄新增成功！</p>";
    }

    // 修改審核記錄
    if (isset($_POST['update_audit'])) {
        $audit_id = $_POST['audit_id'];
        $audit_name = $_POST['audit_name'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE AUDIT 
                               SET audit_name = ?, school = ?, grade = ?, tel = ?, address = ?, status = ? 
                               WHERE audit_id = ?");
        $stmt->execute([$audit_name, $school, $grade, $tel, $address, $status, $audit_id]);
        echo "<p>審核記錄修改成功！</p>";
    }
}

// 查詢功能
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM AUDIT WHERE audit_name LIKE ? OR audit_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM AUDIT");
}
?>

<h2>審核管理</h2>
<form method="post">
    <label>ID：<input type="text" name="audit_id" required></label><br>
    <label>姓名：<input type="text" name="audit_name" required></label><br>
    <label>學校：<input type="text" name="school"></label><br>
    <label>年級：<input type="text" name="grade"></label><br>
    <label>電話：<input type="text" name="tel"></label><br>
    <label>地址：<input type="text" name="address"></label><br>
    <label>狀態：<input type="text" name="status"></label><br>
    <button type="submit">新增審核</button>
</form>

<h3>審核記錄列表</h3>
<form method="get">
    <label>查詢審核：<input type="text" name="search" placeholder="輸入姓名或ID"></label>
    <button type="submit">查詢</button>
</form>
<ul>
<?php
while ($row = $stmt->fetch()) {
    echo "<li>
            {$row['audit_name']} - 學校：{$row['school']} - 年級：{$row['grade']} - 狀態：{$row['status']}
            <form method='post' style='display:inline'>
                <input type='hidden' name='update_audit' value='1'>
                <input type='hidden' name='audit_id' value='{$row['audit_id']}'>
                <input type='hidden' name='audit_name' value='{$row['audit_name']}'>
                <input type='hidden' name='school' value='{$row['school']}'>
                <input type='hidden' name='grade' value='{$row['grade']}'>
                <input type='hidden' name='tel' value='{$row['tel']}'>
                <input type='hidden' name='address' value='{$row['address']}'>
                <input type='hidden' name='status' value='{$row['status']}'>
                <button type='submit'>修改</button>
            </form>
         </li>";
}
?>
</ul>
<?php include 'templates/footer.php'; ?>
