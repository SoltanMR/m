<?php
include './session/init.php';
include '../helper/db.php';

// تابع هدایت با پیام
function redirectWithMessage($message, $state = false, $target = "change_password.php") {
    $_SESSION["passwordState"] = $state;
    $_SESSION["passwordMessage"] = $message;
    header("Location: $target");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["prives_password"]) && !empty($_POST["new_password"]) && !empty($_POST["repet_new_password"])) {
        
        $prives_password = trim($_POST["prives_password"]);
        $new_password    = trim($_POST["new_password"]);
        $repeat_password = trim($_POST["repet_new_password"]);

        // بررسی طول رمزها
        if (mb_strlen($new_password, 'UTF-8') > 200 || mb_strlen($repeat_password, 'UTF-8') > 200 || mb_strlen($prives_password, 'UTF-8') > 200) {
            redirectWithMessage("طول رمز عبور بیش از حد مجاز است.", false, "forgat_password.php?who=student");
        }

        // بررسی تکرار رمز
        if ($new_password !== $repeat_password) {
            redirectWithMessage("رمز جدید و تکرارش یکسان نیستند.");
        }

        // بررسی وجود شناسه کاربر
        if (empty($_SESSION["userInfo"]["id"])) {
            redirectWithMessage("کاربر معتبر نیست.");
        }

        $user_id = (int)$_SESSION["userInfo"]["id"];

        try {
            // گرفتن رمز فعلی از دیتابیس
            $sql = $pdo->prepare("SELECT password FROM honarjoyan WHERE id = ?");
            $sql->execute([$user_id]);
            $result = $sql->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($prives_password, $result['password'])) {
                // هش کردن رمز جدید
                $newHash = password_hash($new_password, PASSWORD_DEFAULT);

                // ذخیره رمز جدید
                $sql_update = $pdo->prepare("UPDATE honarjoyan SET password = ? WHERE id = ?");
                $sql_update->execute([$newHash, $user_id]);

                redirectWithMessage("رمز با موفقیت تغییر یافت.", true);
            } else {
                redirectWithMessage("رمز فعلی اشتباه است.");
            }

        } catch (PDOException $e) {
            redirectWithMessage("خطا در ارتباط با پایگاه داده.");
        }

    } else {
        redirectWithMessage("همه فیلدها الزامی هستند.");
    }
} else {
    redirectWithMessage("درخواست نامعتبر است.");
}
