<?php

// دالة لتسجيل المستخدمين كعملاء
function signc($data)
{
    $errors = array();
    
    // التحقق من صحة الحقول
    if (empty($data['fullname']) || empty($data['borndate']) || empty($data['phonenumber']) || empty($data['password']) || empty($data['email']) || empty($data['governorateID']) || empty($data['directorateID'])) {
        $errors[] = "*الرجاء تعبئة كل الحقول";
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "*الرجاء إدخال بريد إلكتروني صحيح";
    }
    if (strlen(trim($data['password'])) < 8) {
        $errors[] = "*كلمة المرور يجب أن تكون على الأقل 8 أحرف";
    }

    if (count($errors) == 0) {
        // تضمين 'conn.php' لتأسيس اتصال بقاعدة البيانات
        include 'database.php';

        // التحقق مما إذا كان البريد الإلكتروني موجود بالفعل
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $data['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = "*الإيميل موجود سابقاً";
        }
        $stmt->close();

        // إذا لم توجد أخطاء، قم بإدراج البيانات في جدول 'customer'
        if (count($errors) == 0) {
            // إدراج البيانات في جدول 'users'
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (email, password, userType) VALUES (?, ?, 'customer')");
            $stmt->bind_param("ss", $data['email'], $hashed_password);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                $errors[] = "*حدث خطأ أثناء إدراج البيانات في جدول المستخدمين";
            } else {
                // الحصول على معرف المستخدم الأخير المدخل
                $userID = $db->insert_id;
                $stmt->close();

                // إدراج البيانات في جدول 'customer'
                $stmt = $db->prepare("INSERT INTO customer (fullname, borndate, phonenumber, governorateID, directorateID, userID) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiiis", $data['fullname'], $data['borndate'], $data['phonenumber'], $data['governorateID'], $data['directorateID'], $userID);
                $stmt->execute();
                if ($stmt->affected_rows === 0) {
                    $errors[] = "*حدث خطأ أثناء إدراج البيانات في جدول العملاء";
                }
                $stmt->close();
            }
        }

        // إغلاق اتصال قاعدة البيانات
        $db->close();
    }

    return $errors;
}

// دالة لتسجيل المستخدمين كسائقين
function signd($data)
{
    $errors = array();
    // التحقق من صحة الحقول
    if (empty($data['fullname']) || empty($data['age']) || empty($data['phonenumber']) || empty($data['email']) || empty($data['password']) || empty($data['identificationNumber']) || empty($data['drivingLicenseNO']) || empty($data['vehicleLicenseNO']) || empty($data['carName']) || empty($data['carModel']) || empty($data['plateNumber']) || empty($data['governorateID']) || empty($data['directorateID'])) {
        $errors[] = "*الرجاء تعبئة كل الحقول";
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "*الرجاء إدخال بريد إلكتروني صحيح";
    }
    if (strlen(trim($data['password'])) < 8) {
        $errors[] = "*كلمة المرور يجب أن تكون على الأقل 8 أحرف";
    }

    if (count($errors) == 0) {
        // تضمين 'conn.php' لتأسيس اتصال بقاعدة البيانات
        include 'database.php';

    // التحقق مما إذا كان البريد الإلكتروني موجود بالفعل
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "*الإيميل موجود سابقاً";
    }
    $stmt->close();

    // إذا لم توجد أخطاء، قم بإدراج البيانات في جدول 'driver'
    if (count($errors) == 0) {
        // إدراج البيانات في جدول 'users'
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (email, password, userType) VALUES (?, ?, 'driver')");
        $stmt->bind_param("ss", $data['email'], $hashed_password);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $errors[] = "*حدث خطأ أثناء إدراج البيانات في جدول المستخدمين";
        } else {
            // الحصول على معرف المستخدم الأخير المدخل
            $userID = $db->insert_id;
            $stmt->close();

            // إدراج البيانات في جدول 'driver'
            $stmt = $db->prepare("INSERT INTO driver (fullname, age, phonenumber, governorateID, directorateID, identificationNumber, drivingLicenseNO, vehicleLicenseNO, carName, carModel, plateNumber, userID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siiiiiiisiii", $data['fullname'], $data['age'], $data['phonenumber'], $data['governorateID'], $data['directorateID'], $data['identificationNumber'], $data['drivingLicenseNO'], $data['vehicleLicenseNO'], $data['carName'], $data['carModel'], $data['plateNumber'], $userID);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                $errors[] = "*حدث خطأ أثناء إدراج البيانات في جدول السائقين";
            }
            $stmt->close();
        }
    }

    // إغلاق اتصال قاعدة البيانات
    $db->close();
}

return $errors;
    }

// دالة لتسجيل الدخول
function login($data)
{
$errors = array(); // مصفوفة لتخزين الأخطاء

// التحقق من صحة البريد الإلكتروني
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "*الرجاء إدخال بريد إلكتروني صحيح";
}

