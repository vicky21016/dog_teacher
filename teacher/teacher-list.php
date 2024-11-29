<?php
require_once("../db_connect.php");
// session_start();

$cateSql = "SELECT * FROM category";
$resultCate = $conn->query($cateSql);
$categories = $resultCate->fetch_all(MYSQLI_ASSOC);


$categoryArr = [];
foreach ($categories as $category) {
    $categoryArr[$category["id"]] = $category["name"];
}

$title = "Teacher List";
$whereClause = "";
$params = [];

if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $whereClause = " WHERE teacher.category_id = ?";
    $params = [$_GET['category']];
}

$sql = "SELECT teacher.*, category.name AS category_name FROM teacher 
JOIN category ON teacher.category_id = category.id
$whereClause
";

$stmt = $conn->prepare($sql);

if ($whereClause !== "") {
    $stmt->bind_param("i", $params[0]);
}

$stmt->execute();
$result = $stmt->get_result();
$teacherCount = $result->num_rows;
$teachers = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>
<!doctype html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css.php"); ?>
</head>

<body>
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div style="color: green; font-weight: bold; padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 15px;">';
        echo $_SESSION['success'];
        echo '</div>';
        unset($_SESSION['success']);
    }
    ?>
    <!-- <?php include("../header.php") ?> -->
    <div class="container">
        <ul class="nav nav-underline mb-3">
            <li class="nav-item">
                <a class="nav-link <?php if (!isset($_GET["category"])) echo "active" ?>" aria-current="page" href="teacher-list.php">全部</a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li class="nav-item">
                    <a class="nav-link <?php
                                        if (isset($_GET["category"]) && $_GET["category"] == $category["id"]) echo "active";
                                        ?>" href="teacher-list.php?category=<?= $category["id"] ?>">
                        <?= $category["name"] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <h1><?= $title ?></h1>
        <form action="search.php" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="搜尋老師" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i></button>
            </div>
        </form>
        <div class="py-2">共計 <?= $teacherCount ?> 位老師</div>
        <a href="create-teacher.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> 新增老師</a>
        <div class="row row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3 mt-2 mb-3">
            <?php foreach ($teachers as $teacher): ?>
                <div class="col">
                    <div class="">
                        <a href="teacher.php?id=<?= $teacher["id"] ?>">
                            <figure class="ratio ratio-4x3 rounded overflow-hidden">
                                <img class="object-fit-cover" src="/img/<?= $teacher["img"] ?>" alt="<?= $teacher["name"] ?>">
                            </figure>
                        </a>
                        <div><a href="teacher-list.php?category=<?= $teacher["category_id"] ?>"><?= $teacher["category_name"] ?></a></div>
                        <h3>
                            <a href="teacher.php?id=<?= $teacher["id"] ?>">
                                <?= $teacher["name"] ?>
                            </a>
                            <a href="doEdit.php?id=<?= $teacher["id"] ?>" class="btn btn-success btn-sm ms-2">
                                <i class="fa-solid fa-pencil-alt"></i>編輯
                            </a>
                            <button  type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="fa-solid fa-trash"></i> 刪除
                            </button>
                            <!-- 刪除確認 Modal -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">確認刪除</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            確定要刪除 <?= $teacher['name'] ?> 老師嗎？
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                            <a href="doDelete.php?id=<?= $teacher['id'] ?>" class="btn btn-danger">確認刪除</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
<script>
    <?php include("../js.php");?>
</script>

</html>