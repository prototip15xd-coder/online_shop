<?php


if (isset($_SESSION['userid'])) {
    $pdo = new PDO('pgsql:host=postgres;port=5432;dbname=mydb', 'USER', 'PASS');
    $stms = $pdo->query('SELECT * FROM products');
    $products = $stms->fetchAll();
    require_once '../Views/catalog.php';
} else {
    header("Location: /login");

}

?>