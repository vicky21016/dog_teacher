<?php
require_once("../db_connect.php");

session_start();

$name = $_POST["name"];
$skill = $_POST["skill"];
$intro = $_POST["intro"];
$exper = $_POST['exper'];
$category_id = $_POST['category_id'];

if (empty($name) || empty($skill) || empty($intro) || empty($exper) || empty($category_id)) {
    $_SESSION['error'] = "所有欄位都必須填寫！";
    header("Location: create-teacher.php");
    exit;
}

$imageName = null; 
if (isset($_FILES["myFile"]) && $_FILES["myFile"]["error"] == 0) {
    // 獲取檔案副檔名
    $extension = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'jfif', 'webp'];

    if (!in_array(strtolower($extension), $allowed_extensions)) {
        $_SESSION['error'] ="不允許的檔案格式。請選擇 JPG, JPEG, PNG, JFIF 或 WEBP 格式的圖片。";
        header("Location: create-teacher.php");
        exit;
    }
    $imageName  = uniqid() . '.' . $extension;

    $upload_dir = '../img/'; 

    // 確保目錄存在
    if (!is_dir($upload_dir)) {
        //mkdir($upload_dir, 0777, true);
    }

    // 移動檔案
    if (!move_uploaded_file($_FILES["myFile"]["tmp_name"], $upload_dir . $imageName)) {
        $_SESSION['error'] = "圖片上傳失敗，請再試一次。";
        header("Location: create-teacher.php");
        exit;
    }

}

$sql = "INSERT INTO teacher (name, skill, Introduce, Experience, img, category_id)
	VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('SQL prepare error: ' . $conn->error);
}

$stmt->bind_param("sssssi", $name, $skill, $intro, $exper, $imageName, $category_id);
if ($stmt->execute()) {
    $_SESSION['success'] = "新增老師成功！";
    // echo "<pre>";
    // print_r($_SESSION['success']);
    // echo "</pre>";
    header("Location: List.php?id=$id");
    exit;
} else {
    $_SESSION['error'] = "新增老師失敗: " . $stmt->error;
    header("Location: create-teacher.php?id=$id");
    exit;
}

$stmt->close();
$conn->close();

