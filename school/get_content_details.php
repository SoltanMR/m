<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../helper/db.php';
include './session/init.php';
include '../persianNumber/persianNumber.php';

try {
    if (!isset($_GET['id'], $_GET['type'])) {
        throw new Exception('پارامترهای لازم ارسال نشده است');
    }

    $id = (int) $_GET['id'];
    $type = $_GET['type'];
    
    if (!in_array($type, ['homework', 'exam'], true)) {
        throw new Exception('نوع محتوای نامعتبر');
    }
    
    $table = ($type === 'homework') ? 'tamrinat' : 'nmonasoal';
    
    $stmt = $pdo->prepare("SELECT id, title, dars, descreption, writer, file_path FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        throw new Exception('محتوا یافت نشد');
    }

    $hasFile = !empty($data['file_path']);
    $hasDescription = !empty($data['descreption']);
    $hasWriter = !empty($data['writer']);
    
    $response = [
        'status' => 'success',
        'data' => [
            'title'          => persianNumber($data['title']),
            'lesson'         => persianNumber($data['dars']),
            'description'    => $hasDescription ? nl2br(htmlspecialchars(persianNumber($data['descreption']))) : '',
            'writer'         => $hasWriter ? htmlspecialchars(persianNumber($data['writer'])) : 'بدون نام',
            'has_file'       => $hasFile,
            'has_description'=> $hasDescription,
            'id'             => $data['id']
        ]
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $e) {
    $errorResponse = [
        'status'  => 'error',
        'message' => $e->getMessage()
    ];
    
    echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
    exit;
}
?>