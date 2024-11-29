<?php
require_once("../db_connect.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $skill = $_POST['skill'];
    $intro = $_POST['intro'];
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
        $extension = pathinfo($_FILES["myFile"]["name"],PATHINFO_EXTENSION );
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'jfif', 'webp'];

        if (!in_array(strtolower($extension), $allowed_extensions)) {
            $_SESSION['error'] ="不允許的檔案格式。請選擇 JPG, JPEG, PNG, JFIF 或 WEBP 格式的圖片。";
            header("Location: doEdit.php?id=$id");
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
    // 圖片處理

    if ($imageName) {
        $sql = "UPDATE teacher SET name = ?, category_id=?, skill = ?, Introduce = ?, Experience = ?, img = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $name, $category_id , $skill, $intro, $exper, $imageName, $id);
    } else {
        // 若沒有新圖片，則不更改圖片欄位
        $sql = "UPDATE teacher SET name = ?, category_id=?, skill = ?, Introduce = ?, Experience = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $name, $category_id, $skill, $intro, $exper, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "更新成功!";
        header("Location: List.php");
        exit;
    } else {
        $_SESSION['error'] = "更新失敗: " . $stmt->error;
        header("Location: doEdit.php?id=$id");
        exit;
    }

    $stmt->close();
}
$conn->close();
