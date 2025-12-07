<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // لو أحد فتح الملف مباشرة بدون POST → رجعيه لصفحة الدخول
    header("Location: login.php");
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// نجيب المستخدم من قاعدة البيانات
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// فحص اسم المستخدم وكلمة المرور (بدون تشفير، مثل ما عملتي)
if ($user && $password === $user['password']) {
    // تخزين بيانات الجلسة
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];

    // نجاح → روح للصفحة الرئيسية
    header("Location: index.php");
    exit();
} else {
    // خطأ → رجّعي المستخدم لصفحة تسجيل الدخول مع رسالة خطأ
    header("Location: login.php?error=1");
    exit();
}