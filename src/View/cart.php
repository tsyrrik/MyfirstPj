<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- For fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- For icons -->
    <script src="https://kit.fontawesome.com/6bf5f276c4.js" crossorigin="anonymous"></script>

    <style>
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .heading {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .chkbx {
            margin-right: 10px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .cart-item img {
            max-width: 100px;
            margin-right: 20px;
        }
        .cart-item h2 {
            font-size: 18px;
            margin: 0;
        }
        .cart-item p {
            margin: 5px 0;
        }
        .checkout {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #fff;
            background-color: #04AA6D;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout:hover {
            background-color: #039f5b;
        }
    </style>
</head>
<body>
<a href="/catalog">Catalog</a>
<a href="/add-product">Add Product</a>
<a href="/my_profile">My profile</a>
<a href="/logout">Logout</a>
<div class="wrapper">
    <h1 class="heading">CART <i class="fa-solid fa-cart-shopping"></i></h1>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item">
                <?php
                $product = $item['product'];
                $count = $item['count'];
                $imageUrl = htmlspecialchars($product->getImgUrl() ?? 'default_image.jpg');
                ?>
                <img src="<?= $imageUrl; ?>" alt="<?= htmlspecialchars($product->getName()); ?>">
                <div>
                    <h2><?= htmlspecialchars($product->getName()); ?></h2>
                    <p>Цена: <?= htmlspecialchars($product->getPrice()); ?> руб.</p>
                    <p>Количество: <?= htmlspecialchars($count); ?></p>
                    <form action="/remove-product" method="POST">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product->getId()); ?>">
                        <button type="submit">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <p class="checkout">Checkout→</p>
</div>
</body>
</html>
