<?php
include '../db/db.php'; 
include '../templates/header.php'; 

// 處理表單提交（新增或修改）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    $student_name = $_POST['student_name'];
    $school = $_POST['school'];
    $grade = $_POST['grade'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $parent_name = $_POST['parent_name'];
    $parent_tel = $_POST['parent_tel'];
    $version = $_POST['version'] ?? 0;

    if ($_POST['action'] === 'create') {
        // 新增學生及家長資料
        $stmt = $pdo->prepare("
            INSERT INTO STUDENT (student_id, student_name, school, grade, tel, address, status, version)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([$student_id, $student_name, $school, $grade, $tel, $address, $status]);

        $stmt = $pdo->prepare("
            INSERT INTO PARENT (parent_name, parent_tel, student_id)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$parent_name, $parent_tel, $student_id]);

        echo "<p>學生新增成功！</p>";
    } elseif ($_POST['action'] === 'update') {
        // 修改學生及家長資料
        // 檢查版本是否匹配
        $checkStmt = $pdo->prepare("
            SELECT version FROM STUDENT WHERE student_id = ?
        ");
        $checkStmt->execute([$student_id]);
        $currentVersion = $checkStmt->fetchColumn();

        if ($currentVersion == $version) {
            // 版本匹配，允許更新
            $stmt = $pdo->prepare("
                UPDATE STUDENT 
                SET student_name = ?, school = ?, grade = ?, tel = ?, address = ?, status = ?, version = version + 1
                WHERE student_id = ?
            ");
            $stmt->execute([$student_name, $school, $grade, $tel, $address, $status, $student_id]);

            $stmt = $pdo->prepare("
                INSERT INTO PARENT (parent_name, parent_tel, student_id)
                VALUES (?, ?, ?)
                ON CONFLICT (student_id) DO UPDATE 
                SET parent_name = EXCLUDED.parent_name, parent_tel = EXCLUDED.parent_tel
            ");
            $stmt->execute([$parent_name, $parent_tel, $student_id]);

            echo "<p>學生資料修改成功！</p>";
        } else {
            // 版本不匹配，拒絕更新
            echo "<p>更新失敗，該資料已被其他操作修改，請重新加載頁面。</p>";
        }
    }
}

// 查詢學生與家長資料
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $pdo->prepare("
        SELECT 
            s.student_id, 
            s.student_name, 
            s.school, 
            s.grade, 
            s.tel, 
            s.address, 
            s.status, 
            s.version,
            p.parent_name, 
            p.parent_tel
        FROM 
            STUDENT s
        LEFT JOIN 
            PARENT p
        ON 
            s.student_id = p.student_id
        WHERE 
            s.student_name LIKE ? OR s.student_id LIKE ?
    ");
    $stmt->execute(["%$search_query%", "%$search_query%"]);
} else {
    $stmt = $pdo->query("
        SELECT 
            s.student_id, 
            s.student_name, 
            s.school, 
            s.grade, 
            s.tel, 
            s.address, 
            s.status, 
            s.version,
            p.parent_name, 
            p.parent_tel
        FROM 
            STUDENT s
        LEFT JOIN 
            PARENT p
        ON 
            s.student_id = p.student_id
    ");
}

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 學生資料</title>
    <link rel="stylesheet" href="E:\DB_FinalProject\DB_CSS\style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo"><a href="web.php">BEST MATH</a></div>
            <button class="login-button"><a href="web.php">登出</a></button>
        </header>
        <div class="sidebar">
            <ul class="menu">
                <li><a href="modules/student.php">學生資料</a></li>
                <li><a href="modules/teacher.php">教師資料</a></li>
                <li><a href="modules/mentor.php">輔導教師資料</a></li>
                <li><a href="modules/class.php">班級資料</a></li>
                <li><a href="modules/audit.php">試聽資料</a></li>
            </ul>
        </div>
        
        <main class="content">
            <div class="content-header">
                <h1>學生資料</h1>
                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="search" placeholder="搜尋學生資料" value="<?= htmlspecialchars($search_query) ?>">
                        <button class="search-button" type="submit">搜尋</button>
                    </form>
                </div>
                <button class="create-button" onclick="document.getElementById('student-form').style.display='block'">+ CREATE</button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>SCHOOL</th>
                        <th>GRADE</th>
                        <th>TEL</th>
                        <th>ADDRESS</th>
                        <th>PARENT</th>
                        <th>PARENT_TEL</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['student_id']) ?></td>
                                <td><?= htmlspecialchars($student['student_name']) ?></td>
                                <td><?= htmlspecialchars($student['school']) ?></td>
                                <td><?= htmlspecialchars($student['grade']) ?></td>
                                <td><?= htmlspecialchars($student['tel']) ?></td>
                                <td><?= htmlspecialchars($student['address']) ?></td>
                                <td><?= htmlspecialchars($student['parent_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student['parent_tel'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">無相關資料</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <form id="student-form" method="post" style="display:none;">
                <h2>新增/修改學生資料</h2>
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="version" value="0">
                <label>姓名：<input type="text" name="student_name" required></label><br>
                <label>學校：<input type="text" name="school"></label><br>
                <label>年級：<input type="number" name="grade"></label><br>
                <label>電話：<input type="text" name="tel"></label><br>
                <label>地址：<input type="text" name="address"></label><br>
                <label>狀態：<input type="text" name="status"></label><br>
                <label>家長姓名：<input type="text" name="parent_name"></label><br>
                <label>家長電話：<input type="text" name="parent_tel"></label><br>
                <button type="submit">提交</button>
            </form>
        </main>
    </div>
</body>
</html>