<?php
// بدء الجلسة لاستخدام الـ session
session_start();

// التحقق مما إذا كان المستخدم قد قام بتسجيل الدخول وما إذا كانت الجلسة مفعلة ومستمرة
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    // إذا كان المستخدم قد سجل الدخول، يتم التحقق من مدة آخر تسجيل دخول
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $currentDate = new DateTime();
    $userType = $_SESSION["USER"]["USERTYPE"];

    // حساب الفارق بين آخر تسجيل دخول والوقت الحالي
    $interval = $lastLoginDate->diff($currentDate);

    // إذا كانت المدة أكثر من يوم واحد، يتم تسجيل المستخدم خارج النظام وإعادته إلى صفحة تسجيل الدخول
    if ($interval->days > 1) {
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
} else {
    // إذا لم يكن المستخدم مسجلاً الدخول، يتم توجيهه مباشرةً إلى صفحة تسجيل الدخول
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

// استعلام لاسترداد جميع الرحلات المنتهية للسائق الحالي
$finishedTripsQuery = "SELECT * FROM finished_trips WHERE driverID = $driverID";
$finishedTrips = $db->query($finishedTripsQuery);

// استعلام لاسترداد جميع الرحلات الملغية للسائق الحالي
$cancelledTripsQuery = "SELECT * FROM trip WHERE driverID = $driverID AND status = 'cancelled'";
$cancelledTrips = $db->query($cancelledTripsQuery);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <title>الرحلات المنتهية</title>
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
</head>

<body>

    <?php include("elements/navbar.php") ?>

    <div class="rafiheader1">
        <div class="mx-6 p-4">
            <?php
            // عرض الرحلات المنتهية إذا كانت متاحة
            if ($finishedTrips->num_rows > 0) {
                echo '<div class="w-full">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Start Location</th>';
                echo '<th>Arrival Location</th>';
                echo '<th>Start Time</th>';
                echo '<th>Cost</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $finishedTrips->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['tripID'] . '</td>';
                    echo '<td>' . $row['startLocation'] . '</td>';
                    echo '<td>' . $row['arrivalLocation'] . '</td>';
                    echo '<td>' . $row['startTime'] . '</td>';
                    echo '<td>' . $row['cost'] . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                // رسالة عند عدم وجود رحلات منتهية
                echo 'لا توجد رحلات منتهية لهذا السائق.';
            }
            ?>
        </div>

        <div class="mx-6 p-4 mt-5">
            <h2>الرحلات الملغية</h2>
            <?php
            // عرض الرحلات الملغية إذا كانت متاحة
            if ($cancelledTrips->num_rows > 0) {
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
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $cancelledTrips->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['tripID'] . '</td>';
                    echo '<td>' . $row['startLocation'] . '</td>';
                    echo '<td>' . $row['arrivalLocation'] . '</td>';
                    echo '<td>' . $row['startTime'] . '</td>';
                    echo '<td>' . $row['cost'] . '</td>';
                    $num_customers = isset($row['num_customers']) ? $row['num_customers'] : 0; // التأكد من وجود المفتاح
                    echo '<td>' . $num_customers . '</td>';
                    echo '<td>' . $row['capacity'] . '</td>';
                    echo '<td>' . ($row['cost'] * $num_customers) . '</td>';
                    echo '<td>' . ($row['capacity'] === $num_customers ? 'ممتلئ' : 'لم يكتمل العدد') . '</td>';
                    echo '<td>تم الغاء الحجز</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo 'لا توجد رحلات ملغية حاليًا.';
            }
            ?>
        </div>
    </div>

    <script>
        function showSidebar() {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'flex'
        }

        function hideSidebar() {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'none'
        }
    </script>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
// Close the database connection at the end of the script
$db->close();
?>
