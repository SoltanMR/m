<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';
include '../persianNumber/persianNumber.php';

if (!isset($_SESSION['state']) || $_SESSION['state'] !== "true") {
    header("Location:../");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">
    <title>تغییر تصویر پروفایل</title>

    <!-- CSS -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/change_password_style.css">
    <link rel="stylesheet" href="./css/my_css/change_profile_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>
<body class="gradient-bg d-flex justify-content-center align-items-center">

<div class="container-fluid px-0">
    <main>
        <div class="container wow animate__slideInUp">
            <form action="./action_change_profile.php" method="post" enctype="multipart/form-data">
                <div class="mb-4 text-center">
                    <h2>افزودن عکس پروفایل جدید <i class="fas fa-image"></i></h2>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label">افزودن عکس :</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                </div>

                <div class="text-center mb-4 preview__image">
                    <div id="preview__image-box" class="d-flex flex-column">
                        <div class="d-flex justify-content-center align-items-center">
                            <span>پیش نمایش عکس</span>
                            <i class="fas fa-image ms-2"></i>
                        </div>
                        <div>
                            <p>فقط فایل‌های jpeg, png, jpg با حداکثر حجم <?= persianNumber(4) ?> مگابایت مجاز است.</p>
                        </div>
                    </div>
                    <div id="preview__image-container" class="d-none">
                        <img id="preview__image-img" src="#" alt="عکس پروفایل">
                    </div>
                </div>

                <div class="row div_submit_btn">
                    <div class="col-6">
                        <button type="button" id="submitBtn" class="btn-gradient w-50 mx-auto">ثبت</button>
                    </div>
                    <div class="col-6">
                        <a href="user.php" class="btn-gradient w-50 mx-auto">بازگشت</a>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- JS -->
<script src="../jquery/jquery-3.6.0.min.js"></script>
<script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
<script src="../sweetalert2/sweetalert2.all.min.js"></script>
<script src="./js/my_js/change_profile_script.js"></script>
<script src="../error_management/error_management_script.js"></script>

<?php
if (isset($_SESSION["profileState"])) {
    $number = persianNumber(4);
    $msgMap = [
        "error" => ["خطا در ذخیره تصویر.", "error"],
        "file_type_error" => ["فایل تصویر نبود.", "error"],
        "file_size_error" => ["حجم فایل بیش از " . $number ." مگابایت بود.", "error"],
        "success" => ["پروفایل شما با موفقیت عوض شد.", "success"]
    ];

    $state = $_SESSION["profileState"];
    if(isset($msgMap[$state])) {
        echo "<script>errorFunction(" . json_encode($msgMap[$state][1]) . "," . json_encode($msgMap[$state][0]) . ");</script>";
    }
    unset($_SESSION["profileState"]);
}
?>
</body>
</html>