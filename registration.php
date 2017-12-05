<?php
ob_start();
require_once 'core/init.php';
require_once 'helpers/helpers.php';
include 'includes/head.php';
?>
    <style type="text/css">
        body{
            background-image: url("images/headerlogo/login.jpg");
            background-size: 100vw 100vh;
            background-attachment: fixed;
            padding: 20px;
        }
    </style>
    <div id="login-form">
        <h2 class="text-center">Registration</h2>
        <form action="login.php" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="full_name">Full name:</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" class="form-control" value="">
            </div>

            <div class="form-group">
                <input type="submit" name="" value="Register" class="text-center btn btn-primary"/>
                <input type="hidden" name="page" value="registration"/>
            </div>
            <p class="text-right"><a href="login.php" alt="login">Already Registered? Log In </a></p>
        </form>

    </div>
<?php ob_end_flush();?>