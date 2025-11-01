<?php
require_once '../helper/db.php';
require_once './session/init.php';
include '../englishNumber/englishNumber.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ورودی‌ها نباید خالی باشند
    if ($_POST['userName'] === '' || $_POST['password'] === '' ||
        mb_strlen($_POST['password'], 'UTF-8') > 200 || mb_strlen($_POST['userName'], 'UTF-8') > 200) {
        $_SESSION['state'] = 'false';
        header('Location: login.php');
        exit;
    }

    $cookies_to_clear = ['userName', 'password', 'remember_me', 'userNameHonaramoz', 'passwordHonaramoz', 'logedIn'];

    foreach ($cookies_to_clear as $cookie) {
        if(isset($_COOKIE[$cookie])) {
            setcookie($cookie, "", time() - 3600, "/", "", true, true);
        }
    }

    session_unset();
    
    $userName = trim(englishNumber($_POST['userName']));
    $password = trim(englishNumber($_POST['password']));

    // تلاش برای گرفتن کاربر از دیتابیس
    try {
        $stmt = $pdo->prepare("SELECT * FROM `honarjoyan` WHERE codemile = ?");
        $stmt->execute([$userName]);
        $adms = $stmt->fetchAll();
    } catch (PDOException $e) {
        // در صورت نیاز اینجا می‌توانید لاگ کنید
        $_SESSION['state'] = 'false';
        header('Location: login.php');
        exit;
    }

    foreach ($adms as $adm) {
        if ($adm && password_verify($password, $adm['password'])) {
            $adm['password'] = '';

            // ورود موفق
            $_SESSION['state']   = "true";
            $_SESSION['userInfo'] = $adm;

            // اگر گزینه "مرا به خاطر بسپار" زده شده بود، کوکی‌ها را تنظیم کن
            if (!empty($_POST['checkbox-1'])) {
                $expire = time() + 60 * 60 * 24 * 7; // 7 روز
                setcookie('remember_me', $_SESSION['state'], $expire, '/');
                setcookie('userName', $adm['username'], $expire, '/');
                setcookie('password', $adm['codemile'], $expire, '/');
            }

            header('Location: ../');
            exit;
        }
    }
    // ورود ناموفق
    $_SESSION['state'] = 'false';
    header('Location: login.php');
    exit;
}

// اگر درخواست POST نیست یا چیزی نادرست است، بازگشت به صفحه لاگین
$_SESSION['state'] = 'false';
header('Location: login.php');
exit;
