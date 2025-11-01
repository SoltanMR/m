<?php  
include './session/init.php';
include "../helper/db.php";
include './login_state.php';
include '../persianNumber/persianNumber.php';
include './include/gregorian_to_jalali.php';

$item = isset($_GET['item']) ? (int)$_GET['item'] : 0;
$gallery_images = [];
$gallery_title = [];

try {
    $sql_images = $pdo->prepare("SELECT * FROM gallery_images WHERE group_id = ?");
    $sql_images->bindValue(1, $item, PDO::PARAM_INT);
    $sql_images->execute();
    $gallery_images = $sql_images->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center mt-5  pt-4">خطا در بارگذاری تصاویر گالری</div>';
}

try {
    $sql_group = $pdo->prepare("SELECT * FROM gallery_groups WHERE id = ?");
    $sql_group->bindValue(1, $item, PDO::PARAM_INT);
    $sql_group->execute();
    $gallery_title = $sql_group->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center mt-4 pt-4">خطا در بارگذاری اطلاعات گالری</div>';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">
    <title>هنرستان فنی راه دانش</title>

    <!-- CSS -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/single_gallery_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>
<body class="d-flex flex-column min-vh-100">  
    <div class="container-fluid px-0 d-flex flex-column min-vh-100">
        <?php include './include/nav_long.php'; ?>
        <main class="container mt-5 pt-5">
        <?php if (!empty($gallery_images) && !empty($gallery_title)): ?>
            <?php
                list($date, $time) = explode(' ', $gallery_images[0]['created_at']);
                list($year, $month, $day) = explode('-', $date);
                list($jy, $jm, $jd) = gregorian_to_jalali($year, $month, $day);
                $jy = persianNumber($jy);
                $jm = persianNumber($jm);
                $jd = persianNumber($jd);
            ?>
            <div class="text-center mb-3 wow animate__fadeIn">
                <h2 class="section-title d-inline">گالری تصاویر</h2>
            </div>

            <div class="main__gallery-text d-flex justify-content-between align-items-center flex-wrap flex-column flex-md-row">
                <div class="gallery-text-title mx-auto mx-md-0">
                    <h3 class="mb-3 wow animate__fadeInRight text-justify"><?php echo htmlspecialchars(persianNumber($gallery_title['title'])); ?></h3>
                </div>
                <div class="gallery-text-date mx-auto mx-md-0 d-flex justify-content-between align-items-center flex-wrap ">
                    <h6 class="mb-3 wow animate__fadeInLeft mx-auto">
                        <?php echo $jy . '/' . $jm . '/' . $jd; ?><i class="fas fa-calendar ms-2"></i>
                    </h6>
                    <h6 class="mb-3 wow animate__fadeInLeft mx-auto ps-4">
                        <?php echo !empty($gallery_title['writer']) ? htmlspecialchars($gallery_title['writer']) : 'ادمین'; ?>
                        <i class="fa fa-pen ms-2"></i>
                    </h6>
                </div>
            </div>

            <article class="row mt-4 pt-2">
                <?php
                    $index = 0;
                    foreach ($gallery_images as $images_item) {
                        $index++;

                        $folder = ($index != 1) ? "../uploads/gallery_images/" : "../uploads/images/";
                        if (!empty($images_item['image_url']) && file_exists($folder . $images_item['image_url'])):
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-9 mx-auto mx-sm-auto">
                        <div class="gallery-item">
                            <img src="<?php echo $folder . htmlspecialchars($images_item['image_url']); ?>" 
                                 alt="عکس گالری"
                                 class="img-fluid rounded gallery-image">
                        </div>
                    </div>
                <?php
                        endif;
                    }
                ?>
            </article>
        <?php else: ?>
            <div class="gradient-bg text-center py-2 main__alert" role="alert">
                <p class="mb-0">خطا: چنین گالری‌ای وجود ندارد یا تصاویر آن قابل بارگذاری نیست.</p>
            </div>
        <?php endif; ?>

        <!-- Modal تصاویر -->
        <div class="image-modal" id="imageModal">
            <span id="close-span" class="close-btn">&times;</span>
            
            <div class="modal-content">
                <button id="prevBtn" class="btn btn-nav position-absolute start-0 top-50 translate-middle-y">
                    <i class="fas fa-chevron-right fa-2x"></i>
                </button>
                <img id="modalImage" src="" class="img-fluid mx-auto">
                <button id="nextBtn" class="btn btn-nav position-absolute end-0 top-50 translate-middle-y">
                    <i class="fas fa-chevron-left fa-2x"></i>
                </button>
            </div>
        </div>
        </main>

        <?php include "./include/footer.php"; ?>
    </div>

    <!-- JS -->
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <script src="./js/my_js/main_script.js"></script>
    <script src="./js/my_js/single_gallery_script.js"></script>
</body>
</html>
