<?php
$user = 'test';
$pass = '';
$dsn = 'mysql:dbname=mini_bbs;host=localhost;charset=utf8mb4';
//
try {
    $db = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "connect error!! (" , $e->getMessage() , ")";
    return ;
}
