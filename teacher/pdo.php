<?php
require_once("../db_connect.php");
$limit = 10;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$start = ($p - 1) * $limit;


$order = isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'DESC' : 'ASC';
$allowed_columns = ['id', 'account', 'name'];
$sort_column = isset($_GET['sort_column']) && in_array($_GET['sort_column'], $allowed_columns) ? $_GET['sort_column'] : 'id';


$search = isset($_GET['search']) && $_GET['search'] ? $_GET['search'] : '';

$sql = "SELECT * from users where is_deleted=0 and name LIKE '%$search%' ORDER BY $sort_column $order limit $start,$limit ";
$stmt = $db_host->prepare($sql);
try {
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countSql = "SELECT * from users where is_deleted=0 and name LIKE '%$search%'";
    $countStmt = $db_host->prepare($countSql);
    $countStmt->execute();
    $total_results = $countStmt->rowCount();

    $rowCount = $stmt->rowCount();
} catch (PDOException $e) {
    echo "預處理陳述式執行失敗！ <br/>";
    echo "Error: " . $e->getMessage() . "<br/>";
    $db_host = NULL;
    exit;
}

$total_page = ceil($total_results / $limit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users</title>
    <?php include("../css.php") ?>
</head>

<body>

    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="py-4">
            <?php if (isset($_GET['search']) && !empty($search)): ?>
                <div class="py-1">
                    <a href="pdo-users.php" class="btn btn-primary" title="回到使用者列表"><i class="fa-solid fa-left-long"></i></a>
                </div>
            <?php endif; ?>
                <div class="">
                共<?= $rowCount ?>使用者
                </div>
              
            </div>
            
            <div class="py-3 text-end col-md-auto">
                <a href="pdo-create-user.php" class="btn btn-info" title="新增使用者"><i class="fa-solid fa-user-plus"></i></a>
            </div>


        </div>
    </div>
    <div class="container">
        <div class="py-2 ">
            <form action="" method="get">
                <div class="input-group">
                    <input type="search" value="<?= $_GET['search'] ?? "" ?>" class="form-control" name="search">
                    <button class="btn-primary btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>
        <table class="table table-bordered " >
            <thead class="">
                <tr class="">
                    <th class="text-danger bg-light " onclick="window.location.href='?sort_column=id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&p=<?= $p ?>&search=<?= $search ?>'">ID
                        <?php if ($sort_column == 'id'): ?>
                            <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'id' ? 'up' : 'down'; ?>"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="window.location.href='?sort_column=account&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&p=<?= $p ?>&search=<?= $search ?>'">帳號
                        <?php if ($sort_column == 'account'): ?>
                            <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'account' ? 'up' : 'down'; ?>"></i>
                        <?php endif; ?>
                    </th>
                    <th onclick="window.location.href='?sort_column=name&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>&p=<?= $p ?>&search=<?= $search ?>'">姓名
                        <?php if ($sort_column == 'name'): ?>
                            <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'name' ? 'up' : 'down'; ?>"></i>
                        <?php endif; ?>
                    </th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><a href="pdo-user.php?id=<?= $row['id'] ?>"><?= $row['account'] ?></a></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($total_page >= 1): ?>
            <nav class="me-2">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                        <li class="page-item <?php if ($i == $p) echo 'active'; ?>">
                            <a class="page-link" href="pdo-users.php?sort_column=<?= $sort_column ?>&order=<?= $order ?>&p=<?= $i ?>&search=<?= $search ?>"><?= $i; ?></a>

                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
    </div>


</body>

</html>