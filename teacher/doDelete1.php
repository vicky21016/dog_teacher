<?php
require_once("../db_connect.php");
session_start();

$id=$_GET["id"];
$sql="DELETE FROM teacher WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] =  "刪除成功";
    header("Location: List.php?id=$id");
    exit;
} else {
    $_SESSION['error'] = "刪除失敗，請再試一次";
    exit;
}
$stmt->close();
$conn->close();

//header("Location: List.php");
// exit;
?>