<?php
$footer = $footer ?? false;

// Follow Us
try {
    $follow_us_stmt = $pdo->prepare("SELECT * FROM follow_us WHERE type = ?");
    $follow_us_stmt->execute([1]);
    $follow_us_data = $follow_us_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database error in follow_us section: ');
    $follow_us_data = []; // خالی بگذار تا بخش نمایش داده نشود
}
?>

<?php if (count($follow_us_data) > 0): ?>
    <div class="footer__fallow-us wow animate__slideInLeft">
        <div class="fallow-us d-flex flex-column-reverse justify-content-between">
            <button type="button" class="fallow-us__btn p-0">
                <i class="fas fa-headphones fa-2x"></i>
            </button>
            <div class="fallow-us__icon">
                <ul class="list-unstyled">
                    <?php foreach ($follow_us_data as $icon): ?>
                        <li class="py-1">
                            <a target="_blank" href="<?= htmlspecialchars($icon['href']) ?>" class="d-block text-center">
                                <img src="<?= htmlspecialchars($icon['icon_url']) ?>" alt="کانال">
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<footer class="footer mt-auto">
    <!-- Wavey Effect -->
    <div style="height:150px; overflow:hidden;">
        <svg class="footer__wavey-affect" viewBox="0 0 500 150" preserveAspectRatio="none" style="height:100%; width:100%;">
            <path d="M-0.00,50.06 C149.99,150.23 349.21,-50.06 500.00,50.06 L500.00,150.23 L-0.00,150.23 Z"></path>
        </svg>
    </div>
    <div class="footer__conternt">
        <div class="container">
            <div class="row mb-2">
                <!-- Contact Info -->
                <?php
                try {
                    $stmt = $pdo->query("SELECT adress, phone FROM about LIMIT 1");
                    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    error_log("Database error in contact section: " . $e->getMessage());
                    $contact = [
                        'adress' => 'اطلاعات در دسترس نیست',
                        'phone'  => '—'
                    ];
                }
                ?>
                <?php if (!$contact) {
                    echo '<div class="col-md-4 text-center">
                        <h5>ارتباط با ما</h5>
                        <ul class="list-unstyled footer__list wow animate__slideInRight">
                            <li><i"></i>هنوز اطلاعات ثبت نشده </li>
                        </ul>
                    </div>';
                } else { ?>
                    <div class="col-md-4 text-center">
                        <h5>ارتباط با ما</h5>
                        <ul class="list-unstyled footer__list wow animate__slideInRight">
                            <li><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($contact['adress']) ?></li>
                            <li><i class="fas fa-phone me-2 footer__list-border"></i><?= persianNumber(htmlspecialchars($contact['phone'])) ?></li>
                        </ul>
                    </div>
                <?php } ?>
                <!-- Useful Links -->
                <div class="col-md-4 text-center">
                    <h5>لینک های مفید</h5>
                    <ul class="list-unstyled footer__list wow animate__slideInUp">
                        <?php
                        $stmt = $pdo->query("SELECT `link_name_1`,`link-mofid-1`,`link_name_2`,`link-mofid-2`,`link_name_3`,`link-mofid-3` FROM about LIMIT 1");
                        $links = $stmt->fetch(PDO::FETCH_ASSOC);
                        if (count($links) > 0) {
                            for ($x = 1; $x <= 3; $x++) {
                                $linkHref = $links["link-mofid-$x"];
                                $linkName = $links["link_name_$x"];
                                if ($linkHref && $linkName) {
                                    echo '<li class="footer__list-border">
                                            <a href="' . htmlspecialchars($linkHref) . '" target="_blank">
                                                <i class="fas fa-angle-left me-2"></i>' . htmlspecialchars($linkName) . '
                                            </a>
                                          </li>';
                                          $linkIsPrinted = true;
                                }
                            }
                            if (!isset($linkIsPrinted)) {
                                echo '
                                    <li class="footer__list-border">هنوز لینیک نیست.</li>
                                ';
                            }
                        } else {
                            echo '
                                <li class="footer__list-border">هنوز لینیک نیست.</li>
                            ';
                        }
                        ?>
                    </ul>
                </div>

                <!-- Designers -->
                <div class="col-md-4 text-center">
                    <h5>دسترسی سریع</h5>
                    <div class="wow animate__slideInLeft">
                        <a href="<?php echo $footer ? "./school/programmers.php" : "programmers.php" ?>" class="d-block"><i class="fas fa-angle-left me-2"></i><span>طراحان این سایت</span></a>
                        <?php
                        $new = ['news', 'gallery_groups'];

                        $allowedNew = ['news', 'gallery_groups'];
                        foreach ($new as $single_new) {
                            $tableNew = in_array($single_new, $allowedNew) ? $single_new : 'gallery_groups';
                            // -------------- select -------------- //
                            $sql = $pdo->prepare("SELECT id FROM `$tableNew` ORDER BY id DESC LIMIT 1");
                            $sql->execute();
                            $result = $sql->fetch(PDO::FETCH_ASSOC);
                            
                            $sqlGallery = $pdo->prepare("SELECT url FROM `gallery_groups` ORDER BY id DESC");
                            $sqlGallery->execute();
                            $resultGaller = $sqlGallery->fetch(PDO::FETCH_ASSOC);

                            if ($result) {
                                if ($single_new == 'news') {
                                    $new_name = "خبر";
                                    $link_new = $footer
                                    ? "./school/news-detail.php?id=" . $result['id']
                                    : "news-detail.php?id=" . $result['id'];
                                } else {
                                    $new_name = "گالری تصاویر";
                                    $link_new = $footer
                                    ? "./school/single_gallery.php?item=" . $result['id']
                                    : "single_gallery.php?item=" . $result['id'];
                                }

                                $linkImageGallery = $footer ? "./uploads/images/" : "../uploads/images/";

                                if ($resultGaller && $new_name == "گالری تصاویر" 
                                && file_exists($linkImageGallery.$resultGaller['url'])) {
                                    echo '
                                            <a href="' . $link_new  . '" 
                                            class="d-block"><i class="fas fa-angle-left me-2"></i>جدیدترین ' . $new_name . '</a>
                                        ';
                                }
                                else if ($new_name == "خبر") {
                                    echo '
                                            <a href="' . $link_new  . '" 
                                            class="d-block"><i class="fas fa-angle-left me-2"></i>جدیدترین ' . $new_name . '</a>
                                        ';
                                }
                            }
                            else {
                                if (isset($noGalleryNews))
                                {
                                    echo '
                                        <span>هنوز خبری یا گالری ای وجود ندارد.</span>
                                    ';
                                    break;
                                }
                                $noGalleryNews = true;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="school__logo footer__logo text-center">
                    <img class="school__logo-img" src="<?php echo $footer ? './school/img/logo/logo.png' : './img/logo/logo.png'; ?>" alt="لوگوی سایت">
                </div>
            </div>

            <hr class="mb-2 mt-0 bg-light">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-1">تمامی حقوق برای <?php
                                                    try {

                                                        // دریافت اطلاعات تماس
                                                        $stmt = $pdo->prepare("SELECT title FROM `about`");
                                                        $stmt->execute();
                                                        $contact_info = $stmt->fetch(PDO::FETCH_ASSOC);

                                                        // توضیحات با قابلیت شکستن خط هر 100 کاراکتر
                                                        if (!empty($contact_info['title'])) {
                                                            $content = htmlspecialchars($contact_info['title']);
                                                            echo (persianNumber($content));
                                                        }
                                                    } catch (PDOException $e) {
                                                        // در صورت خطا، اطلاعات پیش‌فرض را نمایش بده
                                                        error_log('Database error in contact info: ' . $e->getMessage());
                                                        echo 'خطا در اتصال با پایگاه داده.';
                                                    }
                                                    ?> محفوظ است</p>
                </div>
            </div>
        </div>
    </div>
</footer>