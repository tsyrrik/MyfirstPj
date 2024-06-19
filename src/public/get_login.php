<body class="center flex">
<section class="card center">
    <div class="d-logo center flex">
        <img class="logo" src="https://www.ifatih.com/images/logos/8/4-content-0-logo-1-full-black_3x.png" alt="logo">
    </div>
    <div class="title">
        <h2 class="center flex roboto-thin">One account</h2>
        <h2 class="center flex roboto-thin">Many possibilities</h2>
    </div>

    <form action="handle_login.php" method="POST">
        <section class="form">
            <input class="center flex roboto-thin" type="email" name="email" id="email" placeholder="E-mail address or phone number">
            <?php if (isset($errors['email'])): ?>
                <label><?php echo $errors['email']; ?></label>
            <?php endif; ?>
            <input class="center flex roboto-thin" type="password" name="password" id="password" placeholder="Password">
            <?php if (isset($errors['password'])): ?>
                <label><?php echo $errors['password']; ?></label>
            <?php endif; ?>
            <div class="space-between">
                <div class="center flex">
                    <input type="checkbox" name="rememberMe" id="rememberMe">
                    <label class="text-size-10 roboto-thin" for="rememberMe">Remember me</label>
                </div>
                <div class="center flex">
                    <a class="link text-size-10 roboto-thin" href="">
                        <span>Forgot password?</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="buttons">
            <button class="center flex button button-primary" type="submit">Sign in</button>
            <button class="center flex button button-secondary" type="submit">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                                d="M30.0014 16.3109C30.0014 15.1598 29.9061 14.3198 29.6998 13.4487H16.2871V18.6442H24.1601C24.0014 19.9354 23.1442 21.8798 21.2394 23.1864L21.2127 23.3604L25.4536 26.58L25.7474 26.6087C28.4458 24.1665 30.0014 20.5731 30.0014 16.3109Z"
                                fill="#4285F4"></path>
                        <path
                                d="M16.2863 29.9998C20.1434 29.9998 23.3814 28.7553 25.7466 26.6086L21.2386 23.1863C20.0323 24.0108 18.4132 24.5863 16.2863 24.5863C12.5086 24.5863 9.30225 22.1441 8.15929 18.7686L7.99176 18.7825L3.58208 22.127L3.52441 22.2841C5.87359 26.8574 10.699 29.9998 16.2863 29.9998Z"
                                fill="#34A853"></path>
                        <path
                                d="M8.15964 18.769C7.85806 17.8979 7.68352 16.9645 7.68352 16.0001C7.68352 15.0356 7.85806 14.1023 8.14377 13.2312L8.13578 13.0456L3.67083 9.64746L3.52475 9.71556C2.55654 11.6134 2.00098 13.7445 2.00098 16.0001C2.00098 18.2556 2.55654 20.3867 3.52475 22.2845L8.15964 18.769Z"
                                fill="#FBBC05"></path>
                        <path
                                d="M16.2864 7.4133C18.9689 7.4133 20.7784 8.54885 21.8102 9.4978L25.8419 5.64C23.3658 3.38445 20.1435 2 16.2864 2C10.699 2 5.8736 5.1422 3.52441 9.71549L8.14345 13.2311C9.30229 9.85555 12.5086 7.4133 16.2864 7.4133Z"
                                fill="#EB4335"></path>
                    </g>
                </svg>Sign in with Google</button>
        </section>

        <section class="not-member center flex">
            <span class="text-size-10 roboto-thin"> Not a member yet? <a class="link" href="">Sign up</a></span>
        </section>
    </form>

</section>
</body>
<style>
    :root{
        background-color: #f2f2f2;
    }
    .roboto-thin {
        font-family: "Roboto", sans-serif;
        font-style: normal;
    }
    h2{
        font-size: 20px;
        font-weight: 500;
    }
    .card{
        display: grid;
        background-color: #ffffff;

        box-shadow: 0 4px 6px 0 hsla(0, 0%, 0%, 0.2);
        border-radius: 10px;

        max-width: 375px;
        width: 100%;
        height: 100%;
        margin: 10% 0 0 0;
    }
    .d-logo{
        margin: 40px 0 20px 0;
    }
    .logo{
        max-height: 50px;
        max-width: 375px;
        width: auto;
    }
    .title{
        margin: 0 0 10px 0;
        /* max-width: 375px; */
        width: 300px;
    }
    .center{
        align-items: center;
        justify-content: center;
    }
    .flex{
        display: flex;
    }
    input{
        border: 1px solid #c1c1c1;
        font-size: 10px;
    }
    input[type="email"], input[type="password"]{
        border-radius: 5px;
        margin: 10px 0;
        padding: 8px 0 8px 5px;
        width: 300px;
    }
    input::placeholder{
        font-size: 10px;
        color: #a5a5a5;
        padding: 0 0 0 5px;
    }
    .form{
        margin: 0 0 30px 0;
    }
    .space-between{
        display:flex;
        justify-content: space-between;
    }
    a{
        text-decoration: none;
    }
    .link{
        color: #f86d34;
    }
    .text-size-10{
        font-size: 10px;
        font-weight: 500;
    }
    .buttons{
        margin: 10px 0 0 0;
    }
    .button{
        all:unset;
        border-radius: 5px;
        margin: 5px 0;
        padding: 8px 0 8px 5px;
        width: 100%;
        max-width: 96%;
        font-size: 10px;
        font-family: "Roboto", sans-serif;
        text-align: center;
    }
    .button-primary{
        color: #ffffff;
        background-color: #2a2c30;
        border: 1px solid #2a2c30;
    }
    .button-primary:hover{
        color: #ffffff; /* #2a2c30; */
        background-color: #f86d34; /* #ffffff; */
        border: 1px solid #2a2c30;
    }
    .button-secondary{
        color: #2a2c30;
        background-color: #ffffff;
        border: 1px solid #2a2c30;
    }
    .button-secondary:hover{
        color: #ffffff;
        background-color: #f86d34; /* #3179f2; */
    }
    .not-member{
        margin: 20px 0 40px 0;
    }
    svg{
        max-width: 10px;
        padding: 0 16px 0 0;
    }
</style>