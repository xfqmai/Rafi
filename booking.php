<?php
session_start();

// التحقق من تسجيل الدخول
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    // استرجاع تاريخ آخر تسجيل دخول والتحقق من مدته
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $currentDate = new DateTime();
    $interval = $lastLoginDate->diff($currentDate);
    
    // تحقق من مدة آخر تسجيل دخول
    if ($interval->days > 1) {
        // إذا كانت المدة أكبر من يوم واحد، فقم بتسجيل الخروج وإعادة التوجيه إلى صفحة تسجيل الدخول
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
} else {
    // إذا لم يتم تسجيل الدخول، قم بإعادة التوجيه إلى صفحة تسجيل الدخول
    header("Location: account/login.php");
    exit();
}

// تضمين ملفات القاعدة والوظائف
include "database.php";
include "functions.php";

$message = "";

// استعلام للحصول على مواقع الانطلاق
$sql = "SELECT DISTINCT startLocation FROM trip";
$startLocations = $db->query($sql);

// استعلام للحصول على مواقع الوصول
$sqls = "SELECT DISTINCT arrivalLocation FROM trip";
$arrivalLocations = $db->query($sqls);

// عملية POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // التحقق من وجود قيم محددة لموقع الانطلاق والوصول والمحطة
    if (isset($_POST['startLocation']) && isset($_POST['arrivalLocation']) && isset($_POST['station'])) {
        $data = $_POST;
        $email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;
        $tripID = $data['tripID'];
        $station = $data['station']; // استقبال قيمة المحطة
        $userID = $_SESSION['USER']['USERID'];
        
        // استعلام للحصول على سعة الرحلة ومعرف السائق
        $capacityQuery = "SELECT capacity, driverID FROM trip WHERE tripID = '$tripID'";
        $capacityResult = $db->query($capacityQuery);
        $row = $capacityResult->fetch_assoc();
        $capacity = $row['capacity'];
        $driverID = $row["driverID"];
        
        // استعلام للحصول على معرف المستخدم للسائق
        $userIDQuery = "SELECT userID FROM driver WHERE driverID = '$driverID'";
        $userIDResult = $db->query($userIDQuery);
        $row = $userIDResult->fetch_assoc();
        $driverUserID = $row['userID'];
        
        // استعلام للحصول على عدد الحجوزات الحالية للرحلة
        $bookingCountQuery = "SELECT COUNT(*) AS currentBookings FROM booking WHERE tripID = '$tripID'";
        $bookingCountResult = $db->query($bookingCountQuery);
        $row = $bookingCountResult->fetch_assoc();
        $currentBookings = $row['currentBookings'];
        
        // استعلام للحصول على عدد الحجوزات الحالية للمستخدم
        $userBookingCountQuery = "SELECT COUNT(*) AS userBookings FROM booking WHERE customerID = (SELECT customerID FROM customer WHERE userID = '$userID')";
        $userBookingCountResult = $db->query($userBookingCountQuery);
        $row = $userBookingCountResult->fetch_assoc();
        $userBookings = $row['userBookings'];

        if ($currentBookings < $capacity && $userBookings == 0) {
            // إذا كانت هناك سعة متاحة في الرحلة ولم يكن للمستخدم حجز سابق، قم بعملية الحجز
            $errors = booking_c($data, $email);
            if (count($errors) == 0) {
                // إذا تمت عملية الحجز بنجاح، قم بإعادة التوجيه إلى صفحة العميل
                header("Location: client.php");
                exit();
            }
        } else {
            // إذا كانت الرحلة مكتملة السعة أو كان للمستخدم حجز سابق، عرض رسالة مناسبة
            $message = ($currentBookings >= $capacity) ? "<div class='errors'>عذرًا، لقد تم الوصول إلى السعة القصوى لهذه الرحلة.</div>" : "<div class='errors'>عذرًا، لديك حجز سابق ولا يمكنك الحجز في رحلة أخرى.</div>";
        }
    } else {
        // إذا لم يتم إدخال قيم لموقع الانطلاق والوصول والمحطة
        $message = ($currentBookings >= $capacity) ? "<div class='errors'>عذرًا، لقد تم الوصول إلى السعة القصوى لهذه الرحلة.</div>" : "<div class='errors'>عذرًا، لديك حجز سابق ولا يمكنك الحجز في رحلة أخرى.</div>";
    }
}

$maxBookingsAllowed = 1;

