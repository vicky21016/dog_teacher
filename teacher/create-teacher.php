<?php
require_once("../db_connect.php");

session_start();

$sql = "SELECT * FROM teacher ORDER BY id DESC";
$sql = "SELECT  id, name FROM category";
$result = $conn->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Create Teacher</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php") ?>
</head>

<body>
    <?php include("style.php"); ?>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 16px; line-height: 1.5; z-index: 10; margin-top: -45px; margin-left: 240px;">';
        echo '<strong>錯誤！</strong> ' . $_SESSION['error'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['error']);  // 顯示後清除錯誤訊息
    }

    ?>
    <div class="container">
        <div class="py-2">
            <a href="List.php" class="btn btn-primary" title="回師資列表"><i class="fa-solid fa-left-long"></i></a>
        </div>
        <h1>新增老師</h1>
        <form action="doCreateTeacher.php" method="post" enctype="multipart/form-data">
            <div class="mb-2">
                <label for="" class="form-label">姓名</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-2">
                <label for="category">類別</label>
                <select class="form-control" name="category_id" id="category_id" required>
                    <option value="" disabled selected>選擇類別</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <div class="mb-2">
                    <label for="" class="form-label">專長</label>
                    <textarea class="form-control" id="skill" name="skill" required></textarea>
                    <!-- <input type="text" class="form-control" name="skill"> -->
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">簡介</label>
                    <textarea class="form-control" id="intro" name="intro" required></textarea>
                    <!-- <input type="text" class="form-control" name="intro"> -->
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">經歷</label>
                    <textarea class="form-control" id="exper" name="exper" required></textarea>
                    <!-- <input type="text" class="form-control" name="exper"> -->
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">選擇圖片檔案</label>
                    <input type="file" class="form-control" name="myFile" accept="image/*" required>
                </div>
                <button class="btn btn-primary" type="submit">送出</button>
                <a href="List.php" class="btn btn-secondary">取消</a>
        </form>
    </div>


</body>

</html>