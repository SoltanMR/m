<?php
include './session/init.php';
include '../helper/db.php';

if (!empty($_POST["user_name"]) && !empty($_POST["national_code"]) && !empty($_POST["password"])) {

    if (mb_strlen($_POST['user_name'], 'UTF-8') > 200 || mb_strlen($_POST['password'], 'UTF-8') > 200 || mb_strlen($_POST['national_code'], 'UTF-8') > 200) {
        $_SESSION["passwordState"] = false;
        header("Location:forgat_password.php?who=student");
        exit;
    }

    $user_name = trim($_POST["user_name"]);
    $national_code = preg_replace('/[^0-9]/', '', $_POST["national_code"]);
    $password = trim($_POST["password"]);

    try {
        // بررسی وجود کاربر
        $sql = $pdo->prepare("SELECT password FROM `honarjoyan` WHERE username = ? AND codemile = ?");
        $sql->execute([$user_name, $national_code]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // هش کردن رمز جدید
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // بروزرسانی رمز عبور
            $sql_update = $pdo->prepare("UPDATE `honarjoyan` SET `password` = ? WHERE username = ? AND codemile = ?");
            $sql_update->execute([$hashedPassword, $user_name, $national_code]);

            $_SESSION["passwordState"] = true;
        } else {
            $_SESSION["passwordState"] = false;
        }

    } catch (PDOException $e) {
        $_SESSION["passwordState"] = false;
    }

    header("Location:forgat_password.php?who=student");
    exit;

} else {
    header("Location:forgat_password.php?who=student");
    exit;
}
?>