<?php
session_start();
include '../helper/db.php';
include '../persianNumber/persianNumber.php';

// بررسی وجود student_id
if (!isset($_SESSION["userInfo"]["id"]) || empty($_SESSION["userInfo"]["id"])) {
    die("هنرجو وارد نشده است");
}
$student_id = intval($_SESSION["userInfo"]["id"]);

// بررسی انتخاب گزارش
if (!isset($_POST["selected_report"])) {
    $msg = urlencode("گزارشی انتخاب نشده است.");
    header("Location: report_card.php?msg=$msg");
    exit;
}

$report_get = $_POST["selected_report"];

// دریافت اطلاعات گزارش
try {
    $stmt = $pdo->prepare("SELECT name, data_start, data_end FROM report_card WHERE name = ?");
    $stmt->execute([$report_get]);
    $report_card_name = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report_card_name) {
        echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
گزارش مورد نظر یافت نشد
            </div>
          </div>';
    }
} catch (PDOException $e) {
     echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
خطا در دریافت اطلاعات گزارش
            </div>
          </div>';
}

// دریافت نوع نمرات گزارش
try {
    $stmtType = $pdo->prepare("SELECT scores_type FROM report_card WHERE name = ?");
    $stmtType->execute([$report_get]);
    $scores_type = $stmtType->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
     echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
خطا در دریافت نوع نمرات
            </div>
          </div>';
}

// تفکیک نمرات به پودمان و میان‌ترم
$podeman = [];
$midterm = [];

if (!empty($scores_type)) {
    foreach ($scores_type as $score_type) {
        if (isset($score_type['scores_type'])) {
            if (mb_substr($score_type['scores_type'], 0, 1) === 'پ') {
                $podeman[] = $score_type['scores_type'];
            } else {
                $midterm[] = $score_type['scores_type'];
            }
        }
    }
}

// دریافت اطلاعات هنرجو
try {
    $stmtStudent = $pdo->prepare("
        SELECT h.*, r.name AS reshtaha_name, p.paye AS paye_name
        FROM honarjoyan h
        LEFT JOIN reshtaha r ON h.reshtaha = r.id
        LEFT JOIN paye p ON h.paye = p.id
        WHERE h.id = ?
    ");
    $stmtStudent->execute([$student_id]);
    $student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
اطلاعات هنرجو یافت نشد
            </div>
          </div>';
    }
} catch (PDOException $e) {
    echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
خطا در دریافت اطلاعات هنرجو
            </div>
          </div>';
}

// ترکیب نمرات برای ترتیب دلخواه
$ordered_scores_type = array_merge($podeman, $midterm);

// گرفتن نمرات دانش‌آموز
$scores = [];

