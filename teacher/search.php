<?php
require_once("../db_connect.php");

session_start();

// 取得所有類別
$cateSql = "SELECT * FROM category";
$resultCate = $conn->query($cateSql);
$categories = $resultCate->fetch_all(MYSQLI_ASSOC);

$categoryArr = [];
foreach ($categories as $category) {
    $categoryArr[$category["id"]] = $category["name"];
}

// 取得搜尋條件
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = $conn->real_escape_string($search);

// 設定排序順序
$order = 'ASC';
if (isset($_GET['sort']) && $_GET['sort'] == 'desc') {
    $order = 'DESC';
}

$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page; // 防止頁數小於1
$offset = ($page - 1) * $itemsPerPage;

$sqlCount = "SELECT COUNT(*) AS total FROM teacher 
             LEFT JOIN category ON teacher.category_id = category.id
             WHERE teacher.name LIKE ? OR teacher.skill LIKE ? OR teacher.Introduce LIKE ? OR teacher.id LIKE ?";
$stmtCount = $conn->prepare($sqlCount);
$searchTerm = "%" . $search . "%";
$stmtCount->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalTeachers = $resultCount->fetch_assoc()['total'];
$totalPages = ceil($totalTeachers / $itemsPerPage);

// 構建 SQL 查詢語句，並執行
$sql = "SELECT teacher.*, category.name AS category_name 
            FROM teacher
            LEFT JOIN category ON teacher.category_id = category.id
            WHERE teacher.name LIKE ? OR teacher.skill LIKE ? OR teacher.Introduce LIKE ? OR teacher.id LIKE ?
            ORDER BY teacher.id $order
            LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

// 以 "%" 包圍搜尋字串，實現模糊查詢
//$searchTerm = "%" . $search . "%";
$stmt->bind_param("ssssii", $searchTerm, $searchTerm, $searchTerm, $searchTerm,  $itemsPerPage, $offset);
$stmt->execute();

$result = $stmt->get_result();
$teacherCount = $result->num_rows;
$teachers = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜尋結果</title>
    <?php include("../css.php"); ?>
</head>

<body>
    <div class="container">
        <!-- 顯示類別選單 -->
        <ul class="nav nav-underline mb-3">
            <li class="nav-item">
                <a class="nav-link <?php if (!isset($_GET["category"])) echo "active" ?>" aria-current="page" href="List.php">全部</a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if (isset($_GET["category"]) && $_GET["category"] == $category["id"]) echo "active"; ?>" href="List.php?category=<?= $category["id"] ?>">
                        <?= $category["name"] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h1>搜尋結果</h1>

        <!-- 返回列表頁的按鈕 -->
        <a href="List.php" class="btn btn-primary mb-4"><i class="fa-solid fa-left-long"></i></a>
        <div class="py-2">
            共 <?= $teacherCount ?> 位老師符合搜尋條件。
        </div>
        <!-- 搜尋表單 -->
        <form action="search.php" method="GET" class="mb-4">
            <div class="input-group">
                <input type="search" class="form-control" name="search" placeholder="搜尋" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i></button>
            </div>
        </form>

        <!-- 排序按鈕 -->
        <div class="btn-group mb-3" role="group">
            <a href="search.php?search=<?= htmlspecialchars($search) ?>&sort=asc" class="btn btn-primary"><i class="fa-solid fa-arrow-down-1-9"></i> </a>
            <a href="search.php?search=<?= htmlspecialchars($search) ?>&sort=desc" class="btn btn-primary"><i class="fa-solid fa-arrow-up-9-1"></i></a>
        </div>

        <!-- 顯示搜尋結果 -->
        <?php
        if ($teacherCount > 0) {
            echo "<div class='table-container'><table class='table'>";
            echo "<thead><tr><th>序號</th><th>老師照片</th><th>姓名</th><th>類別</th><th>專長</th><th>介紹</th><th>經驗</th><th>操作</th></tr></thead>";
            echo "<tbody>";
            foreach ($teachers as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td><a href='teacher.php?id=" . $row['id'] . "'><img src='../img/" . htmlspecialchars($row['img']) . "' alt='" . htmlspecialchars($row['name']) . "' class='img-thumbnail' width='4000'></a></td>";
                echo "<td><a href='teacher.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</a></td>";
                echo "<td><a href='List.php?category=" . $row['category_id'] . "'>" . htmlspecialchars($row['category_name']) . "</a></td>";
                echo "<td><div class='scrollable-cell'>" . htmlspecialchars($row['skill']) . "</div></td>";
                echo "<td><div class='scrollable-cell'>" . htmlspecialchars($row['Introduce']) . "</div></td>";
                echo "<td><div class='scrollable-cell'>" . htmlspecialchars($row['Experience']) . "</div></td>";
                echo "<td>
                        <a href='teacher.php?id=" . $row['id'] . "' class='btn btn-success btn-sm mb-2'> <i class='fa-regular fa-eye fa-fw'></i> 檢視</a>
                        <a href='doEdit.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm mb-2'><i class='fa-solid fa-pen-to-square fa-fw'></i> 編輯</a>
                        <a href='doDelete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'><i class='fa-solid fa-trash fa-fw'> </i> 刪除</a>
                    </td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table></div>";
        } else {
            echo "<p>找不到符合條件的老師。</p>";
        }
        ?>
        <div class="d-flex justify-content-center my-4">
            <?php if ($page > 1): ?>
                <a href="?page=1&search=<?= htmlspecialchars($search) ?>& category=<?= htmlspecialchars($_GET['category'] ?? '') ?>"
                    class="btn btn-primary me-2">第一頁</a>
                <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ??'') ?>" class="btn btn-primary me-2">上一頁 </a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ?? '') ?>"
                    class="btn <?= $i == $page ? 'btn-secondary' : 'btn-outline-primary' ?> me-2"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ??'') ?>" class="btn btn-primary me-2">下一頁 </a>
                <a href="?page=<?= $totalPages ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ??'') ?>" class="btn btn-primary">最後 一頁</a>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>

</html>