// التحقق من أن كلمة السر طويلة بما يكفي
if (strlen(trim($data['password'])) < 8) {
    $errors[] = "*يجب أن تكون كلمة المرور على الأقل 8 أحرف";
}
if (count($errors) == 0) {
    include 'database.php';

    $email = $db->real_escape_string($data['email']);
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        // استخدام password_verify للتحقق من صحة كلمة المرور
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        if (password_verify($data['password'], $hashed_password)) {
            $_SESSION["USER"]["USERID"] = $user["userID"];
            $_SESSION["USER"]["EMAIL"] = $email;
            $_SESSION["USER"]["LOGIN_DATE"] = date("Y-m-d H:i:s");
            $_SESSION['IS_LOGGED'] = true;
            $_SESSION['USER']['USERTYPE'] = $user["userType"];
        } else {
            $errors[] = "*البريد الإلكتروني أو كلمة المرور غير صحيحة";
        }
    } else {
        $errors[] = "*لم يتم العثور على المستخدم";
    }
}

return $errors;
}

// دالة للحصول على نوع المستخدم
function getUserType($email)
{
include 'database.php';

$email = $db->real_escape_string($email);

$stmt = $db->prepare("SELECT userType FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 1) {
    $stmt->bind_result($userType);
    $stmt->fetch();
    return $userType;
} else {
    return null;
}
$stmt->close();
}
function getFullname($email) {
    include 'database.php';

    // الحصول على نوع المستخدم أولاً
    $email = $db->real_escape_string($email);
    $sql = "SELECT userType FROM users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userType = $row['userType'];

        // الآن، بناءً على نوع المستخدم، استعلام الجدول المناسب
        if ($userType == 'customer') {
            $sql = "SELECT fullname FROM customer WHERE userID = (SELECT userID FROM users WHERE email = ?)";
        } elseif ($userType == 'driver') {
            $sql = "SELECT fullname FROM driver WHERE userID = (SELECT userID FROM users WHERE email = ?)";
        } else {
            return null; // أو معالجة مناسبة إذا لم يكن userType معروفًا
        }

        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['fullname'];
        }
    }
    return null;
}
include "database.php";

// دالة لإضافة رحلة
function tripc($data, $email)
{
$errors = array(); // مصفوفة لتخزين الأخطاء
include 'database.php';

// استعلام لاسترداد userID من جدول users
$userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
$userIDResult = $db->query($userIDQuery);

if ($userIDResult && $userIDResult->num_rows > 0) {
    $row = $userIDResult->fetch_assoc();
    $userID = $row['userID'];

        // استعلام لاسترداد driverID من جدول driver
        $driverIDQuery = "SELECT driverID FROM driver WHERE userID = '$userID'";
        $driverIDResult = $db->query($driverIDQuery);

        if ($driverIDResult && $driverIDResult->num_rows > 0) {
            $row = $driverIDResult->fetch_assoc();
            $driverID = $row['driverID'];

            $startLocation = trim($data['startLocation']);
            $arrivalLocation = trim($data['arrivalLocation']);
            $startTime = $data['startTime'];
            $cost = trim($data['cost']);
            $capacity = trim($data['capacity']);

            // إدراج بيانات الرحلة إلى قاعدة البيانات
            $sql = "INSERT INTO `trip` (`driverID`, `startLocation`, `arrivalLocation`, `startTime`, `cost`, `capacity`) 
                    VALUES ('$driverID', '$startLocation', '$arrivalLocation', '$startTime', '$cost','$capacity')";

            // تنفيذ الاستعلام
            if ($db->query($sql)) {
                $last_id = $db->insert_id;
                header("location: driver.php");
                exit();
            } else {
                $errors[] = "حدث خطأ أثناء إضافة الرحلة: " . $db->error;
            }
        } else {
            $errors[] = "لم يتم العثور على driverID للمستخدم المحدد.";
        }
    } else {
        $errors[] = "لم يتم العثور على userID للبريد الإلكتروني المحدد.";
    }

    return $errors;
}

// دالة لإضافة حجز
function booking_c($data, $email)
{
    $errors = array(); // مصفوفة لتخزين الأخطاء
    include 'database.php';

    // استعلام لاسترداد userID من جدول users
    $userIDQuery = "SELECT userID FROM users WHERE email = '$email'";
    $userIDResult = $db->query($userIDQuery);

    if ($userIDResult && $userIDResult->num_rows > 0) {
        $row = $userIDResult->fetch_assoc();
        $userID = $row['userID'];

        // استعلام لاسترداد customerID من جدول customer
        $customerIDQuery = "SELECT customerID FROM customer WHERE userID = '$userID'";
        $customerIDResult = $db->query($customerIDQuery);

        if ($customerIDResult && $customerIDResult->num_rows > 0) {
            $row = $customerIDResult->fetch_assoc();
            $customerID = $row['customerID'];
            $tripID = $data['tripID'];
            $bookingTime = date("Y-m-d H:i:s");
            $bookingState = "progress";

            // إدراج بيانات الحجز إلى قاعدة البيانات
            $sql = "INSERT INTO booking (customerID, tripID, bookingTime, bookingState) 
                    VALUES ('$customerID', '$tripID', '$bookingTime', '$bookingState')";

            // تنفيذ الاستعلام
            if ($db->query($sql) === TRUE) {
                header("location: client.php");
                exit();
            } else {
                $errors[] = "حدث خطأ أثناء إضافة الحجز: " . $db->error;
            }
        } else {
            $errors[] = "لم يتم العثور على customerID للمستخدم المحدد.";
        }
    } else {
        $errors[] = "لم يتم العثور على userID للبريد الإلكتروني المحدد.";
    }

    return $errors;
}