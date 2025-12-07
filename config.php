
<?php
// تفعيل تسجيل الأخطاء
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-errors.log');
error_reporting(E_ALL);



// معلومات الاتصال بقاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_NAME', 'weather_db');
define('DB_USER', 'root');
define('DB_PASS', '');

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