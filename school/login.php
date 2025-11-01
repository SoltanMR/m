<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به حساب کاربری</title>

    <!-- CSS -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="./css/my_css/login_user_style.css">
    <link rel="stylesheet" href="../sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/login_user_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>

<body class="body d-flex justify-content-center align-items-center">

<?php
// ریدایرکت اگر کاربر قبلاً وارد شده باشد
if ((isset($_SESSION["state"]) && $_SESSION["state"] == "true") 
    || (isset($_SESSION["stateHonaramoz"]) && $_SESSION["stateHonaramoz"] == "true")
    || (isset($_SESSION["admin_logged_in"]) && $_SESSION["admin_logged_in"])) {
    echo '<script>location.replace("../.php");</script>';
}
?>

<div class="container" dir="ltr">
    <section class="parent">
        <!-- ورود هنرجو -->
        <div class="chiled1">
            <div class="wrapear">
                <h1 class="h11 text-black">ورود هنرجو</h1>
                <form class="form" action="action_login.php" method="POST" id="formLoginStudent" dir="rtl">
                    <div class="input-container">
                        <input class="input1 persian-number" pattern="[۰-۹]+" type="text" name="userName" id="userName" placeholder="کد ملی" oninput="sanitizeStudentUsername(this); removeSpaces(this);">
                    </div>
                    <div class="input-container password-input-container">
                        <input class="input2 persian-number password-field" type="password" name="password" id="password" placeholder="رمز عبور" oninput="sanitizeStudentPassword(this)">
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                            <i class="fas fa-eye-slash" style="display: none;"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-start justify-content-center w-100 mt-3">
                        <div class="chk-remember-me me-3" dir="ltr">
                            <div class="round">
                                <input type="checkbox" id="checkbox-1" name="checkbox-1" class="ckh" />
                                <label for="checkbox-1"></label>
                            </div>
                        </div>
                        <label for="checkbox-1" class="text-black lbl">مرا به خاطر بسپار</label>
                    </div>

                    <div class="text-center w-100 mt-2" dir="rtl">
                        <a href="./forgat_password.php?who=student" class="text-black">رمز خود را فراموش کرده اید؟</a>
                    </div>

                    <button class="submit" type="submit" name="submit" id="submitStudent">ثبت</button>
                    <div class="wrap mt-3 div_btn-gradient goBack">
                        <a href="#" class="btn-gradient back text-center text-decoration-none d-none w-100">بازگشت</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ورود معلم -->
        <div class="chiled3">
            <div class="wrapear">
                <h1 class="h11 text-black">ورود هنرآموز</h1>
                <form class="form" action="action_login_honaramoz.php" method="POST" id="formLoginTeacher" dir="rtl">
                    <div class="input-container">
                        <input class="input1 persian-number" pattern="[۰-۹]+" type="text" name="userNameHonaramoz" id="userNameHonaramoz" placeholder="کد ملی" oninput="sanitizeStudentUsername(this); removeSpaces(this);">
                    </div>
                    <div class="input-container password-input-container">
                        <input class="input2 persian-number password-field" type="password" name="passwordHonaramoz" id="passwordHonaramoz" placeholder="رمز عبور" oninput="sanitizeStudentPassword(this)">
                        <button type="button" class="password-toggle">
                            <i class="fas fa-eye"></i>
                            <i class="fas fa-eye-slash" style="display: none;"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-start justify-content-center w-100 mt-3">
                        <div class="chk-remember-me me-3" dir="ltr">
                            <div class="round">
                                <input type="checkbox" id="checkbox-2" name="checkbox-2" class="ckh" />
                                <label for="checkbox-2"></label>
                            </div>
                        </div>
                        <label for="checkbox-2" class="text-black lbl">مرا به خاطر بسپار</label>
                    </div>

                    <div class="text-center w-100 mt-2" dir="rtl">
                        <a href="./forgat_password.php?who=teacher" class="text-black">رمز خود را فراموش کرده اید؟</a>
                    </div>

                    <button class="submit" type="submit" name="submitHonaramoz" id="submitTeacher">ثبت</button>
                    <div class="wrap mt-3 div_btn-gradient goBack">
                        <a href="#" class="btn-gradient back text-center text-decoration-none d-none w-100">بازگشت</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- قسمت وسط -->
        <div class="chiled2">
            <div class="wrapear2">
                <h1 class="h12">راه دانش</h1>
                <p class="paragraf2">برای ورود به حساب کاربری روی قسمت ورود کلیک کنید</p>
                <div class="w-100 div_btn-gradient">
                    <button class="close close__teacher btn-gradient d-flex align-items-center justify-content-center mx-auto">
                        <i class="fa fa-chalkboard-teacher ms-2"></i>
                        <span>ورود هنرآموز</span>
                    </button>

                    <button class="close close__student btn-gradient mx-auto d-flex align-items-center justify-content-center">
                        <i class="fa fa-users ms-2"></i>
                        <span>ورود هنرجو</span>
                    </button>
                </div>
                <div class="wrap mt-3 div_btn-gradient">
                    <a href="#" class="btn-gradient back text-center text-decoration-none">بازگشت</a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JS -->
<script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
<script src="../jquery/jquery-3.6.0.min.js"></script>
<script src="../sweetalert2/sweetalert2.all.min.js"></script>
<script src="../toggling_password/toggling_password.js"></script>
<script src="../error_management/error_management_script.js"></script>
<script src="./js/my_js/convertNumber.js"></script>
<script src="./js/my_js/login_script.js"></script>

<?php
// نمایش خطای ورود
if((isset($_SESSION["state"]) && $_SESSION["state"] == "false") 
|| (isset($_SESSION["stateHonaramoz"]) && $_SESSION["stateHonaramoz"] == "false")) {
    echo '<script>errorFunction("error", "رمز عبور یا کدملی اشتباه است.");</script>';
    $_SESSION["state"] = $_SESSION["stateHonaramoz"] = "";
}
?>
</body>
</html>