try{
foreach ($ordered_scores_type as $type_name) {
    $stmt = $pdo->prepare("
        SELECT s.*, b.books_name, b.vahed
        FROM score s
        LEFT JOIN books b ON s.books_id = b.id
        JOIN honarjoyan h ON s.honarjoyan_id = h.id
        WHERE s.honarjoyan_id = ? 
          AND s.month_time = ?
          AND h.paye = ? 
          AND h.reshtaha = ?
    ");
    $stmt->execute([$student_id, $type_name, $student['paye'], $student['reshtaha']]);
    $scores = array_merge($scores, $stmt->fetchAll(PDO::FETCH_ASSOC));
}
} catch (PDOException $e) {
     echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
خطا در دریافت نمرات هنرجو
            </div>
          </div>';
}
if (empty($scores)) {
     echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
نمرات هنرجو یافت نشد
            </div>
          </div>';
}
// محاسبه میانگین نمرات برای هر کتاب
$book_scores = [];
$book_counts = [];

foreach ($scores as $sc) {
    if (!isset($sc['books_name'], $sc['score'])) {
        continue; // رد کردن داده ناقص
    }
    $book = $sc['books_name'];
    $score = floatval($sc['score']);
    
    if (!isset($book_scores[$book])) {
        $book_scores[$book] = 0;
        $book_counts[$book] = 0;
    }
    
    $book_scores[$book] += $score;
    $book_counts[$book]++;
}

// محاسبه میانگین وزنی برای هر کتاب
$books_average = [];
$scores_by_book = [];

foreach ($book_scores as $book => $total) {


    if ($book_counts[$book] === 0) {
        continue; // جلوگیری از تقسیم بر صفر
    }

    $avg = $total / $book_counts[$book];
    $scores_by_book[$book] = $avg;
    
    // پیدا کردن واحد کتاب
    foreach ($scores as $s) {
        if ($s['books_name'] === $book) {
            $books_average[$book] = $avg * floatval($s['vahed']);
            break;
        }
    }
 
}// ---- محاسبه رتبه در کلاس ----
try {
    $classmates_stmt = $pdo->prepare("
        SELECT h.id, h.username
        FROM honarjoyan h
        WHERE h.paye = ? AND h.reshtaha = ?
    ");
    $classmates_stmt->execute([$student['paye'], $student['reshtaha']]);
    $classmates_list = $classmates_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($classmates_list)) {
        echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
همکلاسی برای این هنرجو یافت نشد
            </div>
          </div>';
    }
} catch (PDOException $e) {
     echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
خطا در دریافت لیست همکلاسی های هنرجو
            </div>
          </div>';
}

// تابع محاسبه میانگین گروهی کتاب‌ها
function compute_grouped_book_averages($pdo, $student_id, $types_array) {
    $result = [];
    if (empty($types_array)) return $result;

    $placeholders = implode(',', array_fill(0, count($types_array), '?'));
    $sql = "
        SELECT s.*, b.books_name, b.vahed, b.id AS book_id
        FROM score s
        LEFT JOIN books b ON s.books_id = b.id
        WHERE s.honarjoyan_id = ? AND s.month_time IN ($placeholders)
    ";

    $params = array_merge([$student_id], $types_array);

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // در صورت خطا، خروجی خالی برگردان
        return [];
    }

    $sums = [];
    foreach ($rows as $r) {
        if (!isset($r['books_name'], $r['score'], $r['month_time'])) {
            continue; // رد داده ناقص
        }

        $book = $r['books_name'];
        $is_podeman = (mb_substr($r['month_time'], 0, 1) === 'پ');

        if (!isset($sums[$book])) {
            $sums[$book] = [
                'podeman_sum' => 0, 'podeman_count' => 0,
                'mid_sum' => 0, 'mid_count' => 0,
                'vahed' => isset($r['vahed']) ? floatval($r['vahed']) : 0,
                'book_id' => $r['book_id'] ?? null
            ];
        }

        $score = floatval($r['score']);
        if ($is_podeman) {
            $sums[$book]['podeman_sum'] += $score;
            $sums[$book]['podeman_count'] += 1;
        } else {
            $sums[$book]['mid_sum'] += $score;
            $sums[$book]['mid_count'] += 1;
        }

        // اگر vahed هنوز تنظیم نشده و مقدار معتبر وجود دارد
        if (empty($sums[$book]['vahed']) && !empty($r['vahed'])) {
            $sums[$book]['vahed'] = floatval($r['vahed']);
        }
    }

    foreach ($sums as $book => $v) {
        $pod_avg = $v['podeman_count'] > 0 ? $v['podeman_sum'] / $v['podeman_count'] : null;
        $mid_avg = $v['mid_count'] > 0 ? $v['mid_sum'] / $v['mid_count'] : null;

        $result[$book] = [
            'podeman' => $pod_avg,
            'midterm' => $mid_avg,
            'unit' => $v['vahed'],
            'book_id' => $v['book_id']
        ];
    }

    return $result;
}

