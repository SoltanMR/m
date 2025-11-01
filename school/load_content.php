<?php
include './session/init.php';
include '../helper/db.php';
include './login_state.php';
include '../persianNumber/persianNumber.php';

function shortTitle($title, $limit = 50) {
    return mb_strlen($title, 'UTF-8') > $limit ? mb_substr($title, 0, $limit, 'UTF-8') . '...' : $title;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'homework'; // homework یا exam
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$user = $_SESSION['userInfo'];

if ($type === "homework") {
    $table = "tamrinat";
    $img = "homework.png";
    $itemClass = "homework-items";
    $name = "نمونه سوال";
} else {
    $table = "nmonasoal";
    $img = "exam.png";
    $itemClass = "exam-items";
    $name = "تمرین";
}

try {

// گرفتن آیتم‌ها با LIMIT و OFFSET به صورت مستقیم
$sql = "SELECT * FROM $table WHERE reshta = ? AND paye = ? ORDER BY id DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user['reshtaha'], $user['paye']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تعداد کل برای صفحه‌بندی
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE reshta = ? AND paye = ?");
$countStmt->execute([$user['reshtaha'], $user['paye']]);
$totalItems = (int)$countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

} catch (PDOException $e) {
 echo '<div class="gradient-bg text-center py-2 mb-2 main__alert">
            <p class="mb-0">خطا در دریافت اطلاعات از پایگاه داده.</p>
          </div>';
    exit;
}

$html = '';
foreach ($items as $item) {
    $html .= '<div class="my-2 d-flex justify-content-between align-items-center '.$itemClass.' p-3 flex-column flex-sm-row" data-id="'.$item['id'].'">';
    $html .= '<div><img src="./img/userPanel/'.$img.'" class="'.($type==="homework"?"homework-img":"exam-img").'"></div>';
    $html .= '<div class="my-3 my-sm-0 card-bode-text"><span>'.htmlspecialchars(shortTitle(persianNumber($item['title']))).'</span></div>';
    $html .= '<div><button class="btn-gradient mx-auto openModalBtn" data-type="'.$type.'">بیشتر</button></div>';
    $html .= '</div>';
}

if ($totalItems === 0) {
    $html .= '
        <div class="gradient-bg text-center py-2 mb-2 main__alert">
            <p class="mb-0">هنوز هیچ '. $name .' سوالی ثبت نشده است.</p>
        </div>
    ';
} else {
    // شماره صفحات
    $html .= '<div class="pagination text-center mt-3">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i === $page ? 'btn-gradient btn__active' : 'btn-gradient';
        $html .= '<button class="btn '.$active.' page-btn mx-1" data-type="'.$type.'" data-page="'.$i.'">'.persianNumber($i).'</button>';
    }
    $html .= '</div>';
}

echo $html;
