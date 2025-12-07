<?php
session_start();             // تفعيل الجلسات
require_once 'config.php';   // اتصال PDO

// 🚨 حماية الصفحة: لو المستخدم غير مسجل دخول → رجعيه login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// 🔧 تفعيل نظام الإبلاغ عن الأخطاء (من محاضرة 5)
error_reporting(E_ALL);           // إظهار كل أنواع الأخطاء للتحليل
ini_set('display_errors', 0);     // لا تعرض الأخطاء للمستخدم
ini_set('log_errors', 1);         // فعّل تسجيل الأخطاء في ملف لوق
ini_set('error_log', __DIR__ . '/php-errors.log'); // مكان ملف تسجيل الأخطاء
$cities = [
    "Sanaa"      => "صنعاء",
    "Aden"       => "عدن",
    "Taiz"       => "تعز",
    "Al Hudaydah"=> "الحديدة",
    "Ibb"        => "إب",
    "Dhamar"     => "ذمار",
    "Hadramawt"  => "حضرموت",
    "Marib"      => "مأرب",
    "Amran"      => "عمران",
    "Al Mahwit"  => "المحويت",
    "Raymah"     => "ريمة",
    "Al Jawf"    => "الجوف",
    "Shabwah"    => "شبوة",
    "Al Bayda"   => "البيضاء",
    "Ad Dali"    => "الضالع",
    "Lahij"      => "لحج",
    "Saada"      => "صعدة"
];

// ترتيب المحافظات أبجديًا
asort($cities);

$cityKeys = array_keys($cities);
$cityVals = array_values($cities);
//yyyyyyyuuuiii
$totalCities = count($cities);

class WeatherEntry {
    private $city;
    private $temp;
    private $humidity;
    private $desc;

    public function __construct($city, $temp, $humidity, $desc) {
        $this->city = $city;
        $this->temp = $temp;
        $this->humidity = $humidity;
        $this->desc = $desc;
    }

    public function getCity() { return $this->city; }
    public function getTemp() { return $this->temp; }
    public function getHumidity() { return $this->humidity; }
    public function getDesc() { return $this->desc; }
}

$currentWeather = null;//نتيجه الطقس
$errorMessage   = "";

// --------------------------------------------------
// 3) معالجة POST (محاضرة 1 + 2)
// --------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selectedCity = $_POST['city_name'] ?? null;

    if (!in_array($selectedCity, $cityKeys)) {
        $errorMessage = "❌ خطأ: المدينة غير موجودة في القائمة.";
    } else {

        $cityArabic = $cities[$selectedCity];

        // --------------------------------------------------
        // 4) استدعاء API الطقس
        // --------------------------------------------------
        $url = WEATHER_API_URL
             . "?q=" . urlencode($selectedCity)
             . "&appid=" . WEATHER_API_KEY   
             . "&units=metric&lang=ar";

        $apiResponse = @file_get_contents($url);

        if (!$apiResponse) {
            $errorMessage = "❌ فشل الاتصال بـ API.";
        } else {

            $data = json_decode($apiResponse, true);

            if ($data === null || !isset($data['main'])) {
                $errorMessage = "❌ خطأ في بيانات API.";
            } else {

                $temp     = $data['main']['temp'] ?? 0;
                $humidity = $data['main']['humidity'] ?? 0;
                $desc     = $data['weather'][0]['description'] ?? "غير متوفر";

                $currentWeather = new WeatherEntry($cityArabic, $temp, $humidity, $desc);

                            
        


                // 5) حفظ نتيجة الطقس داخل SESSION
                $_SESSION['last_weather'] = [
                    "city" => $cityArabic,
                    "temp" => $temp,
                    "humidity" => $humidity,
                    "desc" => $desc
                ];

                // 7) حفظ البيانات في ملف نصي (File Handling)
                $logLine = date("Y-m-d H:i:s") . " | {$cityArabic} | {$temp}°C\n";
                file_put_contents("weather_log.txt", $logLine, FILE_APPEND);


                // 8) حفظ البيانات في قاعدة البيانات عبر PDO
                try {
                    $sql = "INSERT INTO weather_logs (city_name, temperature, humidity, description)
                            VALUES (:city, :temp, :hum, :desc)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':city', $cityArabic);
                    $stmt->bindParam(':temp', $temp);
                    $stmt->bindParam(':hum', $humidity);
                    $stmt->bindParam(':desc', $desc);
                    $stmt->execute();

                } catch (PDOException $e) {
                    $errorMessage = "خطأ PDO: " . $e->getMessage();
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام عرض حالة الطقس لليمن</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

  <div class="logout-box">
    مرحبًا، <?= $_SESSION['username']; ?> 
    <a href="logout.php" class="logout-btn">تسجيل خروج</a>
</div>

<div class="container">

    <h1><i class="fas fa-cloud-sun"></i> حالة الطقس لمحافظات اليمن</h1>

    <!-- عرض آخر نتيجة محفوظة من SESSION -->
    <?php if (isset($_SESSION['last_weather'])): ?>
        <div class="alert success">
             آخر نتيجة بحث:
            <?= $_SESSION['last_weather']['city'] ?> —
            <?= $_SESSION['last_weather']['temp'] ?>°C —
            رطوبة <?= $_SESSION['last_weather']['humidity'] ?>%
        </div>
    <?php endif; ?>

    <!-- عرض الأخطاء -->
    <?php if ($errorMessage): ?>
        <div class="alert error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <!-- نموذج اختيار المحافظة -->
    <form method="POST">
        <div class="form-group">
            <label for="city_name">اختر المحافظة:</label>
            <select id="city_name" name="city_name" required>
                <option value="">-- اختر محافظة --</option>

                <?php foreach ($cities as $en => $ar): ?>
                    <option value="<?= $en ?>"><?= $ar ?></option>

                   
                <?php endforeach; ?>

            </select>
        </div>

        <button type="submit"><i class="fas fa-search"></i> عرض الطقس</button>
    </form>

    <!-- عرض بيانات الطقس -->
    <?php if ($currentWeather): ?>
        <div class="weather-result">
            <h2><?= $currentWeather->getCity(); ?></h2>

            <div class="weather-details">
                <p><strong>درجة الحرارة:</strong> <?= $currentWeather->getTemp(); ?> °C</p>
                <p><strong>الرطوبة:</strong> <?= $currentWeather->getHumidity(); ?>%</p>
                <p><strong>الوصف:</strong> <?= $currentWeather->getDesc(); ?></p>
            </div>
        </div>
    <?php endif; ?>

</div>
</body>
</html>