function monthNames($name) {
    switch ($name) {
        case "مستمر پاییز":
            return '<div class="average_year-name border-b-l"><p class="text-center">مهر و آبان ماه</p></div>';
        case "آذر": 
            return '<div class="average_year-name border-b-l"><p class="text-center">آذر ماه</p></div>';
        case "نوبت اول": 
            return '<div class="average_year-name border-b-l"><p class="text-center">نوبت اول</p></div>';
        case "مستمر زمستان": 
            return '<div class="average_year-name border-b-l"><p class="text-center">بهمن و اسفند ماه</p></div>';
        case "مستمر بهار": 
            return '<div class="average_year-name border-b-l"><p class="text-center">فروردین و اردیبهشت ماه</p></div>';
        case "نوبت دوم": 
            return '<div class="average_year-name border-l"><p class="text-center">نوبت دوم</p></div>';
        default:
            return '<div class="average_year-name border-b-l"><p class="text-center">' . htmlspecialchars($name) . '</p></div>';
    }
}

// ترتیب گزارش‌ها
$reports_order = [
    "مستمر پاییز",
    "نوبت اول",
    "مستمر زمستان",
    "مستمر بهار",
    "نوبت دوم"
];

$current_report = $report_card_name['name'] ?? null;
$current_index = array_search($current_report, $reports_order);

// انواع نمره‌های کارنامه فعلی
$current_types = [];
try {
    $stmtTypesCurrent = $pdo->prepare("SELECT scores_type FROM report_card WHERE name = ?");
    $stmtTypesCurrent->execute([$current_report]);
    $current_types = $stmtTypesCurrent->fetchAll(PDO::FETCH_COLUMN) ?: [];
} catch (PDOException $e) {
     echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
خطا در دریافت نوع نمره
            </div>
          </div>';
    $current_types = [];
}

// کارنامه قبلی
$previous_report_name = null;
if ($current_index !== false && $current_index > 0) {
    $previous_report_name = $reports_order[$current_index - 1];
}

// انواع نمره‌های کارنامه قبلی
$previous_types = [];
if ($previous_report_name) {
    try {
        $stmtTypesPrev = $pdo->prepare("SELECT scores_type FROM report_card WHERE name = ?");
        $stmtTypesPrev->execute([$previous_report_name]);
        $previous_types = $stmtTypesPrev->fetchAll(PDO::FETCH_COLUMN) ?: [];
    } catch (PDOException $e) {
         echo ' <div class="error-container d-print-none">
 <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
نوع نمره پیدا نشد
            </div>
          </div>';
        $previous_types = [];
    }
}

// محاسبه میانگین‌های کتابی برای کارنامه فعلی و قبلی
$current_book_groups = compute_grouped_book_averages($pdo, $student_id, $current_types);
$previous_book_groups = compute_grouped_book_averages($pdo, $student_id, $previous_types);

// محاسبه رشد برای هر کتاب
$growth_data = [];
foreach ($scores_by_book as $book_name => $current_avg) {
    $used_group = null;

    if (isset($current_book_groups[$book_name])) {
        if ($current_book_groups[$book_name]['podeman'] !== null) {
            $used_group = 'podeman';
        } elseif ($current_book_groups[$book_name]['midterm'] !== null) {
            $used_group = 'midterm';
        }
    }

    if ($used_group === null) {
        foreach ($scores as $sc) {
            if ($sc['books_name'] === $book_name) {
                $used_group = mb_substr($sc['month_time'], 0, 1) === 'پ' ? 'podeman' : 'midterm';
                break;
            }
        }
    }

    $growth_data[$book_name] = 'ندارد';

    if ($used_group !== null) {
        $cur_val = $used_group === 'podeman' 
            ? ($current_book_groups[$book_name]['podeman'] ?? null) 
            : ($current_book_groups[$book_name]['midterm'] ?? null);

        $prev_val = null;
        if (isset($previous_book_groups[$book_name])) {
            if ($previous_book_groups[$book_name][$used_group] !== null) {
                $prev_val = $previous_book_groups[$book_name][$used_group];
            } else {
                $other = $used_group === 'podeman' ? 'midterm' : 'podeman';
                $prev_val = $previous_book_groups[$book_name][$other] ?? null;
            }
        }

        if ($cur_val !== null && $prev_val !== null) {
            $growth = $cur_val - $prev_val;
            $growth_data[$book_name] = number_format($growth, 2);
        }
    }
}

