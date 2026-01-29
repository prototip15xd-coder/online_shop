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
                    <img class="card-img-top" src="<?php echo $product['image_url']; ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product['name'];?></p>
                        <h5 class="card-title"><?php echo $product['description']; ?></h5>
                        <div class="card-footer">
                            <?php echo $product['price'];?>
                        </div>
                    </div>

                    <!-- Форма для этого конкретного товара -->
                    <form action="/catalog" method="POST">
                        <div class="container">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" required>

                            <?php if (isset($errors['amount'])): ?>
                                <label style="color:red"><?php echo $errors['amount']; ?></label>
                            <?php endif; ?>

                            <input type="text"
                                   placeholder="Enter amount"
                                   name="amount"
                                   id="amount_<?php echo $product['id']; ?>"
                                   required>
                        </div>
                        <button type="submit" class="AddProdbtn">Add Product</button>
                    </form>
                </div> <!-- закрываем div.card -->
            <?php endforeach; ?>
        </div> <!-- закрываем div.card-deck -->
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>