<?php

// بدء الجلسة
session_start();
// تضمين ملف الدوال
require_once "../functions.php";

// التحقق من وجود جلسة نشطة
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    // الحصول على تاريخ آخر تسجيل دخول ونوع المستخدم
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $userType = $_SESSION["USER"]["USERTYPE"];
    $currentDate = new DateTime();

    // حساب الفاصل الزمني بين آخر تسجيل دخول والتاريخ الحالي
    $interval = $lastLoginDate->diff($currentDate);

    // التحقق من انتهاء صلاحية الجلسة بعد يوم واحد
    if ($interval->days > 1) {
        // إلغاء الجلسة وإعادة التوجيه إلى صفحة تسجيل الدخول
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
    // إعادة توجيه المستخدم بناءً على نوع المستخدم
    if ($userType == "costumer") {
        header("Location: ../client.php");
    } else {
        header("Location:  ../driver.php");
    }
    exit();
}

// تهيئة مصفوفة الأخطاء
$errors = array();

// التحقق من طلب POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // استدعاء دالة التسجيل وتخزين الأخطاء
    $errors = signc($_POST);

    // إعادة التوجيه عند عدم وجود أخطاء
    if (count($errors) == 0) {
        header("Location: login.php");
        die;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/rafi/assets/image/22.png">
    <link rel="stylesheet" href="/rafi/assets/css/styles.css">
    <title> أنشاء حساب عميل</title>
</head>

<body>
    <!-- شريط التنقل -->
    <nav class="rafiheader" id="navbar">
        <div class="img">
            <img src="/rafi/assets/image/11.png" alt="header image">
        </div>
    </nav>

    <div class="container">
        <div class="box">
            <h3><span class="span"></span>تسجيل الدخول</h3>

            <!-- عرض الأخطاء إن وجدت -->
            <?php if (count($errors) > 0) : ?>
                <div class="errors" role="alert">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- نموذج التسجيل -->
            <form method="post">
                <div class="input_box">
                    <input type="text" name="fullname">
                    <label>الاسم الكامل</label>
                </div>

                <div class="input_box">
                    <input type="date" name="borndate">
                    <label>تاريخ الميلاد</label>
                </div>

                <div class="input_box">
                    <input type="text" name="phonenumber">
                    <label>رقم الهاتف</label>
                </div>

                <div class="input_box">
                    <input type="text" name="email">
                    <label>البريد الإلكتروني</label>
                </div>

                <div class="input_box">
                    <input type="password" name="password">
                    <label>كلمة المرور</label>
                </div>

                <div class="dropdown">
                    <div class="drop1">
                        <label>المحافظة</label>
                        <select name="governorateID">
                            <option value="3">عدن</option>
                        </select>
                    </div>
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
                    <button type="submit" name="signc">إنشاء</button>
                </div>
            </form>
        </div>
    </div>

    <!-- سكريبت لتعديل عرض الخط -->
    <script>
        let span = document.querySelector(".span");
        window.onload = function() {
            span.style.width = "180px";
        }
    </script>

    <!-- مكتبات Bootstrap -->
    <script src="/rafi/assets/js/popper.min.js"></script>
    <script src="/rafi/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/rafi/assets/js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
