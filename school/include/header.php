    <!-- Header Contetnt -->

    <div class="header__content overflow-hidden" id="home">
        <div class="row mow">
            <div class="col-12 col-md-8 col-xxl-7 d-flex align-items-center">
                <div class="header__content-bg"></div>
                <div class="wavey-affect d-none d-md-block">
                    <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                        <path d="M292.03,-49.83 C-118.79,95.22 432.56,65.62 125.56,166.27 L500.00,150.00 L500.00,0.00 Z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="col-12 header__content-section mt-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6">
                            <div class="header__content-text">
                                <h1 class="header__animation-title">
                                    <?php
                                    try {

                                        // دریافت اطلاعات تماس
                                        $stmt = $pdo->prepare("SELECT title FROM `about`");
                                        $stmt->execute();
                                        $contact_info = $stmt->fetch(PDO::FETCH_ASSOC);

                                        // توضیحات با قابلیت شکستن خط هر 100 کاراکتر
                                        if (!empty($contact_info['title'])) {
                                            $content = htmlspecialchars($contact_info['title']);
                                            $words = explode(' ', $content);
                                            $lines = [];
                                            $current_line = '';
                                            $max_chars = 85; // حداکثر کاراکتر در هر خط

                                            foreach ($words as $word) {
                                                if (mb_strlen($current_line . ' ' . $word, 'UTF-8') <= $max_chars) {
                                                    $current_line .= ($current_line ? ' ' : '') . $word;
                                                } else {
                                                    if ($current_line) {
                                                        $lines[] = $current_line;
                                                    }
                                                    $current_line = $word;
                                                }
                                            }

                                            if ($current_line) {
                                                $lines[] = $current_line;
                                            }

                                            echo implode('<br>', persianNumber($lines));
                                        } else {
                                            echo ('هنوز مقداری وجود ندارد');
                                        }
                                    } catch (PDOException $e) {
                                        // در صورت خطا، اطلاعات پیش‌فرض را نمایش بده
                                        error_log('Database error in contact info: ' . $e->getMessage());
                                        echo 'خطا در اتصال با پایگاه داده.';
                                    }
                                    ?>
                                </h1>
                                <div class="mt-4 header__animation-text">
                                    <p class="mb-0 text-justify text-justify1">
                                        <?php
                                        try {

                                            // دریافت اطلاعات تماس
                                            $stmt = $pdo->prepare("SELECT title_content FROM `about`");
                                            $stmt->execute();
                                            $contact_info = $stmt->fetch(PDO::FETCH_ASSOC);

                                            // توضیحات با قابلیت شکستن خط هر 100 کاراکتر
                                            if (!empty($contact_info['title_content'])) {
                                                echo persianNumber($contact_info['title_content']);
                                            } else {
                                                echo ('هنوز مقداری وجود ندارد');
                                            }
                                        } catch (PDOException $e) {
                                            // در صورت خطا، اطلاعات پیش‌فرض را نمایش بده
                                            error_log('Database error in contact info: ' . $e->getMessage());
                                            echo 'خطا در اتصال با پایگاه داده.';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="d-none d-md-block col-12 col-md-6 header__content-img-parent">
                            <img src="./school/img/header/header.png" alt="Header Image"
                                class="header__content-img header__animation-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-md-none header__content-img-parent">
            <img src="./school/img/header/header.png" alt="Header Image"
                class="header__content-img header__animation-img">
        </div>
    </div>
    </header>