<?php
// 資料庫連線設定
$host = 'localhost';
$dbname = 'MathSchool';
$user = 'postgres';
$password = '0825';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}
?>
