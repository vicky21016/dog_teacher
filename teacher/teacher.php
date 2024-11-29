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
<html lang="en">

<head>
    <title>老師詳細資料</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php") ?>
    <style>
        .images {
            max-width: 100% ;
            width: 500px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="py-2">
            <a href="List.php" class="btn btn-primary" title="回師資列表"><i class="fa-solid fa-left-long"></i></a>
        </div>
        <ul class="nav nav-underline mb-3">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="List.php">全部</a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li class="nav-item">
                    <a class="nav-link 
                    <?php if ($category["id"] == $row["category_id"]) echo "active"; ?>" href="List.php?category=<?= $category["id"] ?>"><?= $category["name"] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="py-2">
            <div class="row g-3">
                <?php if ($result->num_rows > 0): ?>
                    <div class="col-lg-6">
                        <img class="img-fluid images" src="../img/<?= $row["img"] ?>" alt="<?= $row["name"] ?>">
                    </div>
                    <div class="col-lg-6">

                        <div class="d-flex justify-content-between">

                            <a href="List.php?category=<?= $row["category_id"] ?>">
                                <?= $categoryArr[$row["category_id"]] ?>
                            </a>

                            <div class="ms-auto">
                                <a href="doEdit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mb-3">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?= $row['id'] ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">刪除確認</h1>
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


                        <h1><?= $row["name"] ?></h1>
                        <div>
                            <h3>簡介：</h3>
                            <p><?= nl2br(htmlspecialchars($row["Introduce"])) ?></p>
                        </div>
                        <div>
                            <h3>專長：</h3>
                            <p><?= nl2br(htmlspecialchars($row["skill"])) ?></p>
                        </div>
                        <div>
                            <h3>經歷：</h3>
                            <p><?= nl2br(htmlspecialchars($row["Experience"])) ?></p>
                        </div>
                    </div>

                <?php else: ?>
                    <p>老師資料不存在。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    <script>
        // 設定刪除按鈕的連結
        const deleteButton = document.getElementById('deleteButton');
        const modal = document.getElementById('exampleModal');
        // 當打開模態框時，設置刪除的連結
        modal.addEventListener('show.bs.modal', function(event) {
            // 取得點擊刪除按鈕時的 teacher id
            const button = event.relatedTarget; // 按鈕
            const teacherId = button.getAttribute('data-id'); // 取得 data-id 屬性
            deleteButton.href = 'doDelete.php?id=' + teacherId; // 設定刪除連結
        });
    </script>
</body>

</html>