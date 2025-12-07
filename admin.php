<?php
//session_start();
require_once 'config.php';   // اتصال PDO




// ------------------------------------------------------
// 1) الحصول على السجلات الكاملة من قاعدة البيانات
// ------------------------------------------------------
try {
    $sql = "SELECT * FROM weather_logs ORDER BY log_id DESC";
    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // عدد السجلات
    $sqlCount = "SELECT COUNT(*) AS total FROM weather_logs";
    $stmtCount = $pdo->query($sqlCount);
    $countResult = $stmtCount->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $countResult['total'];

    // آخر 5 سجلات
    $sqlLast5 = "SELECT * FROM weather_logs ORDER BY log_id DESC LIMIT 5";
    $stmtLast5 = $pdo->query($sqlLast5);
    $lastFive = $stmtLast5->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("خطأ في جلب البيانات: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - سجلات الطقس</title>
    <link rel="stylesheet" href="style.css">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        table th, table td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: center;
            background-color: #fff;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        .back-btn {
            background-color: #ffc107;
            padding: 12px 18px;
            border-radius: 8px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>📊 سجلات الطقس المحفوظة</h1>

    <a class="back-btn" href="index.php">← العودة للصفحة الرئيسية</a>

    <h2>إحصائيات</h2>
    <p><strong>عدد السجلات الكلي:</strong> <?= $totalRecords ?></p>

    <h2>📌 آخر 5 سجلات</h2>
    <table>
        <tr>
            <th>#</th>
            <th>المدينة</th>
            <th>الحرارة</th>
            <th>الرطوبة</th>
            <th>الوصف</th>
            <th>التاريخ</th>
        </tr>

        <?php foreach ($lastFive as $row): ?>
        <tr>
            <td><?= $row['log_id'] ?></td>
            <td><?= $row['city_name'] ?></td>
            <td><?= $row['temperature'] ?> °C</td>
            <td><?= $row['humidity'] ?>%</td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['log_date'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>📄 جميع السجلات</h2>
    <table>
        <tr>
            <th>#</th>
            <th>المدينة</th>
            <th>الحرارة</th>
            <th>الرطوبة</th>
            <th>الوصف</th>
            <th>التاريخ</th>
        </tr>

        <?php foreach ($logs as $row): ?>
        <tr>
            <td><?= $row['log_id'] ?></td>
            <td><?= $row['city_name'] ?></td>
            <td><?= $row['temperature'] ?> °C</td>
            <td><?= $row['humidity'] ?>%</td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['log_date'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>