// محاسبه معدل کل
$total_score = 0;
$total_unit = 0;

foreach ($current_book_groups as $book_name => $groups) {
    $avg_score = $groups['podeman'] ?? $groups['midterm'] ?? 0;
    $unit = $groups['unit'] ?? 0;

    $total_score += $avg_score * $unit;
    $total_unit += $unit;
}

$average = ($total_unit > 0) ? $total_score / $total_unit : 0;
$average_no_weight = count($scores_by_book) > 0 ? array_sum($scores_by_book) : 0;

// محاسبه رشد کلی
$overall_growth = "ندارد";
if (!empty($previous_types) && !empty($current_types)) {
    $prev_total_score = 0.0;
    $prev_total_unit = 0.0;

    foreach ($current_book_groups as $book_name => $cur_groups) {
        $used_group = null;
        if ($cur_groups['podeman'] !== null) $used_group = 'podeman';
        elseif ($cur_groups['midterm'] !== null) $used_group = 'midterm';
        if ($used_group === null) continue;

        $unit = $cur_groups['unit'] ?? 0;
        $prev_val = null;

        if (isset($previous_book_groups[$book_name])) {
            if ($previous_book_groups[$book_name][$used_group] !== null) {
                $prev_val = $previous_book_groups[$book_name][$used_group];
            } else {
                $other = $used_group === 'podeman' ? 'midterm' : 'podeman';
                $prev_val = $previous_book_groups[$book_name][$other] ?? null;
            }
        }

        if ($prev_val !== null && $unit > 0) {
            $prev_total_unit += floatval($unit);
            $prev_total_score += floatval($prev_val) * floatval($unit);
        }
    }

    if ($prev_total_unit > 0) {
        $prev_average = $prev_total_score / $prev_total_unit;
        $overall_growth_value = round($average - $prev_average, 2);

        if ($overall_growth_value > 0) {
            $overall_growth = persianNumber(number_format($overall_growth_value, 2)) . '+';
        } elseif ($overall_growth_value < 0) {
            $overall_growth = persianNumber(number_format(abs($overall_growth_value), 2)) . '-';
        } else {
            $overall_growth = persianNumber(number_format($overall_growth_value, 2));
        }
    }
}

// محاسبه رتبه در کلاس
$class_ranking = [];

if (!empty($classmates_list) && is_array($classmates_list)) {
    foreach ($classmates_list as $mate) {
        $mate_id = $mate['id'];
        try {
            $mate_book_groups = compute_grouped_book_averages($pdo, $mate_id, $current_types);
        } catch (Exception $e) {
            echo ' <div class="error-container d-print-none">
                <div class="custom-error">
                    <i class="fas fa-exclamation-triangle ms-2"></i>
                    خطا در محاسبه میانگین همکلاسی‌ها
                </div>
            </div>';
            $mate_book_groups = [];
        }

        $total_score_mate = 0;
        $total_unit_mate = 0;

        foreach ($mate_book_groups as $groups) {
            $used_group = $groups['podeman'] !== null ? 'podeman' : 'midterm';
            $score_val = $groups[$used_group] ?? 0;
            $unit = $groups['unit'] ?? 0;

            $total_score_mate += $score_val * $unit;
            $total_unit_mate += $unit;
        }

        $average_mate = $total_unit_mate > 0 ? $total_score_mate / $total_unit_mate : 0;

        $class_ranking[] = [
            'id' => $mate_id,
            'username' => $mate['username'],
            'average' => $average_mate
        ];
    }
}

usort($class_ranking, function($a, $b) {
    return $b['average'] <=> $a['average'];
});

$rank_in_class = null;
foreach ($class_ranking as $i => $st) {
    if ($st['id'] == $student_id) {
        $rank_in_class = $i + 1;
        break;
    }
}

