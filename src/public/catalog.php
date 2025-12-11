<?php

if (!isset($_COOKIE["userid"])) {
    header("Location: /src/public/login_page.php");
}

$pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
$stms = $pdo->query('SELECT * FROM products');
$products = $stms->fetchAll();


require_once './catalog_page.php';
?>