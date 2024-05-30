<?php
// بدء الجلسة
session_start();
function logout()
{
    // إلغاء جميع متغيرات الجلسة
    session_unset();
    session_destroy();
    
    // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
    header("Location: login.php");
    
    exit();
}
logout();
?>
