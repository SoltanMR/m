<?php
session_start();
require_once __DIR__ . '../../../helper/db.php';
include '../school_site/login_state.php';

try {
    if (!isset($_SESSION['userInfo'])) {
        throw new Exception("لطفاً ابتدا وارد شوید.");
    }

    if (!isset($_GET['id'])) {
        throw new Exception("فایل مشخص نشده است.");
    }

    $fileId = intval($_GET['id']);

    $stmt = $pdo->prepare("SELECT file_path FROM nmonasoal WHERE id = ?");
    $stmt->execute([$fileId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("فایل پیدا نشد.");
    }

    $fileName = basename($row['file_path']);
    $filePath = realpath(__DIR__ . '/../../uploads/nmonasoal/' . $fileName);

    if (!$filePath || !file_exists($filePath)) {
        throw new Exception("فایل وجود ندارد در مسیر: " . $filePath);
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

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