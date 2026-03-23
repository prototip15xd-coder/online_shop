<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <div class="container">
        <a href="/profile" target="_blank">Мой профиль<br></a>
        <a href="/cart" class="Cart">Корзина<br></a>
        <a href="/catalog" class="Cart">Каталог<br></a>
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
        <h3>Catalog</h3>
        <div class="card-deck">
                <!-- Каждая карточка товара с формой -->
                <div class="card text-center">
                    <img class="card-img-top" src="<?php echo $product->getProductImageUrl(); ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getProductName();?></p>
                        <h5 class="card-title"><?php echo $product->getProductDescription(); ?></h5>
                        <div class="card-footer">
                            <?php echo $product->getProductPrice();?>
                        </div>
                    </div>
                    <?php if (isset($productReviews)): ?>
                        <?php foreach ($productReviews as $review): ?>
                            <p><strong><?php echo $review->getUserName(); ?></strong> - Оценка: <?php echo $review->getRating(); ?>/5</p>
                            <p style="margin-left: 20px;">Отзыв: "<?php echo $review->getReview(); ?>"</p>
                            <hr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
        </div> <!-- закрываем div.card-deck -->
    </div> <!-- закрываем div.container -->
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