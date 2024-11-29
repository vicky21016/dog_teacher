<?php
// php 連接資料庫
$servername = "localhost";
$username = "dogadmin";
$password = "helloworld";
$dbname = "dog_db";

// Create connection
$conn = new mysqli($servername, $username, 
$password, $dbname);
// 檢查連線
if ($conn->connect_error) {
  	die("連線失敗: " . $conn->connect_error);
}else{
    // echo "連線成功";
}
// exit;
// session_start();
?>