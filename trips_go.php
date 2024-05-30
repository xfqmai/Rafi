<?php
// بدء الجلسة
session_start();

// التحقق مما إذا كان المستخدم قد سجل الدخول وما إذا كانت الجلسة مفعلة ومستمرة
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    // حساب فارق الوقت بين آخر تسجيل دخول والوقت الحالي
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $currentDate = new DateTime();
    $interval = $lastLoginDate->diff($currentDate);

    // إذا كان فارق الوقت أكثر من يوم واحد، يتم تسجيل المستخدم خارج النظام وإعادته إلى صفحة تسجيل الدخول
    if ($interval->days > 1) {
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
} else {
    // إعادة المستخدم إلى صفحة تسجيل الدخول إذا لم يكن قد سجل الدخول
    header("Location: account/login.php");
    exit();
}

// تضمين ملف قاعدة البيانات
include 'database.php';

// الحصول على عنوان البريد الإلكتروني للمستخدم الحالي
$email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;

// الحصول على معرف المستخدم باستخدام عنوان البريد الإلكتروني
$userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
$userIDResult = $db->query($userIDQuery);
$row = $userIDResult->fetch_assoc();
$userID = $row['userID'];

// البحث عن معرف السائق باستخدام معرف المستخدم
$driverIDQuery = "SELECT driverID FROM driver WHERE userID = '$userID'";
$driverIDResult = $db->query($driverIDQuery);
$row = $driverIDResult->fetch_assoc();
$driverID = $row['driverID'];

// استعلام لاسترداد جميع الرحلات الحالية للسائق الحالي
$sql = "SELECT t.*, (SELECT COUNT(*) FROM booking WHERE tripID = t.tripID) AS num_customers FROM trip t WHERE driverID = $driverID";
$currentTrips = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <title>الرحلات الحالية</title>
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
</head>

<body>
    <?php include("elements/navbar.php") ?>
    <div class="rafiheader1">
        <div class="mx-6 p-4">
            <?php
            // عرض الرحلات الحالية إذا كانت متاحة
            if ($currentTrips->num_rows > 0) {
                echo '<div class="w-full">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>رقم الرحلة</th>';
                echo '<th>موقع الأنطلاق</th>';
                echo '<th>موقع الوصول</th>';
                echo '<th>وقت الانطلاق</th>';
                echo '<th>سعر التذكرة</th>';
                echo '<th>عدد العملاء</th>';
                echo '<th>سعة الرحلة</th>';
                echo '<th>تكلفة الرحلة</th>';
                echo '<th>حالة سعة الرحلة</th>';
                echo '<th>حالة الرحلة</th>';
                echo '<th>الأحداث</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $currentTrips->fetch_assoc()) {
                    // تحديد لون الحالة (في التقدم أو تم الإلغاء)
                    $statusColor = ($row['status'] === 'progress') ? 'warning' : 'danger';
                    echo '<tr>';
                    echo '<td>' . $row['tripID'] . '</td>';
                    echo '<td>' . $row['startLocation'] . '</td>';
                    echo '<td>' . $row['arrivalLocation'] . '</td>';
                    echo '<td>' . $row['startTime'] . '</td>';
                    echo '<td>' . $row['cost'] . '</td>';
                    echo '<td>' . $row['num_customers'] . '</td>';
                    echo '<td>' . $row['capacity'] . '</td>';
                    echo '<td>' . ($row['cost'] * $row['num_customers']) . '</td>';
                    // عرض حالة سعة الرحلة
                    if ($row['capacity'] === $row['num_customers']) {
                        echo '<td><span>ممتلئ</span></td>';
                    } else {
                        echo '<td><span>لم يكتمل العدد</span></td>';
                    }
                    // عرض حالة الرحلة
                    if ($row['status'] === "progress") {
                        echo '<td><span class="badge bg-' . $statusColor . '">حجز جاري</span></td>';
                    } else {
                        echo '<td><span class="badge bg-' . $statusColor . '">تم الغاء الحجز</span></td>';
                    }
                    echo '<td class="d-flex gap-3">';
                    if ($row['status'] === "cancelled") {
                        echo '<form action="actions_trip.php" method="post">';
                        echo '<input type="hidden" name="trip_id" value="' . $row['tripID'] . '">';
                        echo '<input type="hidden" name="trip_status" value="progress">';
                        echo '<button class="btn btn-success btn-sm btn-block" type="submit">إعادة فتح الرحلة</button>';
                        echo '</form>';
                    } else {
                        echo '<form action="actions_trip.php" method="post">';
                        echo '<input type="hidden" name="trip_id" value="' . $row['tripID'] . '">';
                        echo '<input type="hidden" name="trip_status" value="cancelled">';
                        echo '<button class="btn btn-danger btn-sm btn-block" type="submit">إلغاء الرحلة</button>';
                        echo '</form>';
                    }
                    echo '<button class="btn btn-primary btn-sm" onclick="moveToFinishedTrips(' . $row['tripID'] . ')">انهاء الرحلة</button></td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo 'لا توجد رحلات متاحة حاليًا لهذا السائق.';
            }

            $db->close();
            ?>
        </div>

        <script>
    function moveToFinishedTrips(tripID) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'mov_trip.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Reload the page after successful trip move
                if (xhr.responseText.trim() === 'Trip moved successfully.') {
                    location.reload();
                } else {
                    console.log(xhr.responseText);
                }
            }
        };
        xhr.send('tripID=' + tripID);
    }
</script>


    <script>
        function showSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.display = 'flex';
        }

        function hideSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.display = 'none';
        }
    </script>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
