<?php
include './session/init.php';
include '../helper/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["user_name"], $_POST["national_code"], $_POST["password"])) {

    $user_name = trim($_POST["user_name"]);
    $national_code = trim($_POST["national_code"]);
    $password = $_POST["password"];

    // محدودیت طول ورودی‌ها
    if (mb_strlen($user_name, 'UTF-8') > 200 || mb_strlen($password, 'UTF-8') > 200 || mb_strlen($national_code, 'UTF-8') > 200) {
        $_SESSION["passwordState"] = false;
        header("Location:forgat_password.php?who=teacher");
        exit;
    }

    // پاکسازی کد ملی: فقط ارقام نگه داشته می‌شود
    $national_code = preg_replace('/[^0-9]/', '', $national_code);

    try {
        // جستجوی کاربر
        $sql = $pdo->prepare("SELECT password FROM `honaramoz` WHERE username = ? AND codemile = ?");
        $sql->execute([$user_name, $national_code]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql_update = $pdo->prepare("UPDATE `honaramoz` SET `password` = ? WHERE username = ? AND codemile = ?");
            $sql_update->execute([$hashedPassword, $user_name, $national_code]);

            $_SESSION["passwordState"] = true;
        } else {
            $_SESSION["passwordState"] = false;
        }

    } catch (PDOException $e) {
        $_SESSION["passwordState"] = false;
    }

    header("Location:forgat_password.php?who=teacher");
    exit;

} else {
    header("Location:forgat_password.php?who=teacher");
    exit;
}
?>