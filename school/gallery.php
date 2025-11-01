<?php
include './session/init.php';
include "../helper/db.php";
include './login_state.php';
include '../persianNumber/persianNumber.php';
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>گالری تصاویر</title>

<!-- CSS ها -->
<link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
<link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
<link rel="stylesheet" href="../font_awesome/css/all.min.css">
<link rel="stylesheet" href="./css/my_css/style.css">
<link rel="stylesheet" href="./css/my_css/gallery_style.css">
<link rel="stylesheet" href="../website_font/css/fonts.css">
</head>
<body class="d-flex flex-column min-vh-100">

<?php include './include/nav_long.php'; ?>

<section id="gallery" class="container mt-5 pt-5 mb-3">
    <h2 class="section-title wow animate__fadeInRight">گالری تصاویر</h2>

<?php
$per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

try {
    // دریافت گروه‌ها
    $stmt = $pdo->prepare("SELECT * FROM gallery_groups ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center">خطا در بارگذاری گروه‌های گالری.</div>';
    $groups = [];
}

if (!empty($groups)) {
    echo '<div class="row">';
    $counter = 0;

    foreach ($groups as $group) {
        if (empty($group['url']) || !file_exists("../uploads/images/" . $group['url'])) continue;

        // گرفتن تصاویر گروه
        try {
            $images_stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE group_id = ? ORDER BY id DESC LIMIT 2");
            $images_stmt->execute([(int)$group['id']]);
            $images = $images_stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $images = [];
        }
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 mb-sm-0">
            <div class="gallery-item">
                <?php if (!empty($images)): ?>
                    <div class="thumbnails-overlay">
                        <?php foreach ($images as $img):
                            if (!empty($img['image_url']) && file_exists("../uploads/gallery_images/" . $img['image_url'])): ?>
                                <div class="thumbnail">
                                    <img src="../uploads/gallery_images/<?php echo htmlspecialchars($img['image_url']); ?>" alt="تصویر گالری">
                                </div>
                        <?php endif; endforeach; ?>
                    </div>
                <?php endif; ?>

                <a href="single_gallery.php?item=<?php echo (int)$group['id']; ?>">
                    <img src="../uploads/images/<?php echo htmlspecialchars($group['url']); ?>" 
                         onerror="this.src='./images/placeholder.png'" 
                         class="img-fluid rounded gallery-image"
                         alt="<?php echo htmlspecialchars($group['title'] ?? ''); ?>">
                </a>
            </div>
        </div>
        <?php
    }

    echo '</div>';

    // صفحه‌بندی
    try {
        $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM gallery_groups");
        $total_stmt->execute();
        $total_images = (int)$total_stmt->fetchColumn();
        $total_pages = ceil($total_images / $per_page);
    } catch (PDOException $e) {
        $total_pages = 0;
        echo '<div class="alert alert-warning text-center mt-4">خطا در بارگذاری صفحه‌بندی</div>';
    }

    if ($total_pages > 1) {
        echo '<nav class="mt-3 pages wow animate__slideInUp shadow-none"><ul class="pagination justify-content-center flex-wrap">';
        if ($page > 1) echo '<li class="mt-3 page-item me-3 btn-gradient"><a class="page-link" href="?page=' . ($page - 1) . '">&laquo;</a></li>';
        for ($i = 1; $i <= $total_pages; $i++) echo '<li class="mt-3 page-item me-3 btn-gradient' . ($i == $page ? ' active-page' : '') . '"><a class="page-link" href="?page=' . $i . '">' . persianNumber($i) . '</a></li>';
        if ($page < $total_pages) echo '<li class="mt-3 page-item me-3 btn-gradient"><a class="page-link" href="?page=' . ($page + 1) . '">&raquo;</a></li>';
        echo '</ul></nav>';
    }

} else {
    echo '<div class="alert alert-info text-center">هنوز تصویری در گالری وجود ندارد</div>';
}
?>
</section>

<?php include "./include/footer.php"; ?>

<!-- JS -->
<script src="../jquery/jquery-3.6.0.min.js"></script>
<script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
<script src="./js/my_js/main_script.js"></script>

</body>
</html>
