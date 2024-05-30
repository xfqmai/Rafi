<?php

session_start();
require_once "../functions.php";

// التحقق من حالة تسجيل الدخول للمستخدم
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $userType = $_SESSION["USER"]["USERTYPE"];
    $currentDate = new DateTime();

    $interval = $lastLoginDate->diff($currentDate);

    // تسجيل الخروج إذا كان آخر تسجيل دخول قبل أكثر من يوم
    if ($interval->days > 1) {
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }

    // توجيه المستخدم إلى الصفحة المناسبة بناءً على نوع المستخدم
    if ($userType === "costumer") {
        header("Location: ../client.php");
    } else {
        header("Location:  ../driver.php");
    }
    exit();
}

// التحقق من نوع الحساب المختار بعد إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_type = $_POST['account_type'];

    // توجيه المستخدم إلى الصفحة المناسبة بناءً على نوع الحساب
    if ($account_type === 'customer') {
        header("Location: client.php");
        exit();
    } elseif ($account_type === 'driver') {
        header("Location: driver.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/rafi/assets/image/22.png">
    <title>إنشاء حساب</title>
    <link rel="stylesheet" href="/rafi/assets/css/styles.css">
</head>

<body>
    <div class="rafiheader">
        <div class="img">
            <img src="/rafi/assets/image/11.png" alt="header image">
        </div>
    </div>
    <div class="container">
        <div class="box">
            <form action="#" method="post">
                <!-- زر لإنشاء حساب عميل -->
                <button type="submit" name="account_type" value="customer">عميل</button>
                <br>
                <!-- زر لإنشاء حساب سائق -->
                <button type="submit" name="account_type" value="driver">سائق</button>

                <!-- رابط لتسجيل الدخول إذا كان المستخدم يمتلك حساب -->
                <p class="singup_link">هل تمتلك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
                <!-- رابط للمساعدة -->
                <p class="contact_link"><a href="help.php">تحتاج مساعدة؟</a></p>
            </form>
        </div>
    </div>
    <script>
        let span = document.querySelector(".span");
        window.onload = function() {
            span.style.width = "150px";
        }
    </script>
</body>

</html>
