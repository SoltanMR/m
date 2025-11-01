<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';

// ✅ تعیین مسیر اکشن با اعتبارسنجی
$action = null;
$goBack = null;
if (isset($_GET['who'])) {
    if ($_GET['who'] === "teacher") {
        $action = 'forgat_password_honaramoz.php';
    } elseif ($_GET['who'] === "student") {
        $action = 'forgat_password_student.php';
    } else {
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["state"]) && $_SESSION["state"] === "true") {
    $goBack = 'change_password.php';
}
else if (isset($_SESSION['stateHonaramoz']) && $_SESSION["stateHonaramoz"] === "true") {
    $goBack = '../honaramoz/change-password.php?what=4';
}
else {
    $goBack = 'login.php';
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">
    <title>هنرستان فنی راه دانش</title>

    <!-- CSS -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/change_password_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>

<body class="gradient-bg d-flex justify-content-center align-items-center">

    <div class="container-fluid px-0">
        <main>
            <div class="container wow animate__slideInUp">
                <form action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" method="post" id="forgetForm">
                    <div class="mb-4">
                        <h2 class="text-center">
                            فراموشی رمز عبور
                            <i class="ms-2 fa fa-lock"></i>
                        </h2>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="user_name">نام کاربری :</label>
                        <input class="form-control" type="text" name="user_name" id="user_name"  oninput="sanitizeStudentUsername(this)">
                    </div>

                    <div class="mb-4">
                        <div class="password-input-container">
                            <label class="form-label" for="national_code">کد ملی :</label>
                            <input class="form-control persian-number password-field" type="password" name="national_code" id="national_code" oninput="sanitizeStudentPassword(this)">
                            
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-eye-slash" style="display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="password-input-container">
                            <label class="form-label" for="password">رمز جدید :</label>
                            <input class="form-control persian-number password-field" type="password" name="password" id="password"  oninput="sanitizeStudentPassword(this)">
                            
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-eye-slash" style="display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row div_submit_btn">
                        <div class="col-6">
                            <button id="submitBtn" class="btn-gradient mx-auto w-50" type="button">ثبت</button>
                        </div>
                        <div class="col-6">
                            <a class="btn-gradient w-50 mx-auto " href="<?php echo(htmlspecialchars($goBack)); ?>">بازگشت</a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- JS -->
    <script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script src="../sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../toggling_password/toggling_password.js"></script>
    <script src="../error_management/error_management_script.js"></script>
    <script src="./js/my_js/convertNumber.js"></script>
    <script src="./js/my_js/forgar_password_script.js"></script>

    <?php if (isset($_SESSION["passwordState"])): ?>
        <?php if ($_SESSION["passwordState"] === true): ?>
            <script> errorFunction("success", "رمز با موفقیت تغییر یافت."); </script>
        <?php else: ?>
            <script> errorFunction("error", "کد ملی یا نام کاربری اشتباه است"); </script>
        <?php endif; unset($_SESSION["passwordState"]); ?>
    <?php endif; ?>
</body>
</html>