// استعلام للحصول على معرف المستخدم وعدد الحجوزات
$email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;
$userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
$userIDResult = $db->query($userIDQuery);
$row = $userIDResult->fetch_assoc();
$userID = $row['userID'];
$customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
$customerIDResult = $db->query($customerIDQuery);
$row =


// الاستعلام للحصول على معرف المستخدم وعدد الحجوزات
$email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;
$userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
$userIDResult = $db->query($userIDQuery);
$row = $userIDResult->fetch_assoc();
$userID = $row['userID'];
$customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
$customerIDResult = $db->query($customerIDQuery);
$row = $customerIDResult->fetch_assoc();
$customerID = $row['customerID'];
$bookingCountQuery = "SELECT COUNT(*) AS bookingCount FROM booking WHERE customerID = '$customerID'";
$bookingCountResult = $db->query($bookingCountQuery);
$row = $bookingCountResult->fetch_assoc();
$bookingCount = $row['bookingCount'];

// التحقق من عدد الحجوزات المسموح بها
if ($bookingCount >= $maxBookingsAllowed) {
    $message = "لقد وصلت إلى الحد الأقصى لعدد الحجوزات المسموح بها ولا يمكنك إجراء المزيد.";
}
?>

<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
    <title>حجز الرحلة</title>
</head>

<body>
    <?php include("elements/navbar.php") ?>
    <div class="p-6 m-3">
        <div class="justify-content-center mt-5">
            <div><?php echo $message; ?></div>
            <div class="">
                <form action="booking.php" method="get" class="border rounded p-4 shadow d-flex gap-3 align-items-center">
                    <div class="mb-3">
                        <h2 class="text-center">اختيار موقع الانطلاق والوصول</h2>
                    </div>
                    <div class="mb-3">
                        <label for="startLocation" class="form-label">موقع الانطلاق:</label>
                        <select name="startLocation" id="startLocation" class="form-select">
                            <?php
                            while ($row = $startLocations->fetch_assoc()) {
                            ?>
                                <option value="<?php echo $row['startLocation']; ?>"><?php echo $row['startLocation']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="arrivalLocation" class="form-label">موقع الوصول:</label>
                        <select name="arrivalLocation" id="arrivalLocation" class="form-select">
                            <?php
                            while ($row = $arrivalLocations->fetch_assoc()) {
                            ?>
                                <option value="<?php echo $row['arrivalLocation']; ?>"><?php echo $row['arrivalLocation']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="station" class="form-label">المحطة:</label>
                        <input type="text" name="station" id="station" class="form-control">
                    </div>
                    <div class="text-center">
                        <button type="submit" name="send" class="btn btn-primary">بحث</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="justify-content-center mt-5">
            <div class="">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>حجز</th>
                            <th>وقت الانطلاق</th>
                            <th>موقع الوصول</th>
                            <th>وقت الرحلة</th>
                            <th>سعر الرحلة</th>
                            <th>معرف الرحلة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET['send'])) {
                            if (isset($_GET['startLocation']) && isset($_GET['arrivalLocation']) && isset($_GET['station'])) {
                                $startLocation = $_GET['startLocation'];
                                $arrivalLocation = $_GET['arrivalLocation'];
                                $station = $_GET['station'];
                                $sqlss = "SELECT * FROM trip WHERE startLocation='$startLocation' AND arrivalLocation='$arrivalLocation'";
                                $resultss = $db->query($sqlss);
                                if ($resultss->num_rows > 0) {
                                    while ($rowss = $resultss->fetch_assoc()) {
                                        echo "<tr>
                                            <td>
                                                <form action='booking.php' method='POST'>
                                                    <input type='hidden' name='tripID' value='" . $rowss['tripID'] . "'>
                                                    <input type='hidden' name='startLocation' value='" . $startLocation . "'>
                                                    <input type='hidden' name='arrivalLocation' value='" . $arrivalLocation . "'>
                                                    <input type='hidden' name='station' value='" . $station . "'> <!-- تمرير قيمة المحطة -->
                                                    <button type='submit' name='send' class='btn btn-primary btn-sm'>حجز</button>
                                                </form>
                                            </td>
                                            <td>" . $rowss['startLocation'] . "</td>
                                            <td>" . $rowss['arrivalLocation'] . "</td>
                                            <td>" . $rowss['startTime'] . "</td>
                                            <td>" . $rowss['cost'] . "</td>
                                            <td>" . $rowss['tripID'] . "</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='errors'>لا توجد رحلات متاحة بهذا البحث.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='errors'>يرجى إدخال موقع الانطلاق والوصول والمحطة.</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
