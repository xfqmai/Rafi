<?php
session_start();
require_once "../functions.php";

$errors = array(); // مصفوفة لتخزين الأخطاء

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = login($_POST); // تسجيل الدخول والحصول على الأخطاء
    if (count($errors) == 0) {
        // تحديد نوع المستخدم بناءً على البريد الإلكتروني بعد التسجيل
        $userType = getUserType($_POST['email']); // افترض أن هناك دالة تقوم بإرجاع نوع المستخدم
    
        // حفظ نوع المستخدم في الجلسة
        $_SESSION['USER']['userType'] = $userType;
    
        // التوجيه إلى الصفحة المناسبة استنادًا إلى نوع المستخدم
        if ($userType == 'driver') {
            header("Location: /rafi/driver.php"); // توجيه السائقين إلى صفحة السائق
        } elseif ($userType == 'admin') {
            header("Location: /rafi/admin.php"); // توجيه المشرفين إلى صفحة الأدمن
        } else {
            header("Location: /rafi/client.php"); // توجيه العملاء إلى صفحة العميل
        }
        exit(); // إيقاف التنفيذ الآخر
    }
    
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="/rafi/assets/css/styles.css">
</head>

<body>
    <div class="rafiheader">
        <div class="img">
            <img src="/rafi/assets/image/11.png" alt="header image" />
        </div>
    </div>
    <div class="container">
        <div class="box">
            <h3><span class="span"></span>تسجيل الدخول</h3>

            <form action="login.php" method="post"> <!-- تصحيح مسار العملية -->

                <div class="input_box">
                    <input type="email" name="email" required>
                    <label> البريد الإلكتروني</label>
                </div>
                <div class="input_box">
                    <input type="password" name="password">
                    <label>كلمة المرور</label>
                </div>
                <?php if (!empty($errors)) : ?>
                    <div class="errors">
                        <?php foreach ($errors as $error) : ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <p class="forgot_password"><a href="/rafi/account/forgotpassword.php">هل نسيت كلمة السر ؟</a></p>

                <button type="submit">تسجيل الدخول</button>
                <p class="singup_link">لا تمتلك حساب ؟ <a href="signup.php">انشى حساب </a></p>
                <p class="contact_link"><a href="../help.php">تحتاج مساعدة؟</a></p>
            </form>
        </div>
    </div>
    <script>
        let span = document.querySelector(".span");
        window.onload = function() {
            span.style.width = "180px";
        }
    </script>
</body>

</html>
