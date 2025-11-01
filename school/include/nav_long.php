<?php
// جلوگیری از هشدار در صورت تعریف‌نشدن متغیر
$navbar = $navbar ?? false;
$websiteUser = null;

try {
    if (!empty($_SESSION["state"]) && $_SESSION["state"] === "true") {
        $userInfo = $_SESSION["userInfo"] ?? [];

        if (!empty($userInfo["profile"]) && $navbar) {
            $profileUsers = mb_substr($userInfo["profile"], 1, null, 'UTF-8');
        }
        else if (!empty($userInfo["profile"]) && !$navbar) {
            $profileUsers = $userInfo["profile"];
        }
        else if (empty($userInfo["profile"])) {
            $profileUsers = '';
        }

        $websiteUser = [
            'username' => $userInfo["username"] ?? 'کاربر',
            'profile' => $profileUsers,
            'link' => $navbar ? './school/user.php' : './user.php'
        ];
    } elseif (!empty($_SESSION["admin_logged_in"])) {

        if (!empty($_SESSION["adminProFile"]) && $navbar) {
            $adminProfile = mb_substr($_SESSION["adminProFile"], 1, null, 'UTF-8');
        }
        else if (!empty($_SESSION["adminProFile"]) && !$navbar) {
            $adminProfile = $_SESSION["adminProFile"];
        }
        else if (empty($_SESSION["adminProFile"])) {
            $adminProfile = '';
        }

        $websiteUser = [
            'username' => 'داشبورد',
            'profile' => $adminProfile,
            'link' => $navbar ? './admin/dashbord.php' : '../admin/dashbord.php'
        ];
    } elseif (!empty($_SESSION["stateHonaramoz"]) && $_SESSION["stateHonaramoz"] === "true") {
        $honaramozInfo = $_SESSION["honaramozInfo"] ?? [];
        
        if (!empty($honaramozInfo["profile"]) && $navbar) {
            $honaramozProfile = mb_substr($honaramozInfo["profile"], 1, null, 'UTF-8');
        }
        else if (!empty($honaramozInfo["profile"]) && !$navbar) {
            $honaramozProfile = $honaramozInfo["profile"];
        }
        else if (empty($honaramozInfo["profile"])) {
            $honaramozProfile = '';
        }
        
        $websiteUser = [
            'username' => $honaramozInfo["username"] ?? 'هنراموز',
            'profile' => $honaramozProfile,
            'link' => $navbar ? './honaramoz/dashbord.php?what=1' : '../honaramoz/dashbord.php?what=1'
        ];
    }
} catch (Exception $e) {
    // در صورت بروز خطا، کاربر ناشناس در نظر گرفته می‌شود
    $websiteUser = null;
    echo '
        <div class="container">
            <div class="alert alert-danger text-center my-2">خطا در شناسایی کاربر</div>
        </div>';
}

function getUserNavProfile($websiteUser) {
    $profilePath = $websiteUser['profile'] ?? '';
    $link = $websiteUser['link'] ?? '#';
    $username = htmlspecialchars($websiteUser['username'] ?? 'کاربر');

    // بررسی وجود عکس پروفایل
    if (!empty($profilePath) && file_exists($profilePath)) {
        $icon = '
            <div class="overflow-hidden">
                <img 
                src="' . htmlspecialchars($profilePath) . '"
                alt="عکس پروفایل کاربر"
                class="w-100 h-100 object-fit-cover rounded"
                >
            </div>
        ';
    } else {
        $icon = '<i class="fa fa-user"></i>';
    }

    return '
        <div class="nav__user">
            <a href="' . htmlspecialchars($link) . '" class="text-white d-flex align-items-center justify-content-center">
                ' . $icon . '
                <span class="ms-2">' . $username . '</span>
            </a>
        </div>
    ';
}
?>


