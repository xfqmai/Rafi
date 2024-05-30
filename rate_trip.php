<?php
session_start();
$userType = $_SESSION["USER"]["USERTYPE"];
if (!isset($_SESSION['IS_LOGGED']) || $_SESSION['IS_LOGGED'] !== true) {
    header("Location: account/login.php");
    exit();
}
include 'database.php';

// إذا كان المستخدم ليس عميلاً، يتم توجيهه إلى الصفحة الرئيسية للمستخدمين
if ($userType !== "customer") {
    header("Location: index.php");
    exit();
}

// الحصول على معرف العميل
$email = isset($_SESSION['USER']["EMAIL"]) ? $_SESSION['USER']["EMAIL"] : null;
$userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
$userIDResult = $db->query($userIDQuery);
$row = $userIDResult->fetch_assoc();
$userID = $row['userID'];
$customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
$customerIDResult = $db->query($customerIDQuery);
$row = $customerIDResult->fetch_assoc();
$customerID = $row['customerID'];

// الحصول على بيانات الرحلة المطلوبة للتقييم
$tripID = isset($_GET['tripID']) ? $_GET['tripID'] : null;
if (!$tripID) {
    // في حالة عدم توفر معرف الرحلة، يتم توجيه المستخدم إلى الصفحة الرئيسية للعميل
    header("Location: customer_home.php");
    exit();
}

// استعلام SQL للحصول على بيانات الرحلة لعرضها في الصفحة
$sql = "SELECT * FROM trip WHERE tripID = '$tripID'";
$result = $db->query($sql);
if ($result->num_rows == 0) {
    // إذا لم يتم العثور على الرحلة المطلوبة، يتم توجيه المستخدم إلى الصفحة الرئيسية للعميل
    header("Location: customer_home.php");
    exit();
}
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="title icon" type="image" href="/rafi/assets/image/22.png" />
    <title>تقييم الرحلة</title>
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">
</head>

<body>
    <?php include("elements/navbar.php") ?>
    <div class="container">
        <div class="mt-5">
            <h1 class="text-center">تقييم الرحلة</h1>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">بيانات الرحلة</h5>
                <p class="card-text"><strong>موقع الانطلاق:</strong> <?php echo $row['startLocation']; ?></p>
                <p class="card-text"><strong>موقع الوصول:</strong> <?php echo $row['arrivalLocation']; ?></p>
                <p class="card-text"><strong>وقت الانطلاق:</strong> <?php echo $row['startTime']; ?></p>
                <p class="card-text"><strong>تكلفة الرحلة:</strong> <?php echo $row['cost']; ?></p>
            </div>
        </div>
        <div class="mt-3">
            <h5 class="text-center">قم بتقييم الرحلة</h5>
            <form action="submit_rating.php" method="post">
                <input type="hidden" name="tripID" value="<?php echo $tripID; ?>">
                
                <form action="comments.php" method="post">
    <input type="hidden" name="tripID" value="<?php echo $row['tripID']; ?>">
    <div class="mb-3">
        <label for="comment-textarea" class="form-label">إضافة تعليق</label>
        <textarea class="form-control" name="texts" id="comment-textarea" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" formaction="client.php">إرسال</button>
     </form>

        </div>
    </div>
</body>

</html>
