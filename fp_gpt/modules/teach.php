<?php
include 'db.php';
include 'templates/header.php';

// 查詢老師與他們所教授的課程
$stmt = $pdo->prepare("
    SELECT 
        TEACHER.teacher_name, 
        CLASS.class_name
    FROM TEACH
    LEFT JOIN TEACHER ON TEACH.teacher_id = TEACHER.teacher_id
    LEFT JOIN CLASS ON TEACH.class_id = CLASS.class_id
");
$stmt->execute();
?>

<h2>老師與授課課程一覽</h2>
<table border="1">
    <tr>
        <th>老師姓名</th>
        <th>授課課程</th>
    </tr>
    <?php while ($row = $stmt->fetch()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
            <td><?php echo htmlspecialchars($row['class_name']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include 'templates/footer.php'; ?>
