<?php
session_start();
require_once __DIR__ . '../../../helper/db.php';
include '../school_site/login_state.php';

try {
    // بررسی ورود کاربر
    if (!isset($_SESSION['userInfo'])) {
        throw new Exception("لطفاً ابتدا وارد شوید.");
    }

    // بررسی وجود شناسه فایل
    if (!isset($_GET['id'])) {
        throw new Exception("فایل مشخص نشده است.");
    }

    $fileId = intval($_GET['id']);

    // گرفتن مسیر فایل از دیتابیس
    $stmt = $pdo->prepare("SELECT file_path FROM tamrinat WHERE id = ?");
    $stmt->execute([$fileId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("فایل یافت نشد.");
    }

    $fileName = basename($row['file_path']);

    // مسیر امن پوشه آپلود
    $uploadsDir = realpath(__DIR__ . '/../../uploads/tamrinat/');
    $filePath = realpath($uploadsDir . '/' . $fileName);

    // بررسی وجود فایل و امنیت مسیر
    if (!$filePath || strpos($filePath, $uploadsDir) !== 0 || !file_exists($filePath)) {
        throw new Exception("فایل موردنظر یافت نشد یا موجود نیست.");
    }

    // ارسال هدرهای دانلود
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    // خواندن و ارسال فایل
    flush();
    readfile($filePath);
    exit;

} catch (Exception $e) {
    echo '<div style="
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 15px;
        border-radius: 5px;
        text-align: center;
        margin: 20px auto;
        max-width: 600px;
        font-family: sans-serif;
    ">';
    echo htmlspecialchars($e->getMessage());
    echo '</div>';
    exit;
}
