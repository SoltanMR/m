<?php
require_once '../helper/db.php';
require_once './session/init.php';
include '../englishNumber/englishNumber.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // بررسی ورودی‌ها
    if ($_POST['userNameHonaramoz'] === '' || $_POST['passwordHonaramoz'] === '' ||
        mb_strlen($_POST['userNameHonaramoz'], 'UTF-8') > 200 || mb_strlen($_POST['passwordHonaramoz'], 'UTF-8') > 200) {
        $_SESSION["stateHonaramoz"] = "false";
        header("Location: login.php");
        exit;
    }

    $cookies_to_clear = ['userName', 'password', 'remember_me', 'userNameHonaramoz', 'passwordHonaramoz', 'logedIn'];

    foreach ($cookies_to_clear as $cookie) {
        if(isset($_COOKIE[$cookie])) {
            setcookie($cookie, "", time() - 3600, "/", "", true, true);
        }
    }
    
    session_unset();
    
    $userNameHonaramoz = trim(englishNumber($_POST['userNameHonaramoz']));
    $passwordHonaramoz = trim(englishNumber($_POST['passwordHonaramoz']));

    // جستجو در دیتابیس
    try {
        $stmt = $pdo->prepare("SELECT * FROM `honaramoz` WHERE codemile = ?");
        $stmt->execute([$userNameHonaramoz]);
        $adms = $stmt->fetchAll();
    } catch (PDOException $e) {
        $_SESSION["stateHonaramoz"] = "false";
        header("Location: login.php");
        exit;
    }
    foreach ($adms as $adm) {
        if ($adm && password_verify($passwordHonaramoz, $adm['password'])) {
            $adm['password'] = '';
        // ورود موفق
        session_regenerate_id(true);
        $_SESSION["stateHonaramoz"] = "true";
        $_SESSION["honaramozInfo"] = $adm;

        // مرا به خاطر بسپار
        if (!empty($_POST['checkbox-2'])) {
            $expire = time() + 60 * 60 * 24 * 7; // 7 روز
            setcookie("remember_me", $_SESSION["stateHonaramoz"], $expire, "/");
            setcookie("userNameHonaramoz", $adm['username'], $expire, "/");
            setcookie("passwordHonaramoz", $adm["codemile"], $expire, "/");
        }

        header("Location: ../");
        exit;
        }
    }
        // ورود ناموفق
        $_SESSION["stateHonaramoz"] = "false";
        header("Location: login.php");
        exit;
}

// اگر به‌طور مستقیم به این فایل رسید، بازگشت به login
header("Location: login.php");
exit;
