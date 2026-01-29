<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
</head>

<body>
<?php require_once '../Controllers/CartController.php'; ?>
<div class="container">
    <a href="/profile" target="_blank">Мой профиль<br></a>
    <a href="/catalog" class="catalog">Каталог<br></a>
    <h3>Cart</h3>
    <div class="card-deck">
        <?php foreach ($all_products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <img class="card-img-top" src="<?php echo $product['image_url']; ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product['name'];?></p>
                        <p class="card-text text-muted"><?php echo $product['amount'];?></p>
                        <a href="#"><h5 class="card-title"><?php echo $product['description']; ?></h5></a>
                        <div class="card-footer">
                            <?php echo $product['price'];?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        <a href="/create-order" class="edit-mode-btn">Оформить заказ<br></a>
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
    </div>
</div>
</body>
</html>

<style>
    body {
        padding-top: 80px;
    }

    .show-cart li {
        display: flex;
    }
    .card {
        margin-bottom: 20px;
    }
    .card-img-top {
        width: 200px;
        height: 200px;
        align-self: center;
    }
</style>