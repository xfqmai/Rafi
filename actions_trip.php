<?php
session_start();
include 'database.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <title>الصفحة الرئيسية</title>
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.css">
</head>

<body>
    <!-- تضمين شريط التنقل -->
    <?php include("elements/navbar.php") ?>

    <div class="container">
        <?php
        // التحقق من إذا كان المستخدم مسجل دخوله
        if (isset($_SESSION["USER"]["USERID"])) {
            $userID = $_SESSION["USER"]["USERID"];
            // استعلام للحصول على معرف السائق بناءً على معرف المستخدم
            $driverIDQuery = "SELECT driverID FROM driver WHERE userID = '$userID'";
            $driverIDResult = $db->query($driverIDQuery);

            // التحقق من نجاح الاستعلام والحصول على نتيجة
            if ($driverIDResult && $driverIDResult->num_rows > 0) {
                $row = $driverIDResult->fetch_assoc();
                $driverID = $row['driverID'];
                
                // التحقق من إرسال معرف الرحلة عبر النموذج
                if (isset($_POST["trip_id"])) {
                    $tripID = $_POST["trip_id"];
                    $tripStatus = $_POST["trip_status"];
                    // استعلام لتحديث حالة الرحلة
                    $updatetripQuery = "UPDATE trip SET status = '$tripStatus' WHERE tripID = '$tripID'";

                    // التحقق من نجاح استعلام التحديث
                    if ($db->query($updatetripQuery)) {
                        // استعلام للحصول على معرفات العملاء المرتبطين بالرحلة
                        $getCustomersQuery = "SELECT customerID FROM booking WHERE tripID = '$tripID'";
                        $getCustomersResult = $db->query($getCustomersQuery);

                        // التحقق من وجود عملاء مرتبطين بالرحلة
                        if ($getCustomersResult && $getCustomersResult->num_rows > 0) {
                            // إضافة إشعار لكل عميل
                            while ($customerRow = $getCustomersResult->fetch_assoc()) {
                                $customerID = $customerRow['customerID'];
                                $insertNotificationQuery = "INSERT INTO notifications (customerID, message, created_at) VALUES ('$customerID', 'تم إلغاء الرحلة', NOW())";
                                if (!$db->query($insertNotificationQuery)) {
                                    echo '<div class="alert alert-secondary" role="alert">حدث خطأ أثناء تسجيل الإشعار للعميل ' . $customerID . '. الرجاء المحاولة مرة أخرى.</div>';
                                }
                            }
                            // إعادة التوجيه إلى صفحة الرحلات الجارية بعد النجاح
                            header("Location: trips_go.php");
                            exit();
                        } else {
                            // إعادة التوجيه إلى صفحة الرحلات الجارية إذا لم يكن هناك عملاء مرتبطين
                            header("Location: trips_go.php");
                            exit();
                        }
                    } else {
                        echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الرحلة. الرجاء تسجيل الدخول مرة أخرى.</div>';
                        exit();
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الرحلة.</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الرحلة. الرجاء التأكد من إرسال رقم الرحلة.</div>';
            }
        }
        ?>
        <a class="link" href="driver.php">العودة للوحة التحكم</a>
    </div>

    <!-- تضمين ملفات JavaScript -->
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/rafi/assets/js/popper.min.js"></script>
    <script src="/rafi/assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>
