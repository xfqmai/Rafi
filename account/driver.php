<?php

// بدء جلسة
session_start();

// تضمين ملف الدوال
require_once "../functions.php";

// التحقق مما إذا كان المستخدم مسجلاً دخوله
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    // الحصول على تاريخ آخر تسجيل دخول ونوع المستخدم
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $userType = $_SESSION["USER"]["USERTYPE"];
    $currentDate = new DateTime();

    // حساب الفرق بين تاريخ آخر تسجيل دخول والتاريخ الحالي
    $interval = $lastLoginDate->diff($currentDate);

    // إذا كان الفرق أكثر من يوم، يتم إنهاء الجلسة وإعادة التوجيه لصفحة تسجيل الدخول
    if ($interval->days > 1) {
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
    // إعادة التوجيه بناءً على نوع المستخدم
    if ($userType === "costumer") {
        header("Location: ../client.php");
    } else {
        header("Location: ../driver.php");
    }
    exit();
}

// تهيئة مصفوفة الأخطاء
$errors = array();

// التحقق مما إذا كانت الطلب من نوع POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // استدعاء دالة التحقق من المدخلات
    $errors = signd($_POST);

    // إذا لم تكن هناك أخطاء، إعادة التوجيه لصفحة تسجيل الدخول
    if (count($errors) == 0) {
        header("Location: login.php");
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/rafi/image/22.png">
    <title>أنشاء حساب سائق</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/rafi/assets/css/styles.css">
</head>

<body>

    <!-- شريط التنقل -->
    <nav class="rafiheader" id="navbar">
        <div class="img">
            <img src="/rafi/assets/image/11.png" alt="header image">
        </div>
    </nav>

    <!-- نموذج تسجيل المعلومات -->
    <div class="container">
        <div class="box">
            <h3><span class="span"></span>تسجيل الدخول</h3>
            <h5>الرجاء تسجيل المعلومات الأساسية لقبولك كـ سائق</h5>
            <!-- عرض الأخطاء إذا كانت موجودة -->
            <?php if (count($errors) > 0) : ?>
                <div class="errors" role="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- نموذج إدخال المعلومات -->
            <form method="post">
                <!-- حقول الإدخال -->
                <div class="input_box">
                    <input type="text" name="fullname" required>
                    <label>الاسم الكامل</label>
                </div>

                <div class="input_box">
                    <input type="text" name="phonenumber" required>
                    <label>رقم الهاتف</label>
                </div>
                <!-- القائمة المنسدلة للاختيار من المحافظات -->
                <div class="dropdown">
                    <div class="drop1">
                        <label>المحافظة</label>
                        <select name="governorateID">
                            <option value="3">عدن</option>
                            <!-- يمكنك إضافة المزيد من الخيارات هنا -->
                        </select>
                    </div>
                </div>
                <!-- القائمة المنسدلة للاختيار من المديريات -->
                <div class="dropdown">
                    <div class="drop2">
                        <label>المديرية</label>
                        <select name="directorateID">
                            <option value="9">الشيخ عثمان</option>
                            <option value="10">المنصورة</option>
                            <option value="11">دار سعد</option>
                            <option value="12">خور مكسر</option>
                            <option value="13">كريتر</option>
                            <option value="14">المعلا</option>
                            <option value="15">التواهي</option>
                            <option value="16">البريقاء</option>
                        </select>
                    </div>
                </div>

                <div class="input_box">
                    <input type="text" name="email" required>
                    <label>البريد الإلكتروني</label>
                </div>
                <!-- حقل كلمة المرور -->
                <div class="input_box">
                    <input type="password" name="password" required>
                    <label>كلمة المرور</label>
                </div>
                <!-- حقل العمر -->
                <div class="input_box">
                    <input type="text" name="age" required>
                    <label>العمر</label>
                </div>
                <!-- حقل رقم الهوية -->
                <div class="input_box">
                    <input type="text" name="identificationNumber" required>
                    <label>رقم الهوية</label>
                </div>
                <!-- حقل رفع الصورة -->
                <div class="input_box">
                    <input type="file" name="identificationimage" accept="image/*" required>
                    <label>ارفع صورة لهويتك</label>
                </div>

                <!-- حقل رقم رخصة القيادة -->
                <div class="input_box">
                    <input type="text" name="drivingLicenseNO" required>
                    <label>رقم رخصة القيادة</label>
                </div>
                <!-- حقل رقم رخصة تسيير المركبة -->
                <div class="input_box">
                    <input type="text" name="vehicleLicenseNO" required>
                    <label>رقم رخصة تسيير المركبة</label>
                </div>
                <!-- حقل اسم السيارة -->
                <div class="input_box">
                    <input type="text" name="carName" required>
                    <label>اسم السيارة</label>
                </div>
                <!-- حقل موديل السيارة -->
                <div class="input_box">
                    <input type="text" name="carModel" required>
                    <label>موديل السيارة</label>
                </div>
                <!-- حقل رقم لوحة السيارة -->
                <div class="input_box">
                    <input type="text" name="plateNumber" required>
                    <label>رقم لوحة السيارة</label>
                </div>
                <!-- زر الإرسال -->
                <button type="submit" name="signd">إنشاء</button>
            </form>
        </div>
    </div>
</body>

</html>
