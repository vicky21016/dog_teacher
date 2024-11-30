<?php
$limit = 10;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$start = ($p - 1) * $limit;

$order = isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'DESC' : 'ASC'; //不用動
$allowed_columns = ['id', 'account', 'name']; //內容改你的類別 記得跟下面一樣
$sort_column = isset($_GET['sort_column']) && in_array($_GET['sort_column'], $allowed_columns) ? $_GET['sort_column'] : 'id';//不需要改 除非你要的第一個排序不是id

//                  你的資料庫↓    這個是搜尋的關鍵字↓                              ↓這兩個不用動↓           
$sql = "SELECT * from users where is_deleted=0 and name LIKE '%$search%' ORDER BY $sort_column $order limit $start,$limit ";
//                                           關鍵字類型可以設定多個 以上面得方式 做陣列
?>
<!--                                                                                      你要更改的排序類別↓ -->
<th class="text-danger " style="width: 80px; cursor: pointer;" onclick="window.location.href='?sort_column=id&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&p=<?= $p ?>&search=<?= $search ?>'">ID
    <!--               也要更改 ↓ -->
    <?php if ($sort_column == 'id'): ?>
        <!--                                                            這個也要↓ -->
        <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'id' ? 'up' : 'down'; ?>"></i>
    <?php endif; ?>

    <!--                                                                            你的第二個類別↓ -->
<th style="width: 200px; cursor: pointer;" onclick="window.location.href='?sort_column=account&order=<?= $order === 'ASC' ? 'DESC' : 'ASC'; ?>&p=<?= $p ?>&search=<?= $search ?>'">帳號
    <!--                以此類推↓ -->
    <?php if ($sort_column == 'account'): ?>
 <!--                                                                     以此類推x2↓ -->
        <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'account' ? 'up' : 'down'; ?>"></i>
    <?php endif; ?>
</th>
<!-- 這是第三個                                                       你要更改的排序類別↓  -->
<th style="width: 120px; cursor: pointer;" onclick="window.location.href='?sort_column=name&order=<?= $order === 'ASC' ? 'DESC' : 'ASC' ?>&p=<?= $p ?>&search=<?= $search ?>'">姓名
    <!--                        ↓↓-->
    <?php if ($sort_column == 'name'): ?>
        <!--                                                                     ↓↓ -->
        <i class="fa-solid fa-caret-<?= $order === 'DESC' && $sort_column === 'name' ? 'up' : 'down'; ?>"></i>
    <?php endif; ?>
</th>

















