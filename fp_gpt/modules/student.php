<?php
include 'db.php';
include 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 新增學生
    if (isset($_POST['student_id']) && !isset($_POST['update_student'])) {
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO STUDENT (student_id, student_name, school, grade, tel, address, status)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $student_name, $school, $grade, $tel, $address, $status]);
        echo "<p>學生新增成功！</p>";
    }

    // 修改學生
    if (isset($_POST['update_student'])) {
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE STUDENT 
                               SET student_name = ?, school = ?, grade = ?, tel = ?, address = ?, status = ? 
                               WHERE student_id = ?");
        $stmt->execute([$student_name, $school, $grade, $tel, $address, $status, $student_id]);
        echo "<p>學生修改成功！</p>";
    }
}

// 查詢功能
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM STUDENT WHERE student_name LIKE ? OR student_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM STUDENT");
}
?>

<h2>學生管理</h2>
<form method="post">
    <label>ID：<input type="text" name="student_id" required></label><br>
    <label>姓名：<input type="text" name="student_name" required></label><br>
    <label>學校：<input type="text" name="school"></label><br>
    <label>年級：<input type="number" name="grade"></label><br>
    <label>電話：<input type="text" name="tel"></label><br>
    <label>地址：<input type="text" name="address"></label><br>
    <label>狀態：<input type="text" name="status"></label><br>
    <button type="submit">新增學生</button>
</form>

<h3>學生列表</h3>
<form method="get">
    <label>查詢學生：<input type="text" name="search" placeholder="輸入姓名或ID"></label>
    <button type="submit">查詢</button>
</form>
<ul>
<?php
while ($row = $stmt->fetch()) {
    echo "<li>
            {$row['student_name']} - {$row['school']} - {$row['
