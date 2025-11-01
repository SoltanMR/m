<?php
include './session/init.php';
include '../helper/db.php';

if (!isset($_SESSION['state']) || $_SESSION['state'] !== "true") {
    header("Location:../");
    exit;
}

if (!isset($_FILES['image'])) {
    nextStep("error");
}

$allowedTypes = ['jpg','jpeg','png','gif'];
$maxSize = 4 * 1024 * 1024; // 4MB

$file = $_FILES['image'];
$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
$uploadDir = "../uploads/student_profile/";

if (!file_exists($uploadDir)) {
    try {
        mkdir($uploadDir, 0755, true);
    } catch (Exception $e) {
        nextStep("error");
    }
}

if ($file['error'] !== UPLOAD_ERR_OK) nextStep("error");
if (!in_array($ext, $allowedTypes)) nextStep("file_type_error");
if ($file['size'] > $maxSize) nextStep("file_size_error");

// فایل جدید
$newFileName = uniqid('', true) . '.' . $ext;
$uploadPath = $uploadDir . $newFileName;

// حذف فایل قدیمی
try {
    if (!empty($_SESSION["userInfo"]["profile"]) && file_exists($_SESSION["userInfo"]["profile"])) {
        unlink($_SESSION["userInfo"]["profile"]);
    }
} catch (Exception $e) {
    // اگر حذف نشد، ادامه می‌دیم ولی لاگ نمی‌زنیم
}

// آپلود فایل
try {
    if (!move_uploaded_file($file["tmp_name"], $uploadPath)) {
        nextStep("error");
    }
} catch (Exception $e) {
    nextStep("error");
}

// ذخیره در دیتابیس
try {
    $pdo->beginTransaction();

    $stmt1 = $pdo->prepare("UPDATE honarjoyan SET profile = ? WHERE id = ?");
    $stmt1->execute([$uploadPath, $_SESSION["userInfo"]["id"]]);

    $stmt2 = $pdo->prepare("UPDATE comments SET profile_user = ? WHERE user_id = ?");
    $stmt2->execute([$uploadPath, $_SESSION["userInfo"]["id"]]);

    $pdo->commit();

    $_SESSION["userInfo"]["profile"] = $uploadPath;
    nextStep("success");
} catch (Exception $e) {
    $pdo->rollBack();
    if (file_exists($uploadPath)) {
        try {
            unlink($uploadPath);
        } catch (Exception $e) {
            // اگر حذف نشد، ادامه می‌دیم
        }
    }
    nextStep("error");
}

function nextStep($status) {
    $_SESSION["profileState"] = $status;
    header("Location:change_profile.php");
    exit;
}
?>
