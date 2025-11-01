<?php
try {
    if (empty($_SESSION['userInfo']) && isset($_COOKIE['remember_me']) && isset($_COOKIE['userName'])) {
        $sqlCookie = $pdo->prepare("SELECT id, namepdar, username, profile, reshtaha, paye, codemile FROM honarjoyan WHERE username = ? AND codemile = ?");
        $sqlCookie->bindValue(1, $_COOKIE['userName']);
        $sqlCookie->bindValue(2, $_COOKIE['password']); 
        $sqlCookie->execute();
        $resultCookie = $sqlCookie->fetch(PDO::FETCH_ASSOC);

        if ($resultCookie) {
            $_SESSION["state"] = $_COOKIE['remember_me'];
            $_SESSION["userInfo"] = $resultCookie;
            $_SESSION['loginUsers'] = true;
        }
    } elseif (empty($_SESSION['honaramozInfo']) && isset($_COOKIE['remember_me']) && isset($_COOKIE['userNameHonaramoz'])) {
        $sqlCookie = $pdo->prepare("SELECT id, username, profile, codemile FROM honaramoz WHERE username = ? AND codemile = ?");
        $sqlCookie->bindValue(1, $_COOKIE['userNameHonaramoz']);
        $sqlCookie->bindValue(2, $_COOKIE['passwordHonaramoz']); 
        $sqlCookie->execute();
        $resultCookie = $sqlCookie->fetch(PDO::FETCH_ASSOC);

        if ($resultCookie) {
            $_SESSION["stateHonaramoz"] = $_COOKIE['remember_me'];
            $_SESSION["honaramozInfo"] = $resultCookie;
            $_SESSION['loginUsers'] = true;
        }
    }
} catch (PDOException $e) {
    //در صورت نیاز  لاگ‌گیری  یا کاربر رو به صفحه خطا هدایت کن
     echo "خطا لطفا بعد دوباره تلاش کنید";
}
?>
