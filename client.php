<?php
// بداية الجلسة لتتبع بيانات الجلسة والتحقق من تسجيل الدخول
session_start();
// تحديد نوع المستخدم المسجل الدخول
$userType = $_SESSION["USER"]["USERTYPE"];
// التوجيه إلى صفحة تسجيل الدخول في حالة عدم تسجيل الدخول
if (!isset($_SESSION['IS_LOGGED']) || $_SESSION['IS_LOGGED'] !== true) {
    header("Location: account/login.php");
    exit();
}
// التوجيه إلى صفحة المسوق في حالة تسجيل دخول سائق
if ($userType === "driver") {
    header("Location: account/driver.php");
    exit();
}
// التوجيه إلى صفحة المشرف في حالة تسجيل دخول مدير النظام
if ($userType === "admin") {
    header("Location: account/admin.php");
    exit();
}
// تضمين ملف قاعدة البيانات
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
    <title>الصفحة الرئيسية</title>
    <!-- تضمين أسلوب Bootstrap للتنسيق -->
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
    <!-- تضمين أسلوب الخرائط -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- أسلوب خاص للتحكم في حجم الخريطة -->
    <style>
        .map-container {
            width: 100%;
            height: 65vh; /* يجعل الخريطة تملأ الشاشة بالكامل */
        }
    </style>
</head>

<body>
    <!-- تضمين شريط التنقل -->
    <?php include("elements/navbar.php") ?>
    <div class="container">
        <!-- عرض ترحيبي للمستخدم -->
        <div>
            <h1>أهلاً <?php echo isset($_SESSION["USER"]["fullname"]) ? $_SESSION["USER"]["fullname"] : ''; ?></h1>
        </div>
        <!-- استعلام لاسترداد حجوزات العميل الحالية -->
        <?php
        // استعلام لاسترداد معرف المستخدم باستخدام البريد الإلكتروني
        $email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;
        $userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
        $userIDResult = $db->query($userIDQuery);
        $row = $userIDResult->fetch_assoc();
        $userID = $row['userID'];
        // استعلام لاسترداد معرف العميل باستخدام معرف المستخدم
        $customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
        $customerIDResult = $db->query($customerIDQuery);
        $row = $customerIDResult->fetch_assoc();
        $customerID = $row['customerID'];

        // استعلام لاسترداد حجوزات العميل
        $sql = "SELECT b.tripID, b.bookingTime, b.bookingID, b.bookingState, t.startLocation, t.arrivalLocation, t.startTime, t.cost, b.station
        FROM booking b
        INNER JOIN trip t ON b.tripID = t.tripID
        WHERE b.customerID = '$customerID'";
        $result = $db->query($sql);
        // عرض الحجوزات إذا كانت متاحة
        if ($result->num_rows > 0) {
            echo '<div class="row row-cols-1 row-cols-md-1 g-4">';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col">';
                // إنشاء حاوية لعرض الخريطة
                echo '<div class="map-container">';
                echo '<div id="map' . $row['tripID'] . '" style="height: 100%; width: 100%;"></div>';
                echo '</div>';
                // إنشاء بطاقة لعرض تفاصيل الحجز
                echo '<div class="card mt-3">';
                echo '<div class="card-body text-start">';
                echo '<h5 class="card-title mb-4 text-center">' . $row['arrivalLocation'] . '</h5>';
                // عرض تفاصيل الحجز
                echo '<p class="card-text mb-1"><strong>رقم الرحلة :</strong> ' . $row['tripID'] . '</p>';
                echo '<p class="card-text mb-1"><strong>محطة الوصول:</strong> ' . $row['arrivalLocation'] . '</p>';
                echo '<p class="card-text mb-1"><strong>محطة الوقوف:</strong> ' . $row['station'] . '</p>';
                echo '<p class="card-text mb-1"><strong>وقت الحجز:</strong> ' . $row['bookingTime'] . '</p>';
                echo '<p class="card-text mb-1"><strong>موقع الانطلاق:</strong> ' . $row['startLocation'] . '</p>';
                echo '<p class="card-text mb-1"><strong>وقت الانطلاق:</strong> ' . $row['startTime'] . '</p>';
                echo '<p class="card-text mb-1"><strong>سعر التذكرة:</strong> ' . $row['cost'] . '</p>';
                $bookingStatus = $row['bookingState'];
                $statusColor = ($bookingStatus === 'progress') ? 'warning' : 'danger';
                echo '<p class="card-text mb-4"><strong>حالة الحجز:</strong> <span class="badge bg-' . $statusColor . '">' . $bookingStatus . '</span></p>';
                // إضافة رابط تقييم الرحلة
                echo '<a href="rate_trip.php?tripID=' . $row['tripID'] . '" class="btn btn-primary">تقييم الرحلة</a>';
                echo '</div>';
                echo '<div class="card-footer">';
                if ($bookingStatus === "cancelled") {
                    echo '<form action="cancel_booking.php" method="post">';
                    echo '<input type="hidden" name="booking_id" value="' . $row['bookingID'] . '">';
                    echo '<input type="hidden" name="booking_status" value="progress">';
                    echo '<button class="btn btn-success btn-block" type="submit">إعادة الحجز</button>';
                    echo '</form>';
                } else {
                    echo '<form action="cancel_booking.php" method="post">';
                    echo '<input type="hidden" name="booking_id" value="' . $row['bookingID'] . '">';
                    echo '<input type="hidden" name="booking_status" value="cancelled">';
                    echo '<button class="btn btn-danger btn-block" type="submit">إلغاء الحجز</button>';
                    echo '</form>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo 'لا توجد حجوزات متاحة حالياً لهذا العميل.';
        }
        $db->close();
        ?>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>

    <script>
        function initMap(tripID) {
            var map = L.map('map' + tripID).setView([51.505, -0.09], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            navigator.geolocation.watchPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                var driverMarker = L.marker([lat, lon]).addTo(map);
                driverMarker.bindPopup("موقع السائق الحالي").openPopup();
            });
        }

        document.querySelectorAll('[id^="map"]').forEach(function(mapDiv) {
            var tripID = mapDiv.id.replace('map', '');
            initMap(tripID);
        });

        let notificationPermissionGranted = false;

function notifyCustomer() {
    if (!notificationPermissionGranted && "Notification" in window) {
        Notification.requestPermission().then(function(permission) {
            if (permission === "granted") {
                notificationPermissionGranted = true;
                new Notification("السائق وصل إلى موقعك!");
            }
        });
    } else if (notificationPermissionGranted) {
        new Notification("السائق وصل إلى موقعك!");
    }
}


        // فرضياً: يتم التحقق من وصول السائق
        var driverHasArrived = true;
        if (driverHasArrived) {
            notifyCustomer();
        }
    </script>
</body>
</html>
