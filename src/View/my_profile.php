<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой профиль</title>
    <style>
        @import url("https://fonts.googleapis.com/css?family=Lato:400,400i,700");

        body {
            margin: 0;
            font-family: 'Lato', sans-serif;
        }

        .form {
            max-width: calc(100vw - 40px);
            width: 500px;
            height: auto;
            background: rgba(255, 255, 255, 1);
            border-radius: 8px;
            box-shadow: 0 0 40px -10px #fff;
            margin: 3% auto;
            padding: 20px 30px;
            box-sizing: border-box;
            position: relative;
            border-bottom: 5px solid #ccc;
        }

        .form h2 {
            margin: 18px 0;
            padding-bottom: 10px;
            width: 210px;
            color: #1e439b;
            font-size: 22px;
            border-bottom: 3px solid #ff5501;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group .relative {
            position: relative;
        }

        .form-group .relative i.fa {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            background: none;
            outline: none;
            resize: none;
            border: 2px solid #bebed2;
            transition: all .3s;
        }

        .form-control:focus {
            border-color: #1e439b;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgb(30, 102, 195);
        }

        .tright {
            text-align: right;
        }

        .thanks {
            max-width: calc(100vw - 40px);
            width: 200px;
            height: auto;
            background-color: #444;
            border-radius: 8px;
            box-shadow: 0 0 40px -10px #000;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
            position: absolute;
            top: 20px;
            right: 20px;
            transition: all .3s;
        }

        .thanks h4,
        .thanks p {
            color: #fff;
            text-align: center;
        }

        .movebtn {
            background-color: transparent;
            display: inline-block;
            width: 100px;
            background-image: none;
            padding: 8px 10px;
            margin-bottom: 20px;
            border-radius: 0;
            transition: all 0.5s;
            transition-timing-function: cubic-bezier(0.5, 1.65, 0.37, 0.66);
        }

        .movebtnre {
            border: 2px solid #ef9e75;
            box-shadow: inset 0 0 0 0 #ff5501;
            color: #ff5501;
        }

        .movebtnsu {
            border: 2px solid #1e439b;
            box-shadow: inset 0 0 0 0 #1e439b;
            color: #1e439b;
        }

        .movebtnre:focus,
        .movebtnre:hover,
        .movebtnre:active {
            background-color: transparent;
            color: #FFF;
            border-color: #ff5501;
            box-shadow: inset 96px 0 0 0 #ff5501;
        }

        .movebtnsu:focus,
        .movebtnsu:hover,
        .movebtnsu:active {
            background-color: transparent;
            color: #FFF;
            border-color: #1e439b;
            box-shadow: inset 96px 0 0 0 #1e439b;
        }
    </style>
</head>
<body>

<form class="form" method="POST">
    <h2>User Profile</h2>
    <div class="form-group">
        <label for="name">Name:</label>
        <div class="relative">
            <input class="form-control" id="name" name="name" type="text" pattern="[a-zA-Z\s]+" required="" autofocus="" title="Name should only contain letters." autocomplete="" placeholder="Type your name here..." value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
            <i class="fa fa-user"></i>
        </div>
    </div>
    <div class="form-group">
        <label for="last_name">Last Name:</label>
        <div class="relative">
            <input class="form-control" id="last_name" name="last_name" type="text" pattern="[a-zA-Z\s]+" required="" title="Last name should only contain letters." autocomplete="" placeholder="Type your last name here..." value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
            <i class="fa fa-user"></i>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email address:</label>
        <div class="relative">
            <input class="form-control" id="email" name="email" type="email" required="" placeholder="Type your email address..." pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
            <i class="fa fa-envelope"></i>
        </div>
    </div>
    <div class="tright">
        <button class="movebtn movebtnre" type="reset"><i class="fa fa-fw fa-refresh"></i> Reset </button>
        <button class="movebtn movebtnsu" type="submit">Submit <i class="fa fa-fw fa-paper-plane"></i></button>
    </div>
</form>

<div class="thanks" style="display: none;">
    <h4>Thank you!</h4>
    <p><small>Your profile has been successfully updated.</small></p>
</div>
</body>
</html>