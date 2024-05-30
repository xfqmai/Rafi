<?php

include 'database.php';

if (isset($_POST['tripID'])) {
    $tripID = $_POST['tripID'];

    // إنشاء استعلام ينقل الرحلة المحددة إلى جدول finished_trips ثم يحذفها من جدول trip
    $sql = "INSERT INTO finished_trips (tripID, driverID, startLocation, arrivalLocation, startTime, cost)
            SELECT tripID, driverID, startLocation, arrivalLocation, startTime, cost
            FROM trip
            WHERE tripID = $tripID;

            DELETE FROM trip WHERE tripID = $tripID;";

    // تنفيذ الاستعلام
    if ($db->multi_query($sql) === TRUE) {
        echo "Trip moved successfully."; // رسالة نجاح في نقل الرحلة
    } else {
        echo "Error moving trip: " . $db->error; // رسالة خطأ في حالة فشل عملية النقل
    }
}

$db->close();
