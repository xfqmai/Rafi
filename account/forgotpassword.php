<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../database.php'; // التأكد من تضمين ملف الاتصال بقاعدة البيانات
    $email = $db->real_escape_string($_POST['email']);
    
    // تحقق مما إذا كان البريد الإلكتروني موجودًا في قاعدة البيانات
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($query);
    
    if ($result->num_rows > 0) {
        // إنشاء رمز فريد لإعادة تعيين كلمة المرور
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // إدخال الرمز في قاعدة البيانات
        $query = "INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$token', '$expiry')";
        $db->query($query);
        
        // إرسال بريد إلكتروني للمستخدم مع رابط إعادة تعيين كلمة المرور
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            // تكوين الخادم وبيانات الاتصال
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Rafiapp2024@gmail.com'; // اسم المستخدم الخاص بك
            $mail->Password = 'RAfi12349'; // كلمة المرور الخاصة بك
            $mail->SMTPSecure = 'tls'; // استخدام SSL / TLS
            $mail->Port = 587; // منفذ SSL / TLS

            // تهيئة بيانات الرسالة
            $mail->setFrom('Rafiapp2024@gmail.com', 'موقع رافي للمواصلات');
            $mail->addAddress($email); // البريد الإلكتروني للمستخدم
            $mail->Subject = 'إعادة تعيين كلمة المرور';
            $mail->Body = 'انقر على الرابط التالي لإعادة تعيين كلمة المرور: ' . $resetLink;

            // إرسال الرسالة
            $mail->send();
            $message = 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.';
        } catch (Exception $e) {
            $message = "حدث خطأ أثناء إرسال البريد الإلكتروني. يرجى المحاولة مرة أخرى. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "البريد الإلكتروني غير موجود في نظامنا.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
    <title>نسيت كلمة المرور</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">نسيت كلمة المرور</h2>
                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>
                <form action="forgotpassword.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">إرسال رابط إعادة التعيين</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
