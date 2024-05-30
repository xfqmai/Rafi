<?php
// بداية الجلسة لتتبع بيانات الجلسة وتضمين ملف قاعدة البيانات
session_start();
include 'database.php'; 
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
    <title> الغاء الحجز</title>
    <!-- تضمين أسلوب Bootstrap للتنسيق -->
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
</head>

<body>
    <!-- تضمين شريط التنقل -->
    <?php include("elements/navbar.php") ?>

    <?php
    // التحقق من وجود معرف مستخدم مسجل الدخول
    if (isset($_SESSION["USER"]["USERID"])) {
        // استعلام لاسترداد معرف العميل استنادًا إلى معرف المستخدم
        $userID = $_SESSION["USER"]["USERID"];
        $customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
        $customerIDResult = $db->query($customerIDQuery);

        if ($customerIDResult && $customerIDResult->num_rows > 0) {
            // استرداد معرف العميل
            $row = $customerIDResult->fetch_assoc();
            $customerID = $row['customerID'];
            if (isset($_POST["booking_id"])) {
                // استلام معرف الحجز وحالته من النموذج المقدم
                $bookingID = $_POST["booking_id"];
                $bookingStatus = $_POST["booking_status"];
                // تحديث حالة الحجز
                $updateBookingQuery = "UPDATE booking SET bookingState = '$bookingStatus' WHERE bookingID = '$bookingID'";

                if ($db->query($updateBookingQuery)) {
                    // استرداد معرف الرحلة
                    $getTripIDQuery = "SELECT tripID FROM booking WHERE bookingID = '$bookingID'";
                    $getTripIDResult = $db->query($getTripIDQuery);
                    if ($getTripIDResult && $getTripIDResult->num_rows > 0) {
                        $tripRow = $getTripIDResult->fetch_assoc();
                        $tripID = $tripRow['tripID'];
                        // استرداد معرف السائق
                        $getDriverIDQuery = "SELECT driverID FROM trip WHERE tripID = '$tripID'";
                        $getDriverIDResult = $db->query($getDriverIDQuery);
                        if ($getDriverIDResult && $getDriverIDResult->num_rows > 0) {
                            $getDriverIDResult = $db->query($getDriverIDQuery);
                            $driverRow = $getDriverIDResult->fetch_assoc();
                            $driverID = $driverRow['driverID'];
                            // استرداد معرف المستخدم الخاص بالسائق
                            $getuserIDQuery = "SELECT userID FROM driver WHERE driverID = '$driverID'";
                            $getuserIDResult = $db->query($getuserIDQuery);
                            if ($getuserIDResult && $getuserIDResult->num_rows > 0) {
                                $getuserIDResult = $db->query($getuserIDQuery);
                                $userRow = $getuserIDResult->fetch_assoc();
                                $userDriverID = $userRow['userID'];
                                // إضافة إشعار للسائق بإلغاء الحجز
                                $insertNotificationQuery = "INSERT INTO notifications (userID, message, created_at) VALUES ('$userDriverID', 'قام العميل بإلغاء الحجز', NOW())";
                                if ($db->query($insertNotificationQuery)) {
                                    // إعادة توجيه المستخدم إلى الواجهة الرئيسية
                                    header("Location: client.php");
                                    exit();
                                } else {
                                    // رسالة خطأ في حالة فشل إضافة الإشعار
                                    echo '<div class="alert alert-secondary" role="alert">حدث خطأ أثناء تسجيل الإشعار. الرجاء المحاولة مرة أخرى.</div>';
                                }
                            }
                        } else {
                            // رسالة خطأ في حالة عدم العثور على رقم السائق
                            echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الحجز. لم يتم العثور على رقم السائق.</div>';
                        }
                    } else {
                        // رسالة خطأ في حالة عدم العثور على رقم الرحلة
                        echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الحجز. لم يتم العثور على رقم الرحلة.</div>';
                    }
                } else {
                    // رسالة خطأ في حالة فشل تحديث حالة الحجز
                    echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الحجز. الرجاء تسجيل الدخول مرة أخرى.</div>';
                    exit();
                }
            } else {
                // رسالة خطأ في حالة عدم تلقي معرف الحجز من النموذج
                echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الحجز.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">حدث خطأ أثناء إلغاء الحجز. الرجاء التأكد من إرسال رقم الحجز.</div>';
        }
    }
    ?>
    <a class="link" href="client.php">العودة للوحة التحكم </a>
    <script src="/rafi/assets/js/bootstrap.js"></script>
    <script src="/rafi/assets/js/popper.min.js"></script>
    <script src="/rafi/assets/js/jquery-3.7.1.min.js"></script>

</body>