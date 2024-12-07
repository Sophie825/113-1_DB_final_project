<?php
include 'db.php';
include 'templates/header.php';

// 查詢學期為 113-1 的課程及其相關的教室和授課老師
$stmt = $pdo->prepare("
    SELECT 
        CLASS.class_id, 
        CLASS.class_name, 
        CLASS.semester, 
        CLASSROOM.classroom_name, 
        TEACHER.teacher_name
    FROM CLASS
    LEFT JOIN CLASSROOM ON CLASS.classroom_id = CLASSROOM.classroom_id
    LEFT JOIN TEACH ON CLASS.class_id = TEACH.class_id
    LEFT JOIN TEACHER ON TEACH.teacher_id = TEACHER.teacher_id
    WHERE CLASS.semester = ?
");
$stmt->execute(['113-1']);

?>

<h2>113-1 學期課程一覽</h2>
<table border="1">
    <tr>
        <th>課程編號</th>
        <th>課程名稱</th>
        <th>教室名稱</th>
        <th>授課老師</th>
    </tr>
    <?php while ($row = $stmt->fetch()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['class_id']); ?></td>
            <td><?php echo htmlspecialchars($row['class_name']); ?></td>
            <td><?php echo htmlspecialchars($row['classroom_name'] ?? '無指定教室'); ?></td>
            <td><?php echo htmlspecialchars($row['teacher_name'] ?? '無指定老師'); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include 'templates/footer.php'; ?>
