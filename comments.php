<?php
session_start();
// الاتصال بقاعدة البيانات
include 'database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من أن الحقول معبأة بشكل صحيح
    if (!empty($_POST['tripID']) && !empty($_POST['texts'])) {
        // احتياطي لهجومات حقن الـ SQL
        $tripID = htmlspecialchars($_POST['tripID']);
        $texts = htmlspecialchars($_POST['texts']);
        $userID = $_SESSION["USER"]["USERID"];
        $customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
        $customerIDResult = $db->query($customerIDQuery);
        $customerID = $customerIDResult->fetch_row()[0];



        // إنشاء تاريخ وتوقيت التعليق
        $comment_date = date('Y-m-d H:i:s');

        // التحقق إذا كان المستخدم قد قيَّم الرحلة من قبل
        $check_query = "SELECT COUNT(*) FROM comments WHERE tripID = '$tripID' AND customerID = '$customerID'";
        $check_result = $db->query($check_query);
        $count = $check_result->fetch_row()[0];

        if ($count == 0) {
            // إدراج التعليق في قاعدة البيانات
            $sql = "INSERT INTO comments (tripID, customerID, comment_text) VALUES ('$tripID', '$customerID', '$texts')";

            if ($db->query($sql) === TRUE) {
                $_SESSION['comment_message'] = "تم إرسال التعليق بنجاح!";
            } else {
                $_SESSION['comment_message'] = "خطأ في إرسال التعليق: " . $db->error;
            }
        } else {
            $_SESSION['comment_message'] = "لقد قيَّمت هذه الرحلة من قبل.";
        }

        $db->close();
        header("Location: client.php");
        exit();
    } else {
        $_SESSION['comment_message'] = "الرجاء ملء كافة الحقول.";
        header("Location: client.php");
        exit();
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
    <title> تقييم الرحلة </title>
    <link rel="stylesheet" href="/rafi/assets/css/bootstrap.rtl.min.css">

</head>

<body>
    <?php include("elements/navbar.php") ?>
    <main class="rates">
        <?php
        include 'database.php';

        $tripID = isset($_GET['tripID']) ? $_GET['tripID'] : null;
        $customer_id = $_SESSION["USER"]["USERID"];

        if ($tripID) {
            $sql = "SELECT comment_text FROM comments WHERE booking_id = (SELECT bookingID FROM booking WHERE tripID = '$tripID' AND customerID = '$customer_id')";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>تقييمك السابق: " . $row['comment_text'] . "</p>";
            } else {
                // إذا لم يكن هناك تقييم سابق، اعرض نموذج التقييم
                echo '<form action="" method="post">
                        <div class="commentbooking">
                            <input type="hidden" name="booking_id" value="' . $tripID . '">
                            <div>
                                <div><label for="">تقييم</label></div>
                                <div><textarea name="texts" id="" cols="30" rows="10"></textarea></div>
                            </div>
                            <button type="submit">إرسال التقييم</button>
                        </div>
                    </form>';
            }
        } else {
            echo "<p>لم يتم تحديد رحلة لتقييمها.</p>";
        }

        $db->close();
        ?>
    </main>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>