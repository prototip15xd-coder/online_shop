<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
    </head>

    <body>
        <div class="container">
            <a href="./profile" target="_blank">Мой профиль</a>
            <div class="menu-item">
                <a href="/logout" class="edit-mode-btn">
                    <i class="fa fa fa-xs"></i> Выйти из профиля
                </a>
            </div>
            <h3>Catalog</h3>
            <div class="card-deck">
                <?php foreach ($products as $product): ?>
                    <div class="card text-center">
                        <a href="#">
                            <img class="card-img-top" src="<?php echo $product['image_url']; ?>">
                            <div class="card-body">
                                <p class="card-text text-muted"><?php echo $product['name'];?></p>
                                <a href="#"><h5 class="card-title"><?php echo $product['description']; ?></h5></a>
                                <div class="card-footer">
                                    <?php echo $product['price'];?>
                                </div>
                            </div>
                        </a>
                        <form action="/catalog" method = "POST">
                            <div class="container">

                                <input type="hidden" placeholder="Enter product-id" name="product_id" value="<?php echo $product['id'] ?>" id="product_id" required>

                                <label for="amount"><b>Amount</b></label>
                                <?php if (isset($errors['amount'])): ?>
                                    <label style="color: red"><?php echo $errors['amount']; ?></label>
                                <?php endif; ?>
                                <input type="text" placeholder="Enter amount" name="amount" id="amount" required>

                            </div>
                            <button type="submit" class="AddProdbtn">Add Product</button>
                    </div>
                <?php endforeach; ?>
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

    /* Set a style for the submit/register button */
    .AddProdbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .AddProdbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }
</style>