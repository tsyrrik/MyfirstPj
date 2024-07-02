<a href="/catalog">Catalog</a>
<a href="/add-product">Logout</a>
<a href="/my_profile">My profile</a>
<a href="/logout">Logout</a>

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
<div class="wrapper">
    <h1 class="heading">CART <i class="fa-solid fa-cart-shopping"></i></h1>
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <label>
                <input class="chkbx" type="checkbox"/>
                Product ID: <?php echo htmlspecialchars($item['product_id']); ?>, Count: <?php echo htmlspecialchars($item['count']); ?>
            </label><br>
        <?php endforeach; ?>
    <?php endif; ?>
    <p class="checkout">Checkout→</p>
</div>
</body>
</html>