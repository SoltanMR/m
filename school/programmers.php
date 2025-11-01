<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';
include '../persianNumber/persianNumber.php';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">

    <title>هنرستان فنی راه دانش</title>

    <!-- Links -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/programmers_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>

<body>

<div class="container-fluid px-0 d-flex flex-column min-vh-100">
    <?php include './include/nav_long.php'; ?>

    <main class="mt-5 pt-5 container">
        <?php
        $sql = $pdo->prepare("SELECT * FROM `programmers`");
        $sql->execute();
        $allProgrammers = $sql->fetchAll(PDO::FETCH_ASSOC);

        if(count($allProgrammers) > 0):
        ?>
        <div class="wow animate__slideInRight text-center mb-4">
            <h2 class="section-title d-inline-block">طراحان این سایت</h2>
        </div>

        <?php
        $counter = 0;
        foreach ($allProgrammers as $programmer):
            $counter++;
            $animation = ($counter % 2 == 0) ? "animate__slideInRight" : "animate__slideInLeft";
        ?>
        <div class="card wow <?= htmlspecialchars($animation); ?> mb-4">
            <div class="row g-0">
                <div class="col-12 col-md-5 text-center profile-container">
                    <?php
                    $profilePath = $programmer['profile'] ;
                    if (empty($profilePath) || !file_exists($profilePath)):
                    ?>
                        <i class="fa fa-user card__user-profile-icon mt-4 mb-2"></i>
                    <?php else: ?>
                        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgZmlsbD0ibm9uZSI+PGNpcmNsZSBjeD0iNTAiIGN5PSI1MCIgcj0iNTAiIGZpbGw9IiM0NDQiLz48Y2lyY2xlIGN4PSI1MCIgY3k9IjQwIiByPSIyMCIgZmlsbD0iI2NjYyIvPjxwYXRoIGQ9Ik0zMCA4MEM3MCA4MCA2NSA2NSA1MCA2NVMzMCA4MCAzMCA4MFoiIGZpbGw9IiNjY2MiLz48L3N2Zz4="
                             alt="<?= htmlspecialchars($programmer['full_name']); ?>" 
                             class="profile-img">
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-7">
                    <div class="content-container d-flex flex-column justify-content-between p-3">
                        <h2 class="person-name"><?= htmlspecialchars($programmer['full_name']); ?></h2>
                        <div class="contact-info mb-3">
                        </div>
                        <?php
                        $sqlMedia = $pdo->prepare("SELECT * FROM `programmers_media` WHERE programmers_id = ?");
                        $sqlMedia->execute([$programmer['id']]);
                        $programmersMedia = $sqlMedia->fetchAll(PDO::FETCH_ASSOC);

                        if(count($programmersMedia) > 0):
                        ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; else: ?>
            <div class="gradient-bg text-center py-2 main__alert" role="alert">
                <p class="mb-0">هنوز هیچ اطلاعاتی وجود ندارد.</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php include "./include/footer.php" ?>

<script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
<script src="../jquery/jquery-3.6.0.min.js"></script>
<script src="./js/my_js/main_script.js"></script>

</body>
</html>