<header style="font-size: 15px; ">
    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-expand-lg bg-white gradient-bg  fixed-top">
        <div class="container">
            <!-- Logo -->
            <div class="school__logo">
                <img class="school__logo-img" src="<?php echo $navbar ? "./school/img/logo/logo.png" : "./img/logo/logo.png"?>" alt="لوگوی سایت">
            </div>

            <!-- Navbar Icon For Small Device -->
            <button class="navbar-toggler mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex navbar__collapse-container align-items-center w-100">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- Navbar Link 1 -->
                        <li class="nav-item">
                            <a class="nav-link nav-item-affect text-white smooth-scroll" href="<?php echo ($navbar) ? '#home' : '../' ?>">
                                <i class="fas fa-home me-1"></i>
                                <span>خانه</span>
                            </a>
                        </li>
                        <!-- Navbar Link 2 -->
                        <li class="nav-item">
                            <a class="nav-link nav-item-affect text-white smooth-scroll" href="<?php echo ($navbar) ? '#news' : 'newses.php' ?>">
                                <i class="fas fa-newspaper me-1"></i>
                                <span>اخبار</span>
                            </a>
                        </li>

                        <!-- Navbar Link 3 -->
                        <li class="nav-item">
                            <a class="nav-link nav-item-affect text-white smooth-scroll" href="<?php echo ($navbar) ? '#gallery' : 'gallery.php' ?>">
                                <i class="fas fa-images me-1"></i>
                                <span>گالری</span>
                            </a>
                        </li>

                        <?php
                            if ($navbar) {
                                ?>
                                    <!-- Navbar Link 4 And Dropdown -->
                                    <li class="nav-item nav-dropdown nav-dropdown-md">
                                        <a href="#"
                                            class="nav-link text-white d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-school me-1"></i>
                                                <span>مدرسه من</span>
                                            </div>

                                            <div>
                                                <i class=" d-lg-none fas fa-chevron-down icon-dropdown-md"></i>
                                            </div>
                                        </a>

                                        <!-- Dropdown Link In Larg Device -->
                                        <ul class="nav-dropdown-items d-none d-lg-block">
                                            <!-- Dropdown Link 1 -->
                                            <li><a href="#homework" class="smooth-scroll"><i class="fas fa-pen-fancy me-1"></i>
                                                    تمرینات و نمونه سوالات</a></li>
                                            <!-- Dropdown Link 2 -->
                                            <li><a href="#class" class="smooth-scroll"><i class="fas fa-chalkboard me-1"></i>
                                                    کلاس ها</a></li>
                                        </ul>

                                        <!-- Dropdown Link In Small Device -->
                                        <ul class="nav-dropdown-items-md d-block d-lg-none">
                                            <!-- Dropdown Link 1 -->
                                            <li><a href="#homework" class="smooth-scroll"><i class="fas fa-pen-fancy me-1"></i>
                                                    تمرین ها و نمونه سوالات</a></li>
                                            <!-- Dropdown Link 2 -->
                                            <li><a href="#class" class="smooth-scroll"><i class="fas fa-chalkboard me-1"></i>
                                                    کلاس ها</a></li>
                                        </ul>
                                    </li>

                                    <!-- Navbar Link 5 -->
                                    <li class="nav-item">
                                        <a class="nav-link nav-item-affect last-nav-item text-white smooth-scroll"
                                            href="#about">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <span>درباره ما</span>
                                        </a>
                                    </li>
                                <?php
                            }
                        ?>
                    </ul>
                    <div class="d-flex align-items-center me-auto me-lg-0">
                        <!-- Button For Dark Mode Of Website -->
                        <input type="checkbox" class="input-darkmode-toggle" id="darkModeToggle">
                        <label for="darkModeToggle" class="lbl-darkmode-toggle me-2">
                            <i class="fas fa-sun sun-darkmode-toggle"></i>
                            <i class="fas fa-moon moon-darkmode-toggle"></i>
                        </label>

                        <!-- User Profile In Nave If Login Was True -->
                        <?php
                            if ($websiteUser) {
                                echo getUserNavProfile($websiteUser);
                            } else {
                                $loginUsers = $navbar ? "./school/login.php" : "./login.php";
                                // اگر کاربر لاگین نکرده
                                echo '<a class="btn-login" href="'. $loginUsers .'">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        <span>ورود</span>
                                      </a>';
                            }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </nav>
    <?php
        if (!$navbar) {
            echo("</header>");
        }
    ?>