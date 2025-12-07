<?php
session_start();        // نرجع نفتح الجلسة
session_unset();        // نحذف كل القيم من $_SESSION
session_destroy();      // ندمّر الجلسة بالكامل
// نرجع المستخدم لصفحة تسجيل الدخول
header("Location: login.php");
exit();