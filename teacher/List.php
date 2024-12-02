<?php
require_once("../db_connect.php");
session_start();

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

$order1 = 'ASC';
if (isset($_GET['sort']) && $_GET['sort'] == 'desc') {
    $order1 = 'DESC';
}

if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $whereClause = " WHERE teacher.category_id = ?  ";
    $params = [$_GET['category']];
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = "%" . $search . "%";

if (!empty($search)) {
    $whereClause = " WHERE teacher.name LIKE ? ";
    $params[] = $searchTerm;
}

$limit = 10; // 每頁顯示10筆資料
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 頁碼，預設為第1頁
$offset = ($page - 1) * $limit; // 計算偏移量

$order = isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'DESC' : 'ASC';  // 預設排序順序
$allowed_columns = ['id', 'name'];  // 可排序的欄位名稱
$sort_column = isset($_GET['sort_column']) && in_array($_GET['sort_column'], $allowed_columns) ? $_GET['sort_column'] : 'id';  // 預設排序欄位為id


// 計算總筆數
$sqlCount = "SELECT COUNT(*) AS total FROM teacher 
JOIN category ON teacher.category_id = category.id
$whereClause";
$stmtCount = $conn->prepare($sqlCount);
if ($whereClause !== "") {
    $stmtCount->bind_param("i", $params[0]);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalTeachers = $resultCount->fetch_assoc()['total']; // 總筆數
$stmtCount->close();

$totalPages = ceil($totalTeachers / $limit);

$sql = "SELECT teacher.*, category.name AS category_name FROM teacher 
JOIN category ON teacher.category_id = category.id
$whereClause ORDER BY $sort_column $order 
LIMIT ?, ?
";
//$sql = "SELECT * FROM teacher 
//JOIN category ON teacher.category_id = category.id
//$whereClause
//ORDER BY teacher.$sort_column $order
//LIMIT ?, ?";

$stmt = $conn->prepare($sql);

$searchTerm = "%" . $search . "%"; // 設定搜尋關鍵字
if ($whereClause !== "") {
    // 如果有分類過濾條件，綁定相應的參數
    $stmt->bind_param("sii", $searchTerm, $offset, $limit);
} else {
    // 如果沒有分類過濾，直接綁定搜尋關鍵字、偏移量和限制
    $stmt->bind_param("ii", $offset, $limit);
}

$stmt->execute();
$result = $stmt->get_result();
$teacherCount = $result->num_rows;
$teachers = $result->fetch_all(MYSQLI_ASSOC);

// echo "<pre>";
// print_r($_SESSION['success']);
// echo "</pre>";
// exit;

$stmt->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
    <?php include("../css.php"); ?>
</head>

<body>
    <?php include("style.php"); ?>
    <?php
    if (isset($_SESSION['success'])) {
        echo '<div style="color: green; font-weight: bold; padding: 10px; background-color:#d4edda; border: 1px solid #c3e6cb; border-radius: 5px; z-index: 10; margin-top: -45px; margin-left: 240px;">';
        echo $_SESSION['success'];
        echo '</div>';
        unset($_SESSION['success']);
    }
    ?>
    <div class="container">
        <a href="List.php">
            <h1>List</h1>
        </a>
        <div class="d-flex justify-content-between">
            <div class="py-2">
                共 <?= $totalTeachers ?> 位老師，
                目前在第 <?= $page ?> 頁，共 <?= $totalPages ?> 頁。
            </div>

            <div class="py-3 text-end col-md-auto">
                <a href="create-teacher.php" class="btn btn-primary "><i class="fa-solid fa-plus"></i> 新增老師</a>
            </div>
        </div>

        <?php if ($search): ?>
            <a href="List.php" class="btn btn-primary mb-3">
                <i class="fa-solid fa-left-long"></i>
            </a>
        <?php endif; ?>
        <?php if ($teacherCount > 0): ?>
            <div class="table-container">
                <table class='table table-responsive'>
                    <thead>
                        <tr>
                            <th style="cursor: pointer;" onclick="window.location.href='List.php?sort_column=id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&page=<?= $page ?>&search=<?= htmlspecialchars($search) ?>'">序號
                                <?php if ($sort_column == 'id'): ?>
                                    <i class="fa-solid fa-caret-<?= $order === 'DESC' ? 'up' : 'down'; ?>"></i>
                                <?php endif; ?>
                            </th>
                            <th>照片</th>
                            <th style="cursor: pointer;" onclick="window.location.href='List.php?sort_column=name&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&page=<?= $page ?>&search=<?= htmlspecialchars($search) ?>'">姓名
                                <?php if ($sort_column == 'name'): ?>
                                    <i class="fa-solid fa-caret-<?= $order === 'DESC' ? 'up' : 'down'; ?>"></i>
                                <?php endif; ?>
                            </th>

                            <th>類別</th>
                            <th>專長</th>
                            <th>介紹</th>
                            <th>經驗</th>
                            <th>其他</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td><?= htmlspecialchars($teacher['id']) ?></td>
                                <td>
                                    <a href="teacher.php?id=<?= $teacher["id"] ?>"><img src="../img/<?= htmlspecialchars($teacher['img']) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>"
                                            class="img-thumbnail"></a>
                                </td>
                                <td><a href="teacher.php?id=<?= $teacher["id"] ?>"><?= htmlspecialchars($teacher['name']) ?></a></td>
                                <td>
                                    <?= htmlspecialchars($teacher['category_name']) ?>
                                </td>
                                <td>
                                    <div class="scrollable-cell"><?= htmlspecialchars($teacher['skill']) ?></div>
                                </td>
                                <td>
                                    <div class="scrollable-cell"><?= htmlspecialchars($teacher['Introduce']) ?></div>
                                </td>
                                <td>
                                    <div class="scrollable-cell"><?= htmlspecialchars($teacher['Experience']) ?></div>
                                </td>
                                <td>
                                    <a href="teacher.php?id=<?= $teacher['id'] ?>" class="btn btn-success btn-sm mb-3">
                                        <i class="fa-regular fa-eye fa-fw"></i>
                                    </a>
                                    <a href="doEdit.php?id=<?= $teacher['id'] ?>" class="btn btn-warning btn-sm mb-3">
                                        <i class="fa-solid fa-pen-to-square fa-fw"> </i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="<?= $teacher['id'] ?>">
                                        <i class="fa-solid fa-trash fa-fw"> </i>
                                    </button>
                                    <div class="modal fade" id="exampleModal" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">刪除確認</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    確定要刪除這位老師嗎？
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">取消</button>
                                                    <a id="deleteButton" href="#" class="btn btn-danger">確認刪除</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center my-4">
                <?php if ($page > 1): ?>
                    <a href="?page=1&search=<?= htmlspecialchars($search) ?>& category=<?= htmlspecialchars($_GET['category'] ?? '') ?>" class="btn btn-primary me-2">第一頁</a>
                    <a href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ?? '') ?>" class="btn btn-primary me-2">上一頁 </a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ?? '') ?>" class="btn <?= $i == $page ? 'btn-secondary' : 'btn-outline-primary' ?> 
me-2"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ?? '') ?>" class="btn btn-primary me-2">下一頁 </a>
                    <a href="?page=<?= $totalPages ?>&search=<?= htmlspecialchars($search) ?>&category=<?= htmlspecialchars($_GET['category'] ?? '') ?>" class="btn btn-primary">最後 一頁</a>
                <?php endif; ?>
            </div>
    </div>
<?php else:
?>
    <p>找不到符合條件的老師。</p>
<?php endif;
?>

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