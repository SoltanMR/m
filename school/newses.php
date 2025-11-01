<?php
include './session/init.php';
include "../helper/db.php";
include './login_state.php';
include '../persianNumber/persianNumber.php';
include './include/gregorian_to_jalali.php';
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اخبار</title>

    <!-- CSS ها -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/news_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include './include/nav_long.php'; ?>

    <div class="container">
        <!-- بخش اخبار -->
        <div class="announcement-section mb-3">
            <div class="mt-4 text-start">
                <h2 class="section-title wow animate__slideInRight" style="margin-top: 8rem;">اخبارها</h2>
            </div>
        </div>

        <?php
        $per_page = 12;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = max(0, ($page - 1) * $per_page);

        try {
            // تعداد کل اخبار
            $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM `news`");
            $total_stmt->execute();
            $total_news = (int)$total_stmt->fetchColumn();

            // دریافت اخبار صفحه جاری
            $stmt = $pdo->prepare("SELECT * FROM `news` ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<div class="row g-4">';

            if (!empty($news)) {
                $counter = 0;
                foreach ($news as $item) {
                    $counter++;
                    // تبدیل تاریخ
                    $jalali_date = '';
                    try {
                        if (!empty($item['created_at'])) {
                            $created_at = new DateTime($item['created_at']);
                            list($jyear, $jmonth, $jday) = gregorian_to_jalali(
                                (int)$created_at->format('Y'),
                                (int)$created_at->format('m'),
                                (int)$created_at->format('d')
                            );
                            $jalali_date = $jyear . '/' . $jmonth . '/' . $jday;
                        }
                    } catch (Exception $e) {
                        $jalali_date = 'تاریخ نامعتبر';
                    }

                    // خلاصه متن و عنوان
                    $content = $item['content'] ?? '';
                    $title = $item['title'] ?? '';
                    $short_content = mb_substr(strip_tags($content), 0, 75, 'UTF-8');
                    if (mb_strlen($content, 'UTF-8') > 75) $short_content .= '...';

                    $short_title = mb_substr($title, 0, 25, 'UTF-8');
                    if (mb_strlen($title, 'UTF-8') > 25) $short_title .= '...';

                    // تصویر
                    $image_html = '';
                    $image_file = $item['image'] ?? '';
                    if (!empty($image_file) && file_exists('../uploads/news/' . $image_file)) {
                        $image_path = '../uploads/news/' . $image_file;
                    } else {
                        $image_path = '../uploads/news/fore.jpg';
                    }

                    $image_html = '
                <div class="news-image ratio ratio-16x9">
                    <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($title) . '" 
                         class="img-fluid object-fit-cover"
                         onerror="this.src=\'./images/placeholder.png\'">
                </div>';

                    echo '
                <div class="col-xl-3 col-lg-4 col-md-6 wow">
                    <a href="news-detail.php?id=' . htmlspecialchars($item['id']) . '" class="text-decoration-none text-dark">
                        <div class="card homework h-100 shadow-sm" style="min-height: 280px;">
                            ' . $image_html . '
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font mb-3">' . htmlspecialchars(persianNumber($short_title)) . '</h5>
                                <p class="card-text font1 mb-4 flex-grow-1">' . htmlspecialchars(persianNumber($short_content)) . '</p>
                                <div class="text-start mt-auto">
                                    <small class="news-date text-white"><i class="fas fa-calendar me-2"></i> ' . persianNumber($jalali_date) . '</small>
                                    <div class="news-card-date">
                                        <svg class="footer__wavey-affect" viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                            <defs>
                                                <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%" gradientUnits="userSpaceOnUse">
                                                    <stop offset="0%" stop-color="var(--primary-blue)" />
                                                    <stop offset="100%" stop-color="var(--primary-purple)" />
                                                </linearGradient>
                                            </defs>
                                            <path d="M-0.00,50.06 C149.99,150.23 349.21,-50.06 500.00,50.06 L500.00,150.23 L-0.00,150.23 Z" fill="url(#waveGradient)"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>';
                }

                echo '</div>'; // پایان row

                // صفحه‌بندی
                $total_pages = ceil($total_news / $per_page);
                if ($total_pages > 1) {
                    echo '<nav class="mt-3 pages wow animate__slideInUp">
                        <ul class="flex-wrap pagination justify-content-center">';

                    if ($page > 1) {
                        echo '<li class="mt-3 page-item me-3 btn-gradient">
                            <a class="page-link" href="?page=' . ($page - 1) . '" aria-label="قبلی">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>';
                    }

                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="mt-3 page-item me-3 btn-gradient' . ($i == $page ? ' active-page' : '') . '">
                            <a class="page-link" href="?page=' . $i . '">' . persianNumber($i) . '</a>
                          </li>';
                    }

                    if ($page < $total_pages) {
                        echo '<li class="mt-3 page-item me-3 btn-gradient">
                            <a class="page-link" href="?page=' . ($page + 1) . '" aria-label="بعدی">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                          </li>';
                    }

                    echo '</ul></nav>';
                }
            } else {
                echo '<div class="col-12">
                    <div class="alert alert-info text-center my-5">هنوز خبری ثبت نشده است.</div>
                  </div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger text-center my-5">خطا در بارگذاری اخبار</div>';
        }
        ?>
    </div>
    </div>

    <?php include "./include/footer.php"; ?>

    <!-- جاوااسکریپت -->
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <script src="./js/my_js/main_script.js"></script>

</body>

</html>