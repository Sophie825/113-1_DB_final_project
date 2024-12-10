<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 處理更新操作
    $shift = $_POST['shift'];
    $mentor_id = $_POST['mentor_id'];

    // 更新班次對應的導師
    $stmt = $pdo->prepare("
        UPDATE MENTOR_SHIFT
        SET mentor_id = ?
        WHERE shift = ?
    ");
    $stmt->execute([$mentor_id, $shift]);

    echo "<p>班次更新成功！</p>";
}

// 查詢所有班次和導師
$stmt = $pdo->query("
    SELECT 
        MENTOR_SHIFT.shift, 
        MENTOR_SHIFT.mentor_id, 
        MENTOR.mentor_name
    FROM MENTOR_SHIFT
    LEFT JOIN MENTOR ON MENTOR_SHIFT.mentor_id = MENTOR.mentor_id
    ORDER BY MENTOR_SHIFT.shift ASC
");
$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 查詢所有導師，用於下拉選單
$mentors = $pdo->query("SELECT mentor_id, mentor_name FROM MENTOR")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯輔導老師班表</title>
    <link rel="stylesheet" href="css/edit_mentor_shift.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="web.php">MATH SCHOOL</a>
        </div>
    </header>

    <div class="container">
        <main class="content">
            <h1>編輯輔導老師班表</h1>
            <form method="POST">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>班次</th>
                            <th>目前導師</th>
                            <th>選擇新導師</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shifts as $shift): ?>
                            <tr>
                                <td><?= htmlspecialchars($shift['shift']) ?></td>
                                <td><?= htmlspecialchars($shift['mentor_name'] ?? '無指定導師') ?></td>
                                <td>
                                    <select name="mentor_id" required>
                                        <option value="" disabled selected>選擇導師</option>
                                        <?php foreach ($mentors as $mentor): ?>
                                            <option value="<?= htmlspecialchars($mentor['mentor_id']) ?>" 
                                                <?= $shift['mentor_id'] == $mentor['mentor_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($mentor['mentor_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" name="shift" value="<?= htmlspecialchars($shift['shift']) ?>">
                                        更新
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </main>
        <!-- 頁尾 -->
        <footer>
            © 2024 Math School. All rights reserved.
        </footer>
    </div>
</body>
</html>
