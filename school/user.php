<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';
include '../persianNumber/persianNumber.php';

// بررسی ورود کاربر
if (empty($_SESSION['state']) || $_SESSION['state'] !== "true") {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['userInfo']['username'])) {
    die("لطفاً ابتدا وارد شوید.");
}

$user = $_SESSION['userInfo'];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">
    <title>پنل کاربری</title>

    <!-- CSS Links -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/user_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container-fluid px-0">
    <!-- Navbar -->
    <?php include './include/nav_long.php'; ?>

    <!-- Main -->
    <main class="container">
        <section class="p-4 main__user">
            <div class="row">
                <!-- Profile -->
                <div class="col-lg-6">
                    <div class="card p-2 main__profile-card">
                        <div class="profile__card-header gradient-bg p-2">
                            <div class="user__image-add">
                                <a href="./change_profile.php">
                                    <i class="fas fa-camera icon__image-add"></i>
                                    <i class="fas fa-plus icon__image-add"></i>
                                </a>
                            </div>
                            <div class="user__image text-center w-100 h-100">
                                <?php if (!empty($user['profile']) && file_exists($user['profile'])): ?>
                                    <img src="<?= htmlspecialchars($user['profile']) ?>" alt="عکس پروفایل" class="w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <i class="fa fa-user card__user-profile-icon mt-4"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">پروفایل من</h3>
                            <div class="card-text mt-5">
                                <div class="border-bottom d-flex align-items-center justify-content-between">
                                    <span class="profile__card-text">نام کاربری :</span>
                                    <span class="profile__card-text"><?= htmlspecialchars($user['username']) ?></span>
                                </div>

                                <div class="mt-5 mt-md-4 border-bottom d-flex align-items-center justify-content-between">
                                    <span class="profile__card-text">رشته تحصیلی :</span>
                                    <span class="profile__card-text"><?= htmlspecialchars($user['reshtaha']) ?></span>
                                </div>

                                <div class="mt-5 mt-md-4 border-bottom d-flex align-items-center justify-content-between">
                                    <span class="profile__card-text">پایه :</span>
                                    <span class="profile__card-text"><?= htmlspecialchars($user['paye']) ?></span>
                                </div>

                                <!-- Buttons -->
                                <div class="mt-0 mt-md-4 d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="mt-2 mx-auto d-flex justify-content-between align-items-center flex-wrap reponsive-btn profile__btn-gradient">
                                        <a href="report_card.php" class="mt-4 text-center mx-auto" title="مشاهده ی کارنامه">
                                            <button class="btn-gradient mx-auto btn-gradient-change">
                                                کارنامه
                                                <i class="fas fa-clipboard-list ms-3"></i>
                                            </button>
                                        </a>
                                    </div>

                                    <div class="mt-2 mx-auto d-flex justify-content-between align-items-center flex-wrap reponsive-btn reponsive-btn-mt profile__btn-gradient">
                                        <a href="change_password.php" class="mt-4 text-center mx-auto" title="تغییر رمز عبور">
                                            <button class="btn-gradient mx-auto btn-gradient-change">
                                                تغییر رمز
                                                <i class="fa fa-lock ms-3"></i>
                                            </button>
                                        </a>
                                    </div>

                                    <div class="mt-2 mx-auto d-flex justify-content-between align-items-center flex-wrap log_out-btn profile__btn-gradient">
                                        <a href="log_out.php" class="mt-0 mt-md-4 text-center mx-auto" title="خروج از حساب کاربری">
                                            <button class="btn-gradient mx-auto btn-gradient-change">
                                                خروج
                                                <i class="fas fa-sign-out-alt ms-3"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Homeworks & Exams -->
                <div class="col-lg-6">
                    <!-- Homeworks -->
                    <div class="homeworks">
                        <div class="card homeworks__card">
                            <div class="border-bottom d-flex align-items-center justify-content-start p-4">
                                <i class="fa fa-tasks me-3 icon"></i>
                                <h4>تکالیف من</h4>
                            </div>
                            <div class="card-body homework__card-body">
                                <!-- محتوا توسط AJAX بارگذاری می‌شود -->
                            </div>
                        </div>
                    </div>

                    <!-- Exams -->
                    <div class="exams">
                        <div class="card exams__card">
                            <div class="border-bottom d-flex align-items-center justify-content-start p-4">
                                <i class="fas fa-file-alt me-3 icon icon_exams"></i>
                                <h4>نمونه سوالات من</h4>
                            </div>
                            <div class="card-body exam__card-body">
                                <!-- محتوا توسط AJAX بارگذاری می‌شود -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Footer -->
<?php include "./include/footer.php"; ?>

<!-- Modal -->
<div id="customModal" class="custom-modal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title"></h3>
            <button class="close-btn modal__close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p class="text-center modal-content-text"></p>
            <p class="modal-content-text" id="modalText"></p>
        </div>
        <div class="modal-footer my-3">
            <div class="div_btn-gradient text-center w-100">
                <button class="btn-gradient mx-auto " type="button" data-bs-dismiss="modal"></button>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
<script src="../jquery/jquery-3.6.0.min.js"></script>
<script src="./js/my_js/main_script.js"></script>
<script src="./js/my_js/modal_user.js"></script>
<script src="./js/my_js/user_script.js"></script>
</body>
</html>