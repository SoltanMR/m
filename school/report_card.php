<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';
include '../persianNumber/persianNumber.php';

if (!isset($_SESSION['state']) || $_SESSION['state'] !== "true") {
    header("Location:login.php");
    exit;
}

$honarjoyan_id = $_SESSION["userInfo"]["id"];


// ذخیره گزارش انتخاب‌شده
if (isset($_POST['selected_report'])) {
    $_SESSION['report'] = $_POST['selected_report'];
}
$report_name = $_SESSION['report'] ?? '';

// گرفتن نام رشته و پایه هنرجو از جدول honarjoyan
try {
    $stmt = $pdo->prepare("SELECT reshtaha, paye FROM honarjoyan WHERE id = ?");
    $stmt->execute([$honarjoyan_id]);
    $student = $stmt->fetch();

    $reshtaha_name = $student['reshtaha'] ?? '';
    $paye_name = $student['paye'] ?? '';
} catch (PDOException $e) {
    echo '<div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
            خطا در دریافت اطلاعات هنرجو از پایگاه داده.
          </div>';
    
}

// تبدیل نام رشته به شناسه عددی از جدول reshtaha
try {
    $stmt = $pdo->prepare("SELECT id FROM reshtaha WHERE name = ?");
    $stmt->execute([$reshtaha_name]);
    $reshtaha_id = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo '<div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
            خطا در تبدیل نام رشته به شناسه عددی.
          </div>';
    
}

// تبدیل نام پایه به شناسه عددی از جدول paye
try {
    $stmt = $pdo->prepare("SELECT id FROM paye WHERE paye = ? And reshtaha_id=?");
    $stmt->execute([$paye_name, $reshtaha_id]);
    $paye_id = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo '<div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
            خطا در تبدیل نام پایه به شناسه عددی.
          </div>';
    
}

// گرفتن گزارش‌هایی که دسترسی‌شون باز شده
try {
    $stmt = $pdo->prepare("SELECT report_name FROM report_access WHERE reshtaha_id = ? AND paye_id = ? AND access_open = 1");
    $stmt->execute([$reshtaha_id, $paye_id]);
    $accessible_reports = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo '<div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
            خطا در دریافت لیست گزارش‌های قابل دسترسی.
          </div>';
    
}



// بررسی اینکه report انتخاب‌شده معتبر است یا نه
if ($report_name && !in_array($report_name, $accessible_reports)) {
    $report_name = '';
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
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Page -->
    <div class="container-fluid px-0">
        <!-- Header/Navbar -->
        <?php include './include/nav_long.php'; ?>
        
<?php
$error = $_GET['msg'] ?? null;
?>

<?php if ($error): ?>
    <div class="error-container d-print-none">
  <div class="custom-error">
        <i class="fas fa-exclamation-triangle ms-2"></i>
        <?= htmlspecialchars($error) ?>
    </div>
</div>
    
<?php endif; ?>

        <div class="container mt-5 pt-5">

        <div class="announcement-section mb-3">
            <div class="text-center">
                <h3 class="section-title wow animate__slideInRight d-inline">کارنامه ها</h3>
            </div>
        </div>

            <?php if (!$accessible_reports): ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-circle ms-2"></i>
                    هیچ کارنامه ای برای شما فعال نشده است.
                </div>
            <?php else: ?>
                <?php
                
    
                    $customOrder = ["مستمر پاییز", "نوبت اول", "مستمر زمستان", "مستمر بهار", "نوبت دوم"];
                            
                    usort($accessible_reports, function($a, $b) use ($customOrder) {
                        return array_search($a, $customOrder) <=> array_search($b, $customOrder);
                    });

                    foreach ($accessible_reports as $report): 
                ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($report) ?></h5>
                            <form method="post" action="view_report_student.php">
                                <input type="hidden" name="selected_report" value="<?= htmlspecialchars($report) ?>">
                                <button type="submit" class="btn-gradient mx-auto py-2 px-3">
                                    نمایش کارنامه
                                    <i class="fas fa-clipboard-list ms-3"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    
                <?php endforeach; ?>
                <div class="back-button-container text-center my-4">
  <a href="user.php" class="back-button">
    <i class="fas fa-arrow-right ms-2"></i>
    بازگشت
  </a>
</div>

            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include "./include/footer.php" ?>

    <!-- Scripts -->
    <script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script src="./js/my_js/main_script.js"></script>
</body>
</html>