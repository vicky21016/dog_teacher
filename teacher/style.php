<?php
include("../css.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ladydog</title>
    <style>
        :root {
            --aside-width: 240px;
            --hight-header: 46px;
        }

        .dashboard-logo {
            width: var(--aside-width);
        }

        .dasgboard-aside {
            width: var(--aside-width);
            padding-top: var(--hight-header);
        }

        .main-content {
            margin-left: var(--aside-width);
            margin-top: var(--hight-header);
        }
    </style>
</head>

<body>
    <?php include ("../header1.php")?>
    <aside class="dasgboard-aside position-fixed vh-100 bg-gray border-end overflow-auto ">
        <ul class="list-unstyled">
            <li><a class="py-2 px-3 text-decoration-none d-block" href="">
            <i class="fa-solid fa-house pe-2"></i>Dashboard
                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href="pdo-users.php">
                    <i class="fa-regular fa-file ps-1 pe-2"></i> users
                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href="">
                    <i class="fa-solid fa-cart-shopping pe-2"></i>product

                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href="">
                    <i class="fa-solid fa-user pe-2 ps-1"></i>course
                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href="javascript:void(0);" onclick="loadPage('List')">
                    <i class="fa-solid fa-user pe-2 ps-1"></i>teacher
                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href="">
                    <i class="fa-solid fa-user pe-2 ps-1"></i>article
                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href=""><i
                        class="fa-solid fa-arrow-trend-up pe-2"></i>coupon
                </a>
            </li>
            <li><a class="py-2 px-3 text-decoration-none d-block" href="">
                    <i class="fa-solid fa-puzzle-piece pe-2"></i>Order
                </a>
            </li>
        </ul>

        <hr>
        <ul class="list-unstyled">
       
            <li><a class="py-2 px-3 text-decoration-none d-block" href="">
                    <i class="fa-solid fa-right-from-bracket pe-2"></i>Sign Up
                </a>
        </ul>
    </aside>
    <main class="main-content">
    <?php //include("../smell-project/pdo-users.php") ?>
    <?php //include("List.php"); ?>
    <div id="content-area">
            <!-- Initially, you can show a default loading message or content -->
            <div class="loading">Loading content...</div>
        </div>
    </main>



    <?php include("../js.php"); ?>
    <script>
        // 使用 AJAX 加載頁面內容
        function loadPage(page) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', page + '.php', true);  // 設定請求，根據頁面名稱加載對應的 PHP 檔案
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // 將返回的內容插入到 #content-area 中
                    document.getElementById('content-area').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
        
    </script>
</body>

</html>