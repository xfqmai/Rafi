<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="/rafi/assets/image/11.png" width="80" alt="Rafi" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <?php
                if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
                    $userType = $_SESSION["USER"]["USERTYPE"];
                    // تحقق من نوع المستخدم لتحديد الروابط المناسبة
                    if ($userType === "customer") {
                        echo '<li class="nav-item">
                                <a class="nav-link" href="/rafi/client.php">الرحلات المحجوزة</a>
                            </li>';
                        echo '<li class="nav-item">
                                <a class="nav-link" href="/rafi/booking.php">حجز رحلة</a>
                            </li>';
                    }
                    if ($userType === "driver") {
                        echo '<li class="nav-item">
                                <a class="nav-link" href="/rafi/client.php">لوحة التحكم</a>
                            </li>';
                        echo '<li class="nav-item">
                                <a class="nav-link" href="/rafi/create_trip.php">إنشاء رحلة</a>
                            </li>';
                        echo '<li class="nav-item">
                                <a class="nav-link" href="/rafi/trips_go.php">الرحلات الحالية</a>
                            </li>';
                        echo '<li class="nav-item">
                                <a class="nav-link" href="/rafi/trips_end.php">الرحلات المنتهية</a>
                            </li>';
                    }
                    if ($userType === "admin") {
                        // روابط خاصة بالإدارة
                    }
                }
                ?>
            </ul>
        </div>
        <div class="d-flex gap-4">
        <?php
include 'database.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['IS_LOGGED']) && $_SESSION['IS_LOGGED'] === true) {
    $userID = $_SESSION['USER']['USERID'];
    $userType = $_SESSION['USER']['USERTYPE'];

    // استعلام لجلب الإشعارات غير المقروءة
    $query = "SELECT * FROM notifications WHERE userID = ? AND isRead = 0 ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<div class="dropdown my-auto">
            <img class="btn btn-light dropdown-toggle w-40 h-40 rounded-pill" src="/rafi/assets/image/not_icon.png" width="50" role="button" data-bs-toggle="dropdown" aria-expanded="false" />
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notificationID = $row['notificationID']; // تأكد من وجود عمود notificationID في جدول الإشعارات
            $createdAt = date('Y-m-d H:i', strtotime($row['created_at']));
            
            // تحقق إذا كان الإشعار يتعلق بإلغاء حجز
            if (strpos($row['message'], 'إلغاء الحجز') !== false) {
                // استخراج رقم الحجز من الرسالة
                preg_match('/\d+/', $row['message'], $matches);
                if (isset($matches[0])) {
                    $bookingID = $matches[0];

                    // استعلام لجلب اسم العميل المرتبط بالحجز
                    $customerQuery = "SELECT cu.fullname
                                      FROM customer cu
                                      JOIN booking b ON cu.customerID = b.customerID
                                      WHERE b.bookingID = ?";
                    $stmt2 = $db->prepare($customerQuery);
                    $stmt2->bind_param("i", $bookingID);
                    $stmt2->execute();
                    $customerResult = $stmt2->get_result();

                    if ($customerResult->num_rows > 0) {
                        $customerName = $customerResult->fetch_assoc()['fullname'];
                        echo '<li>
                                <a class="dropdown-item" href="#">' . $row['message'] . ' للعميل ' . $customerName . ' - ' . $createdAt . '</a>
                              </li>';
                    } else {
                        echo '<li>
                                <a class="dropdown-item" href="#">' . $row['message'] . ' - ' . $createdAt . '</a>
                              </li>';
                    }
                    $stmt2->close();
                }
            } else {
                echo '<li>
                        <a class="dropdown-item" href="#">' . $row['message'] . ' - ' . $createdAt . '</a>
                      </li>';
            }
        }
    } else {
        echo '<li class="dropdown-item">لا توجد إشعارات جديدة</li>';
    }

    echo '</ul> </div>';
    echo '<div class="dropdown">
            <img class="btn btn-light dropdown-toggle w-40 h-40 rounded-pill" src="/rafi/assets/image/22.png" width="60" role="button" data-bs-toggle="dropdown" aria-expanded="false" />
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">الملف الشخصي</a></li>
                <li><a class="dropdown-item" href="/rafi/account/logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>';
} else {
    echo '<a href="/rafi/account/login.php" class="btn btn-outline-primary">تسجيل الدخول</a>';
    echo '<a href="/rafi/account/signup.php" class="btn btn-primary">تسجيل</a>';
}
?>

        </div>
    </div>
</nav>
