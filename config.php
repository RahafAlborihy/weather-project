<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'weather_db'); 
define('DB_USER', 'weather_user'); 
define('DB_PASS', '12345');    

try {
    // بناء اتصال جديد
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    
    // ضبط وضع عرض الأخطاء لـ PDO (مهم للتصحيح)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    
    // ضبط الترميز ليعرض اللغة العربية بشكل صحيح
    $pdo->exec("set names utf8");
    
} catch (PDOException $e) {
    // في حالة فشل الاتصال، نتوقف ونعرض رسالة خطأ واضحة
    die("خطأ فادح في الاتصال بقاعدة البيانات: " . $e->getMessage());
}


// 3. إعدادات API (OpenWeatherMap)
// نحدد مفتاح الـ API ورابط الخدمة الأساسي
define('WEATHER_API_KEY', 'd203abf5c61269a999f9f3591863c8e1'); 

// رابط API الطقس الحالي
define('WEATHER_API_URL', 'https://api.openweathermap.org/data/2.5/weather'); 

?>