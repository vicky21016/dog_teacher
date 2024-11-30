<?php
include("../css.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
    <style>
        .main-header {
            height: 60px;
        }

     
    </style>
</head>

<body>
    
    <header class="main-header d-flex bg-dark justify-content-between">
    <a class="link-light dashboard-logo bg-black text-decoration-none  pb-2 pt-2 ps-4 shadow" href="">標題</a>
        <form action="" method="get">
            <div class="input-group mt-2" style="position: relative; right:500px">
                <input type="search" value="<?= $_GET['search'] ?? "" ?>" class="form-control mx-auto" name="search">
                <button class="btn-primary btn mx-auto" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
    </header>


</body>

</html>