<?php
// بداية الجلسة لتتبع بيانات الجلسة
session_start();

// التحقق من تسجيل الدخول ونوع المستخدم
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    $userType = $_SESSION["USER"]["USERTYPE"];

    // في حالة عدم تسجيل دخول سائق، سيتم توجيه المستخدم إلى صفحة العميل
    if ($userType !== "driver") {
        header("Location: client.php");
        exit();
    }
} else {
    // في حالة عدم تسجيل الدخول، سيتم توجيه المستخدم إلى صفحة تسجيل الدخول
    header("Location: account/login.php");
    exit();
}

// تعيين متغير driverID بقيمة معرف السائق من جلسة المستخدم
$driverID = $_SESSION["USER"]["USERID"];

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
    <title> الصفحة الرئيسية </title>
    <!-- تضمين أسلوب Bootstrap للتنسيق -->
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
</head>

<body>
    <!-- تضمين شريط التنقل -->
    <?php include("elements/navbar.php") ?>
    <!-- قسم رئيسي لعرض المعلومات والجدول -->
    <div class="rafiheader1">
        <div>
            <!-- عرض تحية للمستخدم -->
            <h1>اهلا <?php echo isset($_SESSION["USER"]["fullname"]) ? $_SESSION["USER"]["fullname"] : ''; ?></h1>
        </div>
        <div class="mx-6 p-4">
            <?php
            // تضمين ملف قاعدة البيانات
            include 'database.php';
            // استعلام SQL لاستعراض الحجوزات للسائق الحالي
            $sql = "SELECT c.fullname, b.tripID, b.bookingTime, b.bookingState, t.startLocation, t.arrivalLocation, t.startTime, t.cost, b.station
                    FROM booking b
                    INNER JOIN customer c ON b.customerID = c.customerID
                    INNER JOIN trip t ON b.tripID = t.tripID
                    WHERE t.driverID = '$driverID'";

            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                // عرض البيانات في جدول إذا كانت متاحة
                echo '<div class="w-full">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>اسم العميل </th>';
                echo '<th>رقم الرحلة</th>';
                echo '<th>وقت الحجز</th>';
                echo '<th>حالة الحجز</th>';
                echo '<th>موقع الإنطلاق</th>';
                echo '<th>موقع الوصول</th>';
                echo '<th>وقت الإنطلاق</th>';
                echo '<th>سعر التذكرة</th>';
                echo '<th>المحطة</th>'; // إضافة عمود جديد لقيمة المحطة
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    // عرض بيانات الحجوزات في الصفوف
                    echo '<tr>';
                    echo '<td>' . $row['fullname'] . '</td>';
                    echo '<td>' . $row['tripID'] . '</td>';
                    echo '<td>' . $row['bookingTime'] . '</td>';
                    echo '<td>' . $row['bookingState'] . '</td>';
                    echo '<td>' . $row['startLocation'] . '</td>';
                    echo '<td>' . $row['arrivalLocation'] . '</td>';
                    echo '<td>' . $row['startTime'] . '</td>';
                    echo '<td>' . $row['cost'] . '</td>';
                    echo '<td>' . $row['station'] . '</td>'; // Adding new column for station value
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo 'لا توجد حجوزات متاحة حاليًا لهذا السائق.';
            }

            $db->close();
            ?>

        </div>
    </div>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>

    <script>
        function showSidebar() {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'flex'
        }

        function hideSidebar() {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'none'
        }

        function moveFinishedTrips() {
            alert("سيتم نقل الرحلات المنتهية.");
        }
    </script>
</body>

</html>
