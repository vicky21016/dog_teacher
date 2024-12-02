<?php
require_once("../db_connect.php");

session_start();

$sql = "SELECT  id, name FROM category";
$result = $conn->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM teacher WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
    } else {
        echo "找不到該老師資料";
        exit;
    }
} else {
    echo "無效的id";

    exit;
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>編輯老師</title>
    <?php include("../css.php"); ?>
</head>

<body>
<<<<<<< HEAD
    <?php include("style.php"); ?>
=======
<?php include("style.php"); ?>
>>>>>>> 647c3b0c6d024a4a81fafcffc7a07e721815809e
    <?php
    // 顯示錯誤訊息（警告）
    // if (isset($_SESSION['error'])) {
    // // // echo '<div style="color: red; font-weight: bold; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 15px;">';
    // echo $_SESSION['error'];
    // echo '</div>';
    // unset($_SESSION['error']);  // 顯示後清除錯誤訊息
    // }

    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 16px; line-height: 1.5; z-index: 10;  margin-top:-45px; margin-left: 240px;">';
        echo '<strong>錯誤！</strong> ' . $_SESSION['error'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        unset($_SESSION['error']);  // 顯示後清除錯誤訊息
    }
    if (isset($_SESSION['success'])) {
        echo '<div style="color: green; font-weight: bold; padding: 10px; background-color:#d4edda; border: 1px solid #c3e6cb; border-radius: 5px; z-index: 10; margin-top: -45px; margin-left: 240px;">';
        echo $_SESSION['success'];
        echo '</div>';
        unset($_SESSION['success']);
    }

    ?>


    <div class="container">
        <div class="py-2">
            <a href="List.php" class="btn btn-primary" title="回師資列表"><i class="fa-solid fa-left-long"></i></a>
        </div>
        <h1>編輯老師</h1>
        <form action="doUpdate.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $teacher['id'] ?>">

            <div class="mb-3">
                <label for="name" class="form-label">name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($teacher['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">類別</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="" disabled selected>選擇類別</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $teacher['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="skill" class="form-label">skill</label>
                <textarea class="form-control" id="skill" name="skill" required><?= htmlspecialchars($teacher['skill']) ?></textarea>
                <!-- <input type="text" class="form-control" id="skill" name="skill" value="<?= htmlspecialchars($teacher['skill']) ?>" required> -->
            </div>

            <div class="mb-3">
                <label for="intro" class="form-label">Introduce</label>
                <textarea class="form-control" id="intro" name="intro" required><?= htmlspecialchars($teacher['Introduce']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="exper" class="form-label">Experience</label>
                <textarea class="form-control" id="exper" name="exper" required><?= htmlspecialchars($teacher['Experience']) ?></textarea>
                <!-- <input type="text" class="form-control" id="exper" name="exper" value="<?= htmlspecialchars($teacher['Experience']) ?>" required> -->
            </div>

            <div class="mb-3">
                <label for="myFile" class="form-label">圖片</label>
                <input type="file" class="form-control" id="myFile" name="myFile">
            </div>

            <button type="submit" class="btn btn-primary">保存更改</button>
            <a href="List.php" class="btn btn-secondary">取消更改</a>
        </form>
    </div>
    <script>
        // 當選擇檔案時觸發的事件
        document.getElementById("myFile").addEventListener("change", function(event) {
            // 檢查檔案是否已選擇
            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    // 更新圖片
                    document.getElementById("currentImage").src = e.target.result;
                }
                // 讀取圖片檔案
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>
</body>

</html>