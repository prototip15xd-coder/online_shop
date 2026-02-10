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
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
        <h3>Catalog</h3>
        <div class="card-deck">
            <?php foreach ($products as $product): ?>
                <!-- Каждая карточка товара с формой -->
                <div class="card text-center">
                    <img class="card-img-top" src="<?php echo $product->getImageUrl(); ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getName();?></p>
                        <h5 class="card-title"><?php echo $product->getDescription(); ?></h5>
                        <div class="card-footer">
                            <?php echo $product->getPrice();?>
                        </div>
                    </div>

                    <table><tr>
                            <td>
                                <form action="/catalog" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>" required>
                                    <input type="hidden" name="action" value="plus">
                                    <button type="submit">+</button>
                                </form>
                            </td>
                            <td><?php echo $product->getAmount(); ?></td>
                            <td>
                                <form action="/catalog" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>" required>
                                    <input type="hidden" name="action" value="minus">
                                    <button type="submit">-</button>
                                </form>
                            </td>
                        </tr></table>
                </div> <!-- закрываем div.card -->
            <?php endforeach; ?>
        </div> <!-- закрываем div.card-deck -->
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>