<?php
include './school/session/init.php';
include "./helper/db.php";
include './school/login_state.php';
include './persianNumber/persianNumber.php';

if (isset($_SESSION["state"]) && $_SESSION["state"] == "true") {
    $download = "./school/user.php";
} else {
    $download = "./school/login.php";
}

function getAnimationClass($counter, $total = 4) {
    switch ($counter % $total) {
        case 1: return 'animate__slideInRight';
        case 2:
        case 3: return 'animate__slideInUp';
        default: return 'animate__slideInLeft';
    }
}

$navbar = true;
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <base target="_self">
    <!-- Meta -->
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="./school/img/favicon/favicon.ico">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="وبسایت رسمی هنرستان فنی راه دانش">

    <title>هنرستان فنی راه دانش</title>

    <!-- Links -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="./bootstrap_rtl/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./font_awesome/css/all.min.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="./school/css/owl_carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="./school/css/owl_carousel/owl.theme.default.min.css">
    <!-- My CSS -->
    <link rel="stylesheet" href="./sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="./school/css/my_css/style.css">
    <link rel="stylesheet" href="./school/css/my_css/home_style.css">
    <link rel="stylesheet" href="./website_font/css/fonts.css">
</head>

<body>
    <!-- Page -->
    <div class="container-fluid px-0">
        <!-- Header/Navbar -->
        <?php
            if (isset($_COOKIE['remember_me'])) {
                setcookie('logedIn', "true", time() + 60 * 60 * 24 * 7, '/');
            }
        
            include './school/include/nav_long.php';
            include './school/include/header.php';
        ?>

        <!-- Main Content -->
        <main class="mt-4">
            <!-- Slider -->
            <?php
                try {
                    // دریافت اسلایدرها از دیتابیس
                    $stmt = $pdo->prepare("SELECT * FROM `slider` ORDER BY id DESC");
                    $stmt->execute();
                    $sliders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                    if (count($sliders) > 0) {
                        echo '
                        <section id="home" class="hero-slider-container">
                            <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                <!-- Indicators -->
                                <div class="carousel-indicators">';
                        foreach ($sliders as $index => $slider) {
                            echo '<button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="' . $index . '"' 
                                 . ($index === 0 ? ' class="active" aria-current="true"' : '') 
                                 . ' aria-label="Slide ' . ($index + 1) . '"></button>';
                        }
                        echo '</div>';
                    
                        // Slides
                        echo '<div class="carousel-inner">';
                        foreach ($sliders as $index => $slider) {
                            $imgUrl = htmlspecialchars($slider['url'], ENT_QUOTES, 'UTF-8');
                            echo '
                            <div class="carousel-item' . ($index === 0 ? ' active' : '') . '">
                                <picture>
                                    <source media="(max-width: 576px)" srcset="./uploads/sliders/' . $imgUrl . '">
                                    <source media="(max-width: 992px)" srcset="./uploads/sliders/' . $imgUrl . '">
                                    <img src="./uploads/sliders/' . $imgUrl . '" class="d-block w-100" alt="Slide" loading="lazy">
                                </picture>
                            </div>';
                        }
                        echo '</div>';
                    
                        // Controls
                        echo '
                        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">قبلی</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">بعدی</span>
                        </button>
                            </div>
                        </section>';
                    } else {
                        // پیام هشدار وقتی اسلایدری وجود ندارد
                        echo '
                        <div class="container">
                            <div class="gradient-bg text-center py-2 main__alert">
                                <p class="mb-0">هنوز اسلایدری وجود ندارد.</p>
                            </div>
                        </div>';
                    }
                } catch (PDOException $e) {
                    // پیام خطا
                    echo '
                    <div class="container">
                        <div class="alert alert-danger text-center my-2">خطایی در نمایش اسلایدر رخ داده است.</div>
                    </div>';
                }
            ?>

            <!-- Latest News -->
            <div id="news" class="container mt-5">
                <!-- بخش اطلاع رسانی ها -->
                <div class="announcement-section">
                    <div class="text-start">
                        <h2 class="section-title wow animate__slideInRight">اخبارها</h2>
                    </div>
                </div>

                <?php
                include './school/include/gregorian_to_jalali.php';

                try {
                    $stmt = $pdo->prepare("SELECT * FROM `news` ORDER BY created_at DESC LIMIT 6");
                    $stmt->execute();
                    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($news) > 0) {
                        echo '<div class="owl-carousel owl-theme">';
                        $counter = 0;
                    
                        foreach ($news as $item) {
                            $counter++;
                        
                            // تاریخ شمسی
                            $created_at = new DateTime($item['created_at']);
                            $year = (int)$created_at->format('Y');
                            $month = (int)$created_at->format('m');
                            $day = (int)$created_at->format('d');
                            list($jyear, $jmonth, $jday) = gregorian_to_jalali($year, $month, $day);
                            $jalali_date = sprintf('%04d/%02d/%02d', $jyear, $jmonth, $jday);
                        
                            // خلاصه محتوا
                            $short_content = mb_substr(strip_tags($item['content']), 0, 75, 'UTF-8');
                            if (mb_strlen($item['content'], 'UTF-8') > 75) {
                                $short_content .= '...';
                            }
                        
                            // خلاصه عنوان
                            $short_title = mb_substr($item['title'], 0, 25, 'UTF-8');
                            if (mb_strlen($item['title'], 'UTF-8') > 25) {
                                $short_title .= '...';
                            }
                        
                            // تصویر خبر
                            $image_html = '';
                            if (!empty($item['image'])) {
                                $image_path = file_exists('./uploads/news/' . $item['image'])
                                    ? './uploads/news/' . $item['image']
                                    : './uploads/news/fore.jpg';
                            
                                $image_html = '
                                    <div class="news-image ratio ratio-16x9 cursor-pointer">
                                        <img src="' . htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') . '" 
                                             alt="' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '" 
                                             class="img-fluid object-fit-cover"
                                             loading="lazy"
                                             onerror="this.style.display=\'none\'">
                                    </div>';
                            }
                            else if (file_exists('./uploads/news/fore.jpg'))
                            {
                                $image_path = './uploads/news/fore.jpg';
                            
                                $image_html = '
                                    <div class="news-image ratio ratio-16x9 cursor-pointer">
                                        <img src="' . htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8') . '" 
                                             alt="' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '" 
                                             class="img-fluid object-fit-cover"
                                             loading="lazy"
                                             onerror="this.style.display=\'none\'">
                                    </div>';
                            }
                        
                            echo '
                                <div class="item card-news">
                                    <a href="./school/news-detail.php?id=' . (int)$item['id'] . '" class="text-decoration-none text-dark">
                                        <div class="card homework h-100 shadow-sm" style="min-height: 280px;">
                                            ' . $image_html . '
                                            <div class="card-body news-card d-flex flex-column">
                        
                                                <h5 class="card-title font mb-3">' . htmlspecialchars(persianNumber($short_title)) . '</h5>
                                                <p class="card-text font1 mb-4 flex-grow-1">' . htmlspecialchars(persianNumber($short_content)) . '</p>
                                                <div class="text-start mt-auto">
                                                    <small class="news-date text-white">
                                                        <i class="fas fa-calendar me-2"></i>' . persianNumber($jalali_date) . '
                                                    </small>
                                                </div>
                        
                                                <div class="news-card-date">
                                                    <svg class="footer__wavey-affect" viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                                                        <defs>
                                                            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%" gradientUnits="userSpaceOnUse">
                                                                <stop offset="0%" stop-color="var(--primary-blue)" />
                                                                <stop offset="100%" stop-color="var(--primary-purple)" />
                                                            </linearGradient>
                                                        </defs>
                                                        <path d="M-0.00,50.06 C149.99,150.23 349.21,-50.06 500.00,50.06 L500.00,150.23 L-0.00,150.23 Z" 
                                                              fill="url(#waveGradient)"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            ';
                        }
                    
                        echo '
                            </div>
                            <div class="mt-5 wow animate__zoomIn text-center">
                                <a href="./school/newses.php" class="btn-gradient d-inline-flex align-items-center">
                                    اخبار بیشتر
                                    <i class="fas fa-arrow-left ms-3"></i>
                                </a>
                            </div>
                        ';
                    } else {
                        echo '
                        <div class="col-12">
                            <div class="gradient-bg text-center py-2 main__alert">
                                <p class="mb-0">هنوز خبری وجود ندارد.</p>
                            </div>
                        </div>';
                    }
                
                } catch (PDOException $e) {
                    echo '
                    <div class="container">
                        <div class="alert alert-danger text-center my-2">خطا در بارگذاری اخبار</div>
                    </div>';
                }
                ?>
            </div>
            

            <!-- Gallery -->
            <section id="gallery" class="container mt-5">
                <h2 class="section-title wow animate__slideInRight">گالری تصاویر</h2>

                <?php
                try {
                    $galleryImage = false;
                    $stmt = $pdo->prepare("SELECT * FROM `gallery_groups` ORDER BY id DESC LIMIT 4");
                    $stmt->execute();
                    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                    if (count($images) > 0) {
                        $rows = ceil(count($images) / 4);
                        $counter = 0;
                    
                        for ($i = 0; $i < $rows; $i++) {
                            echo '<div class="row mb-4">';
                        
                            for ($j = 0; $j < 4; $j++) {
                                $index = ($i * 4) + $j;
                                $counter++;
                                if (isset($images[$index]) && file_exists("./uploads/images/".$images[$index]['url'])) {
                                    $galleryImage = true;
                                    ?>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 wow <?php echo $animation_class; ?>">
                                        <div class="gallery-item mb-4 mb-md-4">
                                            <?php 
                                            $pictures_sql = $pdo->prepare("SELECT * FROM `gallery_images` WHERE group_id = ? ORDER BY id DESC LIMIT 2");
                                            $pictures_sql->bindValue(1, $images[$index]['id'], PDO::PARAM_INT);
                                            $pictures_sql->execute();
                                            $pictures = $pictures_sql->fetchAll(PDO::FETCH_ASSOC);
                                
                                            if (count($pictures) > 1) {
                                                echo '<div class="thumbnails-overlay">';
                                                foreach ($pictures as $pictur) {
                                                    if(file_exists("./uploads/gallery_images/".$pictur['image_url']))
                                                    {
                                                        echo '
                                                            <div class="thumbnail">
                                                                <img src="./uploads/gallery_images/' . htmlspecialchars($pictur['image_url'], ENT_QUOTES, 'UTF-8') . '" 
                                                                     alt="تصویر گالری" 
                                                                     onerror="this.style.display=\'none\'">
                                                            </div>';

                                                    }
                                                }
                                                echo '</div>';
                                            }
                                            ?>

                                            <a href="./school/single_gallery.php?item=<?php echo (int)$images[$index]['id']; ?>">
                                                <img src="./uploads/images/<?php echo htmlspecialchars($images[$index]['url'], ENT_QUOTES, 'UTF-8'); ?>" 
                                                     class="img-fluid rounded gallery-image" 
                                                     alt="<?php echo !empty($images[$index]['title']) ? htmlspecialchars($images[$index]['title'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                                     loading="lazy"
                                                     onerror="this.style.display='none'">
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        
                            echo '</div>';
                        }
                    
                        if (!$galleryImage) 
                        {
                            echo '
                                <div class="gradient-bg text-center py-2 main__alert">
                                    <p class="mb-0">هنوز گالری تصاویری وجود ندارد.</p>
                                </div>
                            ';
                        }
                        else
                        {
                            echo '
                                <div class="mt-1 mt-md-5 wow animate__zoomIn text-center">
                                    <a href="./school/gallery.php" class="btn-gradient d-inline-flex align-items-center">
                                        مشاهده همه گالری ها
                                        <i class="fas fa-arrow-left ms-3"></i>
                                    </a>
                                </div>
                            ';
                        }
                    } else {
                        echo '
                            <div class="gradient-bg text-center py-2 main__alert">
                                <p class="mb-0">هنوز گالری تصاویری وجود ندارد.</p>
                            </div>
                        ';
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger text-center">خطا در بارگذاری گالری تصاویر</div>';
                }
                ?>
            </section>
<section id="homework" class="container mt-5">
    <h2 class="section-title wow animate__slideInRight">تمرینات و نمونه سوالات من</h2>
    <?php
    $tasks = ['tamrinat', 'nmonasoal'];
    $taskNone = true;
    $allowedTasks = ['tamrinat', 'nmonasoal'];

    foreach ($tasks as $task) {
        $table = in_array($task, $allowedTasks) ? $task : 'tamrinat';

        try {
            $sql = $pdo->prepare("SELECT * FROM `$table` ORDER BY id DESC LIMIT 2");
            $sql->execute();
            $task_sql = $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger text-center">خطا در دریافت اطلاعات: </div>';
            continue;
        }

        if (count($task_sql) > 0) {
            if ($taskNone) {
                $taskNone = false;
                ?>
                <div class="table-responsive-md gradient-bg">
                    <table class="table table-hover mb-3 mb-sm-0">
                        <thead>
                            <tr class="text-center">
                                <th>نوع</th>
                                <th>رشته و پایه</th>
                                <th>درس</th>
                                <th>توضیحات</th>
                            </tr>
                        </thead>
                        <tbody class="text-black">
                <?php
            }

            foreach ($task_sql as $singleTask) {
                $taskDownload = "";
                $userInfo = $_SESSION["userInfo"] ?? null;
                $state = $_SESSION["state"] ?? null;

                if ($userInfo && isset($userInfo["reshtaha"], $userInfo["paye"])) {
                    if (
                        $singleTask['reshta'] == $userInfo["reshtaha"] &&
                        $singleTask['paye'] == $userInfo["paye"] &&
                        $download === "./school/user.php"
                    ) {
                        $taskDownload = "./school/download/download.php?id={$singleTask['id']}";
                    }
                }
                ?>
                <tr class="text-center wow animate__zoomIn">
                    <td>
                        <img src="./school/img/userPanel/<?php echo ($task == "tamrinat") ? "homework.png" : "exam.png"; ?>"
                             alt="<?php echo ($task == 'tamrinat') ? 'تمرین' : 'نمونه سوال'; ?>">
                        <span><?php echo ($task == 'tamrinat') ? 'تمرین' : 'نمونه سوال'; ?></span>
                    </td>
                    <td><?php echo htmlspecialchars(persianNumber($singleTask['paye'])) . ' ' . htmlspecialchars($singleTask['reshta']); ?></td>
                    <td><?php echo htmlspecialchars(persianNumber($singleTask['dars'])); ?></td>
                    <td class="py-3">
                        <?php if (!empty($taskDownload)) { ?>
                            <div class="div_btn-gradient">
                                <button class="btn-gradient btn-gradient-exams mx-auto openModalBtn"
                                        data-id="<?php echo $singleTask['id']; ?>"
                                        data-type="<?php echo ($task == "tamrinat") ? "homework" : "exam"; ?>">
                                    بیشتر
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="div_btn-gradient">
                                <button class="btn-gradient btn-gradient-exams mx-auto"
                                        onclick="<?php echo ($state !== "true") ? 'showLoginAlert()' : 'showAccessAlert()'; ?>">
                                    بیشتر
                                </button>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
        }
    }

    if (!$taskNone && $task == 'nmonasoal') {
        ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    if ($taskNone) {
        echo '
            <div class="gradient-bg text-center py-2 main__alert">
                <p class="mb-0">هنوز هیچ تکلیفی و نمونه سوالی وجود ندارد.</p>
            </div>
        ';
    } else {
        $state = $_SESSION["state"] ?? null;
        if ($state === "true") {
            echo '
                <div class="mt-5 wow animate__zoomIn text-center">
                    <a href="./school/user.php" class="text-center d-inline-block">
                        <button class="btn-gradient mx-auto">
                            تمرینات و نمونه سوالات من
                            <i class="fas fa-arrow-left ms-3"></i>
                        </button>
                    </a>
                </div>
            ';
        } else {
            echo '
                <div class="mt-5 wow animate__zoomIn text-center">
                    <button class="btn-gradient mx-auto" onclick="showLoginAlert()">
                        تمرینات و نمونه سوالات من
                        <i class="fas fa-arrow-left ms-3"></i>
                    </button>
                </div>
            ';
        }
    }
    ?>
</section>


            <!-- My Class -->
            <section id="class" class="statement mx-auto mt-5">
                <div class="content-box-lg">
                    <div class="container">
                        <div class="row">
                            <!-- مدرس -->
                            <div class="col-md-5">
                                <div class="tech-statement text-center mt-4 mb-3">
                                    <div class="tech-statement-text">
                                        <span class="counter">
                                            <?php
                                            try {
                                                   $stmt = $pdo->query("SELECT COUNT(*) FROM `honaramoz`");
                                                    echo persianNumber($stmt->fetchColumn());
                                                } catch (PDOException $e) {
                                                   echo '<div class="alert alert-danger text-center">خطا در دریافت تعداد هنراموزان </div>';
         
                                                }
                                                
                                            ?>
                                        </span>
                                        <span>مدرس</span><br>
                                        <i class="fa fa-chalkboard-teacher main__statment-icon-teacher"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-md-2">
                                <div class="tech-statement text-center mt-4 mb-3">
                                    <div class="tech-statement-text">
                                        <p>به همراه</p>
                                    </div>
                                </div>
                            </div>
                        
                    <!-- هنر جو -->
<div class="col-md-5">
    <div class="tech-statement text-center mt-4 mb-3">
        <div class="tech-statement-text">
            <span class="counter">
                <?php
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) FROM `honarjoyan`");
                    $count = $stmt->fetchColumn();
                    echo persianNumber($count);
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger text-center">خطا در دریافت تعداد هنرجویان</div>';
                }
                ?>
            </span>
            <span>هنر جو</span><br>
            <i class="fa fa-users main__statment-icon-student"></i>
        </div>
    </div>
</div>

<div class="main__statment col-12 my-3">
    <div class="main__statment-border"></div>
</div>

<div class="col-md-12">
    <div id="tech-statement" class="text-center mt-4 mb-3">
        <h3>رشته های موجود در هنرستان :</h3>
    </div>
</div>
</div>

<div class="row mx-auto">
<?php
try {
    $stmt = $pdo->query("
        SELECT r.name, COUNT(h.id) AS total_students
        FROM reshtaha r 
        LEFT JOIN honarjoyan h ON r.name = h.reshtaha 
        GROUP BY r.name 
        ORDER BY COUNT(h.id) DESC
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        $counter = 0;
        foreach ($results as $row) {
            $counter++;
            ?>
            <div class="col-md-4 wow">
                <div class="card main__class">
                    <div class="card-header text-center text-white">
                        <div class="icon-container">
                            <i class="fa card-icon fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="card-body text-center main__class-text">
                        <p class="card-title"><?= htmlspecialchars($row['name']) ?></p>
                        <p class="total-students"><?= persianNumber($row['total_students']) ?> هنرجو</p>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '
            <div class="gradient-bg text-center py-2 mb-2 main__alert">
                <p class="mb-0 text-white">هنوز رشته‌ای وجود ندارد.</p>
            </div>
        ';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center">خطا در دریافت اطلاعات رشته‌ها</div>';
}
?>
</div>
</div>
</div>
</section>

            <!-- About School -->
            <section id="about" class="mt-5 py-3 container">
                <h2 class="section-title wow animate__slideInRight">درباره مدرسه</h2>
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0 overflow-hidden">
                        <div class="responsive-map-container">
                            <iframe
                                src="https://balad.ir/embed?p=1igN1IROeEb9q9"
                                title="نمایش مکان هنرستان فنی رادانش مریوان روی نقشه بلد"
                                class="responsive-map"
                                frameborder="0"
                                style="border:0;"
                                allowfullscreen
                                aria-hidden="false"
                                tabindex="0">
                            </iframe>
                        </div>
                    </div>
                                
                    <div class="col-md-6 animate__slideInLeft wow">
                        <div>
                            <?php
                            try {
                                $stmt = $pdo->prepare("SELECT content FROM `about`");
                                $stmt->execute();
                                $contact_info = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                                if (!empty($contact_info['content'])) {
                                    echo '<div class="text-justify about-text">';
                                    echo nl2br(htmlspecialchars(persianNumber($contact_info['content']), ENT_QUOTES, 'UTF-8'));
                                    echo '</div>';
                                } else {
                                    echo '
                                    <div class="gradient-bg text-center py-2 mb-2 main__alert">
                                        <p class="mb-0">توضیحاتی برای این بخش ثبت نشده است.</p>
                                    </div>';
                                }
                            } catch (PDOException $e) {
                                echo '
                                <div class="container">
                                    <div class="alert alert-danger text-center my-2">خطایی در نمایش توضیحات رخ داده است.</div>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <?php
        $footer = true;
        
        include './school/include/footer.php';
        ?>
    </div>

    <div id="customModal" class="custom-modal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body customBody">
                <p class="text-center modal-content-text"></p>

                <p class="modal-content-text" id="modalText">
                </p>
            </div>
            <div class="modal-footer my-3">
                <div class="div_btn-gradient text-center w-100">
                    <button  class="btn-gradient mx-auto" type="button" data-bs-dismiss="modal">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Java Script And Jquery -->
    <!-- BootStrap -->
    <script src="./bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <!-- Jquery -->
    <script src="./jquery/jquery-3.6.0.min.js"></script>
    <!-- Owl Carousel -->
    <script src="./school/js/owl_carousel/owl.carousel.min.js"></script>
    <!-- My Java Script And Jquery Code -->
    <script src="./sweetalert2/sweetalert2.all.min.js"></script>
    <script src="./error_management/error_management_script.js"></script>
    <script src="./school/js/my_js/home_script.js"></script>
    <script src="./school/js/my_js/main_script.js"></script>
    <script src="./school/js/my_js/modal_user.js"></script>

    <?php
        if (((isset($_SESSION["stateHonaramoz"]) && $_SESSION["stateHonaramoz"] === "true")
        || (isset($_SESSION["state"]) && $_SESSION["state"] === "true"))) {
            
            if (!isset($_COOKIE['logedIn']) && isset($_COOKIE['remember_me'])) {
                echo("<script>errorFunction('success','ورود با موفقیت انجام شد.');</script>");
                $_SESSION['loginUsers'] = true;
            }
            else if (!isset($_SESSION["loginUsers"])){
                echo("<script>errorFunction('success','ورود با موفقیت انجام شد.');</script>");
                $_SESSION['loginUsers'] = true;
            }
        }
    ?>
</body>
</html>