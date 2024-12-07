<?php
include 'db.php';
include 'templates/header.php';

if (isset($_POST['search'])) {
    // 取得搜尋的課程名稱
    $class_name = $_POST['class_name'];

    // 查詢課程資料
    $stmt = $pdo->prepare("
        SELECT 
            CLASS.class_id, 
            CLASS.class_name, 
            CLASS.semester 
        FROM CLASS 
        WHERE CLASS.class_name LIKE ?
    ");
    $stmt->execute(['%' . $class_name . '%']);
    $course = $stmt->fetch();

    if ($course) {
        // 查詢該課程的所有學生
        $stmt = $pdo->prepare("
            SELECT 
                STUDENT.student_id, 
                STUDENT.student_name 
            FROM TAKE
            LEFT JOIN STUDENT ON TAKE.student_id = STUDENT.student_id
            WHERE TAKE.class_id = ?
        ");
        $stmt->execute([$course['class_id']]);
        $students = $stmt->fetchAll();
    }
}
?>

<h2>搜尋課程並查詢學生</h2>

<form method="post">
    <label>輸入課程名稱：<input type="text" name="class_name" required></label>
    <button type="submit" name="search">搜尋課程</button>
</form>

<?php if (isset($course)): ?>
    <h3>課程資訊</h3>
    <p>課程名稱: <?php echo htmlspecialchars($course['class_name']); ?></p>
    <p>學期: <?php echo htmlspecialchars($course['semester']); ?></p>

    <h3>選修此課程的學生</h3>
    <?php if ($students): ?>
        <table border="1">
            <tr>
                <th>學生編號</th>
                <th>學生姓名</th>
            </tr>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>此課程目前沒有學生選修。</p>
    <?php endif; ?>
<?php else: ?>
    <p>未找到符合的課程。</p>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>
