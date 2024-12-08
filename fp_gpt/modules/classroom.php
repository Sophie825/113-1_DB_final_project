<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

// 查詢 113-1 學期的課程及其相關資訊
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
<div class="management-options">
    <?php while ($row = $stmt->fetch()): ?>
        <div class="option-card">
            <h3><?php echo htmlspecialchars($row['class_name']); ?></h3>
            <p>教室：<?php echo htmlspecialchars($row['classroom_name'] ?? '無指定教室'); ?></p>
            <p>老師：<?php echo htmlspecialchars($row['teacher_name'] ?? '無指定老師'); ?></p>
            <button class="click_in">
                <a href="student_list.php?class_id=<?php echo htmlspecialchars($row['class_id']); ?>">查看學生名單</a>
            </button>
        </div>
    <?php endwhile; ?>
</div>

<?php include 'templates/footer.php'; ?>