$class_total = array_sum(array_column($class_ranking, 'average'));
$class_count = count($class_ranking);
$class_average = $class_count > 0 ? $class_total / $class_count : 0;

$diff_with_class = $average - $class_average;
$abs = number_format(abs($diff_with_class), 2);
$persian_abs = persianNumber($abs);

$diff_html = $diff_with_class < 0
    ? $persian_abs . '-'
    : persianNumber(number_format($diff_with_class, 2));

// محاسبه رتبه در کل مدرسه
$schoolmates = [];
try {
    // محاسبه رتبه در کل مدرسه - با فیلتر زمانی کارنامه جاری
    $schoolmatesStmt = $pdo->prepare("
        SELECT 
            h.id, 
            h.username,
            SUM(book_data.avg_score * book_data.vahed) / NULLIF(SUM(book_data.vahed), 0) AS average
        FROM honarjoyan h
        JOIN (
            SELECT 
                s.honarjoyan_id,
                b.books_name,
                AVG(s.score) as avg_score,
                b.vahed
            FROM score s
            JOIN books b ON s.books_id = b.id
            WHERE s.month_time IN (" . str_repeat('?,', count($current_types) - 1) . "?)
            GROUP BY s.honarjoyan_id, b.books_name, b.vahed
        ) as book_data ON h.id = book_data.honarjoyan_id
        GROUP BY h.id
        ORDER BY average DESC
    ");
        
    $schoolmatesStmt->execute($current_types);
    $schoolmates = $schoolmatesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo ' <div class="error-container d-print-none">
        <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
            خطا در دریافت و محاسبه رتبه در کل مدرسه 
        </div>
    </div>';
    $schoolmates = [];
}
    
// پیدا کردن رتبه در مدرسه
$rank_in_school = null;
foreach ($schoolmates as $i => $st) {
    if ($st['id'] == $student_id) {
        $rank_in_school = $i + 1;
        break;
    }
}

$total_classmates = count($class_ranking);
$total_schoolmates = count($schoolmates);

// محاسبه میانگین‌های ماهانه
$monthly_averages = [
    "مستمر پاییز" => null,
    "نوبت اول" => null,
    "مستمر زمستان" => null,
    "مستمر بهار" => null,
    "نوبت دوم" => null
];
$monthly_names = [
    "مهر و آبان ماه",
    "آذر ماه",
    "نوبت اول",
    "بهمن و اسفند ماه",
    "فروردین و اردیبهشت ماه",
    "نوبت دوم"
];

$stmtReportRow = [];
try {
    $stmtReportCard = $pdo->prepare("SELECT DISTINCT name FROM report_card;");
    $stmtReportCard->execute();
    $stmtReportRow = $stmtReportCard->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo ' <div class="error-container d-print-none">
        <div class="custom-error">
            <i class="fas fa-exclamation-triangle ms-2"></i>
            خطا در دریافت لیست کارنامه‌ها
        </div>
    </div>';
    $stmtReportRow = [];
}

usort($stmtReportRow, function($a, $b) use ($reports_order) {
    $pos_a = array_search($a, $reports_order);
    $pos_b = array_search($b, $reports_order);
    return $pos_a - $pos_b;
});

foreach ($stmtReportRow as $stmtReport) {
    try {
        $stmtScoreType = $pdo->prepare("SELECT scores_type FROM report_card WHERE name = ?");
        $stmtScoreType->execute([$stmtReport]);
        $scores_type_row = $stmtScoreType->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        echo ' <div class="error-container d-print-none">
            <div class="custom-error">
                <i class="fas fa-exclamation-triangle ms-2"></i>
                خطا در دریافت نوع نمره‌های کارنامه ' . htmlspecialchars($stmtReport) . '
            </div>
        </div>';
        $scores_type_row = [];
    }

    try {
        $book_groups = compute_grouped_book_averages($pdo, $student_id, $scores_type_row);
    } catch (Exception $e) {
        echo ' <div class="error-container d-print-none">
            <div class="custom-error">
                <i class="fas fa-exclamation-triangle ms-2"></i>
                خطا در محاسبه میانگین‌های کتابی برای ' . htmlspecialchars($stmtReport) . '
            </div>
        </div>';
        $book_groups = [];
    }

    $total_score_month = 0;
    $total_unit_month = 0;

    foreach ($book_groups as $groups) {
        $used_group = $groups['podeman'] !== null ? 'podeman' : 'midterm';
        $score_val = $groups[$used_group] ?? 0;
        $unit = $groups['unit'] ?? 0;

        $total_score_month += $score_val * $unit;
        $total_unit_month += $unit;
    }

    $monthly_averages[$stmtReport] = $total_unit_month > 0
        ? $total_score_month / $total_unit_month
        : 0;

    if ($stmtReport === $current_report) break;
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کارنامه <?= htmlspecialchars($student['username']) ?></title>

    <!-- استایل‌ها -->
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
    <link rel="stylesheet" href="../bootstrap_rtl/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../font_awesome/css/all.min.css">
    <link rel="stylesheet" href="./css/my_css/report_card_style.css">
    <link rel="stylesheet" href="../website_font/css/fonts.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="back-button-container d-print-none text-center my-4">
  <a href="report_card.php" class="back-button">
    <i class="fas fa-arrow-right ms-2"></i>
    بازگشت
  </a>
</div>

<div class="back-button-container text-center my-4">
    <button onclick="window.print()" class="print-button d-print-none">چاپ کارنامه</button>
</div>

<div class="container-fluid px-0">
    <div class="container">
        <div class="report-card-container">
                <main class="report-card">
                    <div class="report-card-inner">
                        <!-- معرفی -->
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM info_report");
                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } catch (Exception $e) {
                            die('  <div class="error-state-container">
                                        <div class="error-state-card">
                                            <i class="fas fa-exclamation-triangle error-state-icon"></i>
                                            <h4 class="error-state-title">خطا در ارتباط با پایگاه داده</h4>
                                            <p class="error-state-text">لطفاً دوباره تلاش کنید</p>
                                        </div>
                                    </div>
                                ');
                        }
                        ?>
                        <div class="introduction">
                            <div class="speach w-100">
                                <?php if (!empty($results)) { ?>
                                    <p class="text-center"><?php echo htmlspecialchars($results[0]['title_report']); ?></p>
                                <?php } ?>
                            </div>
                            <div class="d-flex">
                                <div class="contoury">
                                    <?php if (!empty($results)) { ?>
                                        <p class="text-center"><?php echo htmlspecialchars($results[0]['name_contoury']); ?></p>
                                    <?php } ?>
                                </div>
                                <div class="city">
                                    <?php if (!empty($results)) { ?>
                                        <p class="text-center"><?php echo htmlspecialchars($results[0]['name_city']); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!-- اطلاعات دانش‌آموز -->
                        <div class="d-flex report-card-main">
                            <div class="student_info">
                                <div class="d-flex flex-column">
                                    <div class="school_rate">
                                        <?php if (!empty($results)) { ?>
                                                <p class="text-center">کد واحد آموزشی :<?php echo htmlspecialchars(persianNumber($results[0]['school_rate'])); ?> </p>
                                        <?php } ?>

                                        <p class="text-center">کارنامه ی : <?= htmlspecialchars($report_card_name['name']) ?></p>
                                        <p class="text-center">ردیف دانش آموز : <?= persianNumber($_SESSION["userInfo"]["id"]) ?></p>
                                        <p class="text-center">رشته و پایه : <?= htmlspecialchars($_SESSION["userInfo"]["paye"]) . " " . htmlspecialchars($_SESSION["userInfo"]["reshtaha"]) ?></p>
                                        <p class="text-center">سال تحصیلی : <?= htmlspecialchars(persianNumber($report_card_name['data_end']) . "-" . persianNumber($report_card_name['data_start'])) ?></p>
                                    </div>

                                    <div class="student_name py-5">
                                        <p class="text-center">نام و نام خانوادگی : <?= htmlspecialchars($_SESSION["userInfo"]["username"]) ?></p>
                                        <p class="text-center">نام پدر : <?= htmlspecialchars($_SESSION["userInfo"]["namepdar"] ?? '') ?></p>
                                    </div>

                                    <div class="pb-2">
                                        <p class="text-center">معدل: <?= persianNumber(number_format($average, 2)) ?></p>

                                        <p class="text-center">
                                            رتبه در کلاس: <?= persianNumber($rank_in_class ?? '-') ?> 
                                            از <?= persianNumber($total_classmates) ?> نفر
                                        </p>

                                        <p class="text-center">
                                            رتبه در مدرسه: <?= persianNumber($rank_in_school ?? '-') ?> 
                                            از <?= persianNumber($total_schoolmates) ?> نفر
                                        </p>

                                        <?php
                                        $status = 'نامشخص';
                                        if ($average >= 18) {
                                            $status = 'عالی';
                                        } elseif ($average >= 15) {
                                            $status = 'خوب';
                                        } elseif ($average >= 10) {
                                            $status = 'قبول';
                                        } else {
                                            $status = 'نیاز به تلاش بیشتر';
                                        }
                                        ?>
                                        <p class="text-center">وضعیت درس : <?= htmlspecialchars($status) ?></p>
                                        <p class="text-center">میانگین کلاسی : <?= persianNumber(number_format($class_average, 2)) ?></p>
                                        <p class="text-center">اختلاف با میانگین کلاسی : <?= $diff_html ?></p>
                                        <p class="text-center">رشد نسبت به مرحله قبلی : <?= $overall_growth ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- جدول نمرات -->
                            <div class="student_grade">
                                <div class="d-flex school_name">
                                    <div class="w-100">
                                        <?php if (!empty($results)) { ?>
                                            <p class="text-center"><?php echo htmlspecialchars($results[0]['school_name']); ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="grades_font">
                                    <div class="grades_header d-flex">
                                        <div class="row_number border-b-l text-center">ردیف</div>
                                        <div class="book_name border-b-l text-center">موارد درسی</div>
                                        <div class="qulety border-b-l text-center">واحد</div>
                                        <div class="grade border-b-l text-center">نمره</div>
                                        <div class="how_is_it border-b-l text-center">وضعیت قبولی</div>
                                        <div class="border-b prograse text-center">رشد</div>
                                    </div>

                                    <div class="grades_main">
                                        <?php 
                                        $passInAll = false;
                                        $unique_books = array_keys($scores_by_book); 
                                        $i = 1; 
                                        foreach($unique_books as $book_name): 
                                            $unit = 0;
                                            foreach ($scores as $sc) {
                                                if ($sc['books_name'] === $book_name) { 
                                                    $unit = $sc['vahed']; 
                                                    break; 
                                                }
                                            }

                                            if ($unit == 0) {
                                                $stmtBook = $pdo->prepare("SELECT vahed FROM books WHERE books_name = ? LIMIT 1");
                                                $stmtBook->execute([$book_name]);
                                                $unit = floatval($stmtBook->fetchColumn() ?? 0);
                                            }
                                                                                        
                                            $stmtBookType = $pdo->prepare("SELECT type FROM books WHERE books_name = ? LIMIT 1");
                                            $stmtBookType->execute([$book_name]);
                                            $bookType = $stmtBookType->fetch(PDO::FETCH_ASSOC);
                                            
                                            $type = $bookType['type'] ?? null;

                                            if ($type == 'پودمانی')
                                            {
                                                $bookPassFalle = $scores_by_book[$book_name] >= 12 ? 'قبول' : 'تجدید';
                                            }
                                            else
                                            {
                                                $bookPassFalle = $scores_by_book[$book_name] >= 10 ? 'قبول' : 'تجدید';
                                            }

                                            if ($bookPassFalle == 'تجدید') {
                                                $passInAll = true;
                                            }
                                        ?>
                                            <div class="d-flex">
                                                <div class="border-b-l row_number text-center"><?= persianNumber($i) ?></div>
                                                <div class="border-b-l book_name text-center"><?= htmlspecialchars($book_name) ?></div>
                                                <div class="border-b-l qulety text-center"><?= persianNumber($unit) ?></div>
                                                <div class="border-b-l grade text-center">
                                                    <?= persianNumber(number_format($scores_by_book[$book_name], 2)) ?>
                                                </div>
                                                <div class="border-b-l how_is_it text-center">
                                                    <?= $bookPassFalle ?>
                                                </div>
                                                <div class="border-b prograse text-center">
                                                    <?php 
                                                    if (isset($growth_data[$book_name]) && $growth_data[$book_name] !== 'ندارد') {
                                                        $growth_value = floatval($growth_data[$book_name]);
                                                        if ($growth_value > 0) {
                                                            echo persianNumber(number_format($growth_value, 2)) . '+';
                                                        } elseif ($growth_value < 0) {
                                                            echo persianNumber(number_format(abs($growth_value), 2)) . '-';
                                                        } else {
                                                            echo persianNumber(number_format($growth_value, 2));
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        <?php $i++; endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- جمع‌بندی و میانگین‌ها -->
                            <div class="year_info">
                                <div class="year_info-title">
                                    <div class=" border-b">
                                        <p class="text-center">میانگین نمرات به تفکیک ماه ها</p>
                                    </div>
                                </div>

                                <div class="average_year">
                                    <?php foreach ($monthly_averages as $month => $avg): ?>
                                        <?php
                                            if ($month == 'نوبت اول') {
                                                echo '
                                                    <div class="d-flex">
                                                        '. monthNames("آذر") .'
                                                        <div class="average_year-grade border-b">
                                                            <p class="text-center"></p>
                                                        </div>
                                                    </div>
                                                ';
                                            }
                                        ?>
                                        <div class="d-flex">
                                            <?php echo(monthNames($month)) ?>
                                            <div class="average_year-grade border-b">
                                                <p class="text-center">
                                                    <?= $avg !== null ? persianNumber(number_format($avg, 2)) : '' ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                        
                                <div class="average_month">
                                    <div class="d-flex">
                                        <div class="average_year-name border-b-l text-center">تعداد واحدها</div>
                                        <div class="average_year-grade border-b text-center"><?= persianNumber($total_unit) ?></div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="average_year-name border-b-l text-center">میانگین نمرات بدون ضریب</div>
                                        <div class="average_year-grade border-b text-center"><?= persianNumber(number_format($average_no_weight, 2)) ?></div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="average_year-name border-l text-center">جمع نمرات با ضریب واحد</div>
                                        <div class="average_year-grade text-center"><?= persianNumber(number_format($total_score, 2)) ?></div>
                                    </div>
                                </div>
                                        
                                <div class="d-flex flex-column pass_falle">
                                    <div class=" border-b">
                                        <p class="text-center">وضعیت نهایی تحصیلی</p>
                                    </div>
                                    <div class="">
                                        <p class="text-center"><?= $passInAll == true ? 'تجدید' : 'قبول' ?></p>
                                    </div>
                                </div>
                                        
                                <div class="author">
                                    <div class="author_name mb-5">
                                        <div>
                                            <p class="text-center">مسؤول ثبت نمرات:</p>
                                        </div>
                                        <div>
                                            <?php if (!empty($results)) { ?>
                                                <p class="text-center"><?php echo htmlspecialchars($results[0]['author_name']); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                        
                                    <div class="manager">
                                        <div>
                                            <p class="text-center">مدیر آموزشگاه:</p>
                                        </div>
                                        <div>
                                            <?php if (!empty($results)) { ?>
                                                <p class="text-center"><?php echo htmlspecialchars($results[0]['manager_name']); ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>


                    <div class="report-card-footer">
                        <p class="text-center"><?php echo htmlspecialchars(persianNumber($results[0]['adriss_school'])); ?></p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

</body>
</html>
