<?php
require_once("../db_connect.php");

if (!isset($_GET["id"])) {
    header("location: List.php");
}

$cateSql = "SELECT * FROM category";
$resultCate = $conn->query($cateSql);
$categories = $resultCate->fetch_all(MYSQLI_ASSOC);

$categoryArr = [];
foreach ($categories as $category) {
    $categoryArr[$category["id"]] = $category["name"];
}

$id = $_GET["id"];
$sql = "SELECT * FROM teacher WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();



// compact("categories", "categoryArr", "row")

?>
<!doctype html>
<html lang="zh-Hant">

<head>
    <title>老師詳細資料</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include("../css.php") ?>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .container {
            background: #fff;
            border-radius: 8px;
<<<<<<< HEAD
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* max-height: 900px; */
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

=======
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
>>>>>>> 647c3b0c6d024a4a81fafcffc7a07e721815809e
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
<<<<<<< HEAD
            height: 100%;
=======
            height: 100%; 
>>>>>>> 647c3b0c6d024a4a81fafcffc7a07e721815809e
        }

        .teacher-image {
            width: 100%;
            max-width: 450px;
            height: 600px;
            border-radius: 8px;
            object-fit: cover;
        }

        .teacher-info h3 {
            font-size: 1.2rem;
            color: #333;
            margin-top: 15px;
            font-weight: bold;
        }

        .teacher-info p {
            color: #555;
            line-height: 1.5;
        }

        .btn {
            border-radius: 5px;
        }
    </style>
</head>

<body>
<<<<<<< HEAD
    <?php include("style.php"); ?>
=======
<?php include("style.php"); ?>
>>>>>>> 647c3b0c6d024a4a81fafcffc7a07e721815809e
    <div class="container mt-5">
        <div class="py-2">
            <a href="List.php" class="btn btn-primary" title="回師資列表">
                <i class="fa-solid fa-left-long"></i>
            </a>
        </div>

<<<<<<< HEAD
        <div class="row ">
=======
        <div class="row">
>>>>>>> 647c3b0c6d024a4a81fafcffc7a07e721815809e
            <?php if ($result->num_rows > 0): ?>
                <!-- 圖片區 -->
                <div class="col-md-6 image-container">
                    <img class="teacher-image" src="../img/<?= htmlspecialchars($row['img']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                </div>

                <!-- 資訊區 -->
<<<<<<< HEAD
                <div class="col-md-6 ">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1><?= htmlspecialchars($row['name']) ?></h1>
                        <div class="header-buttons mt-2">
                            <a href="doEdit.php?id=<?= $row['id'] ?>" class="btn btn-warning">
=======
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1><?= htmlspecialchars($row['name']) ?></h1>
                        <div class="header-buttons mt-2">
                            <a href="doEdit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
>>>>>>> 647c3b0c6d024a4a81fafcffc7a07e721815809e
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </div>
                    <a class="category-link" href="List.php?category=<?= $row['category_id'] ?>">
                        <?= htmlspecialchars($categoryArr[$row['category_id']]) ?>
                    </a>

                    <div class="teacher-info mt-4">
                        <h3>簡介</h3>
                        <p><?= nl2br(htmlspecialchars($row['Introduce'])) ?></p>

                        <h3>專長</h3>
                        <p><?= nl2br(htmlspecialchars($row['skill'])) ?></p>

                        <h3>經歷</h3>
                        <p><?= nl2br(htmlspecialchars($row['Experience'])) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-5">老師資料不存在。</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 刪除確認模態框 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">刪除確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    確定要刪除這位老師嗎？
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a id="deleteButton" href="#" class="btn btn-danger">確認刪除</a>
                </div>
            </div>
        </div>
    </div>

    <?php include("../js.php") ?>
    <script>
        // 設定刪除按鈕的連結
        const deleteButton = document.getElementById('deleteButton');
        const modal = document.getElementById('exampleModal');

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const teacherId = button.getAttribute('data-id');
            deleteButton.href = 'doDelete.php?id=' + teacherId;
        });
    </script>
</body>

</html>