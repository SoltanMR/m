<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';

if (!isset($_SESSION['state']) || $_SESSION['state'] !== "true") {
    header("Location:../");
    exit;
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <base target="_self">
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">

    <title>هنرستان فنی راه دانش</title>

    <!-- Links -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <!-- My CSS -->
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/change_password_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>

<body class="gradient-bg d-flex justify-content-center align-items-center">
    <!-- Page -->
    <div class="container-fluid px-0">

        <main>
            <div class="container wow animate__slideInUp">
                <form id="formPassword" action="./action_change_password.php" method="post">
                    <div class="mb-4">
                        <h2 class="text-center">
                            ویرایش رمز عبور
                            <i class="fa fa-lock"></i>
                        </h2>
                    </div>

                    <div class="mb-4">
                        <div class="password-input-container">
                            <label class="form-label" for="prives_password">رمز قبلی :</label>
                            <input class="form-control persian-number password-field" type="password" name="prives_password" id="prives_password" oninput="sanitizeStudentPassword(this)">
                        
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-eye-slash" style="display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="password-input-container">
                            <label class="form-label" for="new_password">رمز جدید :</label>
                            <input class="form-control persian-number password-field" type="password" name="new_password" id="new_password" oninput="sanitizeStudentPassword(this)">

                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-eye-slash" style="display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="password-input-container">
                            <label class="form-label" for="repet_new_password">تکرار رمز جدید :</label>
                            <input class="form-control persian-number password-field" type="password" name="repet_new_password" id="repet_new_password" oninput="sanitizeStudentPassword(this)">

                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-eye-slash" style="display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <a href="../school/forgat_password.php?who=teacher" class="text-center text-black mb-3 d-inline-block w-100" style="color:#764ba2;">رمز عبور را فراموش کرده‌اید؟</a>
                    </div>

                    <div class="row div_submit_btn">
                        <div class="col-6">
                            <button name="btnSubmit" id="submitBtn" class="btn-gradient mx-auto w-50" type="button">ثبت</button>
                        </div>
                            
                        <div class="col-6">
                            <a class="btn-gradient w-50 mx-auto " href="user.php">بازگشت</a>
                        </div>
                    </div>
                </form>
            </div>
        </main>

    </div>

    <!-- Java Script And Jquery -->
    <!-- BootStrap -->
    <script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <!-- Jquery -->
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <!-- Sweetalert2 -->
    <script src="../sweetalert2/sweetalert2.all.min.js"></script>
    <!-- My Java Script And Jquery Code -->
    <script src="../toggling_password/toggling_password.js"></script>
    <script src="./js/my_js/convertNumber.js"></script>
    <script src="./js/my_js/change_password_script.js"></script>
    <script src="../error_management/error_management_script.js"></script>

    <?php
        if (isset($_SESSION["passwordState"]) && $_SESSION["passwordState"] == true)
        {
    ?>
            <script> errorFunction("success", "رمز با موفقیت عوض شد."); </script>
    <?php
            unset($_SESSION["passwordState"]);
        }
        else if(isset($_SESSION["passwordState"]) && $_SESSION["passwordState"] == false)
        {
    ?>
            <script> errorFunction("error", "رمز اشتباه بود."); </script>
    <?php
            unset($_SESSION["passwordState"]);
        }
    ?>
</body>

</html>