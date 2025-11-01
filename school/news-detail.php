<?php
include './session/init.php';
include "../helper/db.php";
include './login_state.php';
include '../persianNumber/persianNumber.php';
include '../englishNumber/englishNumber.php';
// تابع تبدیل تاریخ میلادی به شمسی
include './include/gregorian_to_jalali.php';

$profileUser = "";

// پردازش فرم ارسال نظر اصلی
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    
    if (!isset($_POST['news_id'], $_POST['comment']))
    {
        header("Location: ../");
        exit;
    }

    // اعتبارسنجی داده‌ها
    $news_id = (int)$_POST['news_id'];
    $comment = englishNumber(trim($_POST['comment']));
    $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$news_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        header("Location: ../");
        exit;
    }

    // cheack if there are any users
    if(isset($_SESSION["state"]) && $_SESSION["state"] == "true") {
        $name = $_SESSION["userInfo"]["username"];
        $user_id = $_SESSION["userInfo"]["id"];
        $profileUser = $_SESSION["userInfo"]["profile"];
    } else if(isset($_SESSION["stateHonaramoz"]) && $_SESSION["stateHonaramoz"] == "true") {
        $name = $_SESSION["honaramozInfo"]["username"];
        $user_id = $_SESSION["honaramozInfo"]["id"];
        $profileUser = $_SESSION["honaramozInfo"]["profile"];
    } else if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] == true) {
        $name = $_SESSION['adminName'];
        $user_id = $_SESSION["adminId"];
        $profileUser = $_SESSION["adminProFile"];
    } else {
        $_SESSION['success_message'] = 'لطفا اول ورود کنید.';
        $_SESSION['errorType'] = 'error';
        header("Location: news-detail.php?id=".$news_id);
        exit;
    }
    
    if (empty($name)) {
        $_SESSION['success_message'] = 'لطفا وارد شوید.';
        $_SESSION['errorType'] = 'error';
        header("Location: news-detail.php?id=".$news_id);
        exit;
    } elseif (strlen($name) > 100) {
        $_SESSION['success_message'] = 'نام نباید بیشتر از 100 کاراکتر باشد.';
        $_SESSION['errorType'] = 'error';
        header("Location: news-detail.php?id=".$news_id);
        exit;
    }
    
    // ۱- بررسی خالی بودن
    if (empty($comment)) {
        $_SESSION['success_message'] = "متن نظر نمی‌تواند خالی باشد.";
        $_SESSION['errorType'] = 'error';
        header("Location: news-detail.php?id=".$news_id);
        exit;
    }
    // ۲- بررسی طول (مثلاً حداقل ۳ و حداکثر 1000 کاراکتر)
    elseif (mb_strlen($comment, 'UTF-8') < 3) {
        $_SESSION['success_message'] = "نظر باید حداقل ۳ کاراکتر داشته باشد.";
        $_SESSION['errorType'] = 'error';
        header("Location: news-detail.php?id=".$news_id);
        exit;
    }
    
    // اگر خطایی وجود نداشت، نظر را ذخیره کنید
    if (empty($_SESSION['success_message'])) {
        try {
            if ($parent_id) {
                $stmt = $pdo->prepare("INSERT INTO comments (news_id, name, comment, parent_id, profile_user, user_id, status) VALUES (?, ?, ?, ?, ?, ?, 0)");
                $stmt->execute([$news_id, $name, $comment, $parent_id, $profileUser, $user_id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO comments (news_id, name, comment, profile_user, user_id, status) VALUES (?, ?, ?, ?, ?, 0)");
                $stmt->execute([$news_id, $name, $comment, $profileUser, $user_id]);
            }
            
            $_SESSION['success_message'] = 'نظر شما با موفقیت ثبت شد و پس از تایید مدیریت نمایش داده خواهد شد.';
            $_SESSION['errorType'] = 'success';
            header("Location: news-detail.php?id=".$news_id);
            exit();
        } catch (PDOException $e) {
            $_SESSION['success_message'] = 'خطا در ثبت نظر: ';
        }
    }
}

// دریافت خبر
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $created_at = new DateTime($item['created_at']);
        $year = (int)$created_at->format('Y');
        $month = (int)$created_at->format('m');
        $day = (int)$created_at->format('d');
        list($jyear, $jmonth, $jday) = gregorian_to_jalali($year, $month, $day);
        $jalali_date = $jyear.'/'.$jmonth.'/'.$jday;

        $image_path = (!empty($item['image']) && file_exists('../uploads/news/' . $item['image']))
            ? '../uploads/news/' . $item['image']
            : '../uploads/news/fore.jpg';
        
        // دریافت نظرات اصلی (بدون والد) که تایید شده‌اند
        try {
            $comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE news_id = ? AND parent_id IS NULL AND status = 1 ORDER BY created_at DESC");
            $comments_stmt->execute([$id]);
            $main_comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // دریافت پاسخ‌ها برای هر نظر که تایید شده‌اند
            $comments_with_replies = [];
            foreach ($main_comments as $comment) {
                $reply_stmt = $pdo->prepare("SELECT * FROM comments WHERE parent_id = ? AND status = 1 ORDER BY created_at ASC");
                $reply_stmt->execute([$comment['id']]);
                $replies = $reply_stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // تبدیل تاریخ برای نظر اصلی
                $comment_date = new DateTime($comment['created_at']);
                list($jy, $jm, $jd) = gregorian_to_jalali(
                    $comment_date->format('Y'),
                    $comment_date->format('m'),
                    $comment_date->format('d')
                );
                $comment['jalali_date'] = $jy.'/'.$jm.'/'.$jd;
                
                // تبدیل تاریخ برای پاسخ‌ها
                foreach ($replies as &$reply) {
                    $reply_date = new DateTime($reply['created_at']);
                    list($ry, $rm, $rd) = gregorian_to_jalali(
                        $reply_date->format('Y'),
                        $reply_date->format('m'),
                        $reply_date->format('d')
                    );
                    $reply['jalali_date'] = $ry.'/'.$rm.'/'.$rd;
                }
                
                $comment['replies'] = $replies;
                $comments_with_replies[] = $comment;
            }
        } catch (PDOException $e) {
            $comments_error = 'خطا در بارگذاری نظرات';
        }
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($item['title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="../sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="./css/my_css/style.css">
    <link rel="stylesheet" href="./css/my_css/news-detail.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include './include/nav_long.php'; ?>

    <!-- بخش خبر اصلی -->
    <div class="news-container">
        <div class="news-header d-flex align-items-center justify-content-between flex-wrap">
            <!-- <h1 class="news-title"><?php //echo htmlspecialchars($item['title']); ?></h1> -->

            <h1 class="news-title mb-4"><?php echo htmlspecialchars(persianNumber($item['title'])); ?></h1>
            <h6 class="news-title-writer mb-4 ms-auto"><?php echo !empty($item['writer']) ? htmlspecialchars($item['writer']) : 'ادمین'; ?><i class="fa fa-pen ms-2"></i></h6>

        </div>
        
        <?php if ($image_path): ?>
            <div class="news-image-container">
                <img src="<?php echo htmlspecialchars($image_path); ?>" 
                     alt="<?php echo htmlspecialchars($item['title']); ?>" 
                     class="news-image wow animate__fadeIn img-fluid"
                     onclick="openImageModal('<?php echo htmlspecialchars($image_path); ?>')">
            </div>
        <?php endif; ?>
        
        <div class="news-content">
            <p class="mt-5"><?php echo persianNumber($item['content']); ?></p>
            <div class="news-date"> <?php echo persianNumber($jalali_date); ?><i class="fas fa-calendar ms-2"></i></div>
        </div>
    </div>
    
    <!-- بخش نظرات -->
    <div class="comments-section overflow-hidden">
        <div class="text-center mb-4 wow animate__fadeIn">
            <h2 class="comments_title d-inline pb-1">
                <i class="fas fa-comments me-2"></i>نظرات 
            </h2>
        </div>
        
        <!-- فرم ارسال نظر اصلی -->
        <div class="card comment-form-card mb-5">
            <div class="card-body">
                <h5 class="card-title">ارسال نظر جدید</h5>
                <form id="commentForm" method="POST">
                    <input type="hidden" name="news_id" value="<?php echo $id; ?>">
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">متن نظر</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="نظر خود را بنویسید..." required onkeydown="return preventNewLine(event)"></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn-gradient mx-auto">
                            <i class="fas fa-paper-plane me-2"></i>ارسال نظر
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- لیست نظرات -->
        <div class="comments-list">
            <?php if (isset($comments_error)): ?>
                <div class="alert alert-danger"><?php echo $comments_error; ?></div>
            <?php elseif (empty($comments_with_replies)): ?>
                <div class="gradient-bg text-center py-2 mb-2 main__alert">
                    <p class="mb-0">هنوز نظری ثبت نشده است.</p>
                </div>
            <?php else: ?>
                <?php foreach ($comments_with_replies as $comment): ?>
                    <div class="comment-item wow animate__fadeInRight" id="comment-<?php echo $comment['id']; ?>">
                        <div class="comment-body">
                            <div class="comment-header">
                                <div class="comment-author">
                                    <?php
                                        if ($comment['profile_user'] != "" && file_exists($comment['profile_user'])) {
                                            $icon_profile = '
                                            <div class="comment-avatar-box">
                                                <img 
                                                src="'.$comment['profile_user'].'"
                                                 alt="پروفایل کاربران" class="w-100 h-100 object-fit-cover"
                                                 >
                                            </div>';
                                        } else {
                                            $icon_profile = '<i class="fas fa-user-circle comment-avatar"></i>';
                                        }
                                        echo ("$icon_profile");
                                    ?>
                                    <h6 class="m-0"><?php echo htmlspecialchars($comment['name']); ?></h6>
                                </div>
                                <small class="comment-date"><?php echo persianNumber($comment['jalali_date']); ?><i class="fas fa-calendar ms-2"></i></small>
                            </div>
                            <p class="comment-text text-justify"><?php echo persianNumber(nl2br(htmlspecialchars($comment['comment']))); ?></p>
                            
                            <!-- فرم پاسخ -->
                            <div class="reply-form" id="reply-form-<?php echo $comment['id']; ?>">
                                <form method="POST">
                                    <input type="hidden" name="news_id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="reply-comment-<?php echo $comment['id']; ?>" class="form-label">متن پاسخ</label>
                                        <textarea class="form-control" id="reply-comment-<?php echo $comment['id']; ?>" name="comment" rows="3" placeholder="پاسخ خود را بنویسید..." required onkeydown="return preventNewLine(event)"></textarea>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <div class="z-index-btn">
                                            <button type="button" class="btn-gradient mx-auto" onclick="hideReplyForm(<?php echo $comment['id']; ?>)">انصراف</button>
                                        </div>
                                        <div class="z-index-btn">
                                            <button type="submit" class="btn-gradient mx-auto">ارسال پاسخ</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- نمایش پاسخ‌ها -->
                        <?php if (!empty($comment['replies'])): ?>
                            <div class="comment-replies">
                                <?php foreach($comment['replies'] as $reply_comment): ?>
                                    <div class="reply-item">
                                        <div class="comment-header">
                                            <div class="comment-author">
                                                <?php
                                                    if ($reply_comment['profile_user'] != "" && file_exists($reply_comment['profile_user'])) {
                                                        $icon_profile = '
                                                        <div class="comment-avatar-box">
                                                            <img 
                                                            src="'.$reply_comment['profile_user'].'"
                                                             alt="پروفایل کاربران" class="w-100 h-100 object-fit-cover"
                                                             >
                                                        </div>';
                                                    } else {
                                                        $icon_profile = '<i class="fas fa-user-circle comment-avatar"></i>';
                                                    }
                                                    echo ("$icon_profile");
                                                ?>
                                                <h6 class="m-0"><?php echo htmlspecialchars($reply_comment['name']); ?></h6>
                                            </div>

                                            

                                            <small class="comment-date"><?php echo persianNumber($reply_comment['jalali_date']); ?><i class="fas fa-calendar ms-2"></i></small>

                                        </div>
                                        <p class="comment-text text-justify"><?php echo nl2br(htmlspecialchars(persianNumber($reply_comment['comment']))); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- مودال تصویر -->
    <div id="imageModal" class="image-modal">
        <span id="close-span" class="close-btn" onclick="closeImageModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" class="modal-image">
        </div>
    </div>
    
    <?php include "./include/footer.php"; ?>

    <script src="../bootstrap_rtl/bootstrap.bundle.min.js"></script>
    <script src="../jquery/jquery-3.6.0.min.js"></script>
    <script src="./js/my_js/main_script.js"></script>
    <script src="./js/my_js/news_detail_script.js"></script>
    <script src="../sweetalert2/sweetalert2.all.min.js"></script>
    <script src="../error_management/error_management_script.js"></script>

    <?php
        if(isset($_SESSION["success_message"]))
        {
            echo '
            <script> errorFunction("'. htmlspecialchars($_SESSION["errorType"]).'", "'.htmlspecialchars($_SESSION["success_message"]) .'"); </script>
            ';
            unset($_SESSION["success_message"]);
            unset($_SESSION["errorType"]);
        }
    ?>

</body>
</html>
<?php

    } else {
        echo '<div class="container mt-5 text-center alert alert-warning">خبر یافت نشد.</div>';
    }
} else {
    echo '<div class="container mt-5 text-center alert alert-danger">شناسه معتبر نیست.</div>';
}

?>