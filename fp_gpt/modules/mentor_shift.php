<?php
include 'db.php';
include 'templates/header.php';

// 查詢並依 shift 由小到大排序
$stmt = $pdo->prepare("
    SELECT 
        MENTOR_SHIFT.shift, 
        MENTOR.mentor_name
    FROM MENTOR_SHIFT
    LEFT JOIN MENTOR ON MENTOR_SHIFT.mentor_id = MENTOR.mentor_id
    ORDER BY MENTOR_SHIFT.shift ASC
");
$stmt->execute();

?>

<h2>導師班表</h2>
<table border="1">
    <tr>
        <th>星期</th>
        <th>導師姓名</th>
    </tr>
    <?php while ($row = $stmt->fetch()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['shift']); ?></td>
            <td><?php echo htmlspecialchars($row['mentor_name'] ?? '無指定導師'); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include 'templates/footer.php'; ?>
