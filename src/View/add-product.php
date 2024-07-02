<a href="/catalog">Catalog</a>
<a href="/logout">Logout</a>
<a href="/my_profile">My profile</a>

<form action="/add-product" method="POST">
    <div class="container">
        <h1>Add product</h1>
        <p>Please fill in this form to add a product to the cart.</p>
        <hr>

        <label for="product_id"><b>Product ID</b></label>
        <?php if (isset($errors['product_id'])): ?>
            <label><?php echo $errors['product_id']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Product ID" name="product_id" id="product_id" required>

        <label for="count"><b>Count</b></label>
        <?php if (isset($errors['count'])): ?>
            <label><?php echo $errors['count']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter Count" name="count" id="count" required>

        <p>By adding a product you agree to our <a href="#">Terms & Privacy</a>.</p>
        <button type="submit" class="registerbtn">Add product</button>
    </div>

    <div class="container signin">
        <p>Already have an account? <a href="#">Sign in</a>.</p>
    </div>
</form>

<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>