<?php
require_once("../db_connect.php");
session_start();

$id = $_GET["id"];  // 獲取傳入的教師ID

// 設定軟刪除 SQL 語句
$sql = "UPDATE teacher SET is_deleted = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

// 執行語句並檢查結果
if ($stmt->execute()) {
    $_SESSION['success'] = "刪除成功";  // 設定成功訊息
    header("Location: List.php");  // 跳回老師列表頁面
    exit;
} else {
    $_SESSION['error'] = "刪除失敗，請再試一次";  // 設定錯誤訊息
    exit;
}

$stmt->close();
$conn->close();
