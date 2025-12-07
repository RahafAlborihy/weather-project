<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>

    <style>
       body {
    font-family: "Tajawal", Arial, sans-serif;
    background: #77a9ff; /* خلفية زرقاء موحدة */
    margin: 0;
    padding: 0;
}

.login-container {
    width: 420px;   /* وسّعنا الصندوق أكثر */
    margin: 90px auto;
    background: #fff;
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.15);
    text-align: center;
}

h2 {
    margin-bottom: 25px;
    color: #0468c8;
    font-size: 24px;
}

input {
    width: 100%;
    padding: 14px;
    margin: 12px 0;
    border-radius: 10px;
    border: 1px solid #ccc;
    background-color: #ffffff;  /* الحقول أصبحت بيضاء */
    font-size: 16px;
}

button {
    width: 100%;
    padding: 14px;
    background-color: #28a745;  /* زر أخضر */
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 17px;
    cursor: pointer;
    margin-top: 10px;
    font-weight: bold;
}

button:hover {
    background-color: #218838;
}

.error {
    background: #ffd7d7;
    padding: 10px;
    border-radius: 6px;
    color: #b60000;
    margin-bottom: 15px;
}
    </style>
</head>
<body>

<div class="login-container">

    <h2>تسجيل الدخول</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ اسم المستخدم أو كلمة المرور غير صحيحة</div>
    <?php endif; ?>

    <form action="process_login.php" method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم" required>
        <input type="password" name="password" placeholder="كلمة المرور" required>
        <button type="submit">دخول</button>
    </form>
</div>

</body>
</html>