<?php
session_start();

// التحقق مما إذا كان المستخدم مسجل دخوله
if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    // الحصول على تاريخ آخر تسجيل دخول
    $lastLoginDate = new DateTime($_SESSION["USER"]["LOGIN_DATE"]);
    $currentDate = new DateTime();

    // حساب الفرق بين تاريخ اليوم وتاريخ آخر تسجيل دخول
    $interval = $lastLoginDate->diff($currentDate);

    // إذا كان الفرق أكبر من يوم، تسجيل الخروج وإعادة التوجيه لصفحة تسجيل الدخول
    if ($interval->days > 1) {
        unset($_SESSION["USER"]);
        unset($_SESSION['IS_LOGGED']);
        header("Location: account/login.php");
        exit();
    }
} else {
    // إذا لم يكن المستخدم مسجل دخوله، إعادة التوجيه لصفحة تسجيل الدخول
    header("Location: account/login.php");
    exit();
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
    <title>المسؤول</title>
</head>

<body>
    <?php include("elements/navbar.php") ?>
    <main class="container my-5">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">الرحلات المنتهية</h5>
                        <p class="card-text display-4">
                            <?php
                            // عرض عدد الرحلات المنتهية
                            include 'database.php';
                            $sql = "SELECT COUNT(*) AS completed_trips FROM finished_trips";
                            $result = $db->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['completed_trips'];
                            } else {
                                echo "0";
                            }
                            $db->close();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">المبلغ الإجمالي للرحلات</h5>
                        <p class="card-text display-4">
                            <?php
                            // عرض المبلغ الإجمالي للرحلات
                            include 'database.php';
                            $sql = "SELECT SUM(cost) AS total_cost FROM finished_trips";
                            $result = $db->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['total_cost'];
                            } else {
                                echo "0";
                            }
                            $db->close();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">الرحلات الملغية</h5>
                        <p class="card-text display-4">
                            <?php
                            // عرض عدد الرحلات الملغية
                            include 'database.php';
                            $sql = "SELECT COUNT(*) AS cancelled_trips FROM trip WHERE status = 'cancelled'";
                            $result = $db->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['cancelled_trips'];
                            } else {
                                echo "0";
                            }
                            $db->close();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">تقييم السائقين</h5>
                        <p class="card-text display-4">
                           
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">تقييم العملاء</h5>
                        <p class="card-text display-4">
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">عدد السائقين</h5>
                        <p class="card-text display-4">
                            <?php
                            // عرض عدد السائقين
                            include 'database.php';
                            $sql = "SELECT COUNT(*) AS driver_count FROM driver";
                            $result = $db->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['driver_count'];
                            } else {
                                echo "0";
                            }
                            $db->close();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h5 class="card-title">عدد العملاء</h5>
                        <p class="card-text display-4">
                            <?php
                            // عرض عدد العملاء
                            include 'database.php';
                            $sql = "SELECT COUNT(*) AS customer_count FROM customer";
                            $result = $db->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo $row['customer_count'];
                            } else {
                                echo "0";
                            }
                            $db->close();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="/rafi/assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>
