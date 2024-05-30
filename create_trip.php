<?php
// بداية الجلسة لتتبع بيانات الجلسة والتحقق من تسجيل الدخول
session_start();

// التحقق من تسجيل الدخول وتاريخ آخر تسجيل دخول
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $currentDate = new DateTime();

    // حساب الفاصل الزمني بين آخر تسجيل دخول والتاريخ الحالي
    $interval = $lastLoginDate->diff($currentDate);

    // في حال مرور أكثر من يوم واحد منذ آخر تسجيل دخول، يتم إلغاء جلسة المستخدم وتوجيهه إلى صفحة تسجيل الدخول
    if ($interval->days > 1) {
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
} else {
    // إعادة التوجيه إلى صفحة تسجيل الدخول في حالة عدم تسجيل الدخول
    header("Location: account/login.php");
    exit();
}

// تضمين ملف الوظائف
require_once "functions.php";

$errors = array();

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = $_POST;
    // استرداد بريد العميل المسجل الدخول
    $email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;
    // تنفيذ وظيفة tripc() لإنشاء رحلة جديدة ومعالجة الأخطاء
    $errors = tripc($data, $email);
    // في حال عدم وجود أخطاء، يتم توجيه المستخدم إلى صفحة الرحلات
    if (count($errors) == 0) {
        header("Location: trips_go.php");
        die;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <!-- تعيين الترميز والميتا لضمان التوافقية -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- رابط أيقونة العنوان -->
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <!-- عنوان الصفحة -->
    <title>إنشاء رحلة</title>
    <!-- تضمين أسلوب Bootstrap للتنسيق -->
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
</head>

<body>
    <!-- تضمين شريط التنقل -->
    <?php include("elements/navbar.php") ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header">
                        <!-- عنوان البطاقة -->
                        <h5 class="card-title">معلومات الرحلة</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <!-- عرض الأخطاء إذا كانت متاحة -->
                            <?php if (!empty($errors)) : ?>
                                <div class="alert alert-danger">
                                    <?php foreach ($errors as $error) : ?>
                                        <p><?php echo $error; ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <!-- حقول إدخال معلومات الرحلة -->
                            <div class="form-group">
                                <label for="directorate">المديرية</label>
                                <select class="form-control" id="directorate">
                                    <option value="1">الشيخ عثمان</option>
                                    <option value="2">المنصورة</option>
                                    <option value="3">دار سعد</option>
                                    <option value="4">خور مكسر</option>
                                    <option value="5">كريتر</option>
                                    <option value="6">المعلا</option>
                                    <option value="7">التواهي</option>
                                    <option value="8">البريقاء</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="startLocation">موقع تحرك الرحلة </label>
                                <input type="text" class="form-control" id="startLocation" name="startLocation" required>
                            </div>
                            <div class="form-group">
                                <label for="arrivalLocation">موقع وصول الرحلة</label>
                                <input type="text" class="form-control" id="arrivalLocation" name="arrivalLocation" required>
                            </div>
                            <div class="form-group">
                                <label for="startTime">وقت تحرك الرحلة</label>
                                <input type="time" class="form-control" id="startTime" name="startTime" required>
                            </div>
                            <div class="form-group">
                                <label for="cost">سعر الرحلة</label>
                                <input type="text" class="form-control" id="cost" name="cost" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity">سعة الافراد</label>
                                <input type="text" class="form-control" id="capacity" name="capacity" required>
                            </div>
                            <button type="submit" name="tripc" class=" my-3 btn btn-primary">نشر الرحلة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>