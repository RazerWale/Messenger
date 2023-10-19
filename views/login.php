<?php $title = "Login"; ?>

<!-- variables in the file
isUserError - if there is an error with the user should be true
isPasswordError - if there is an error with the password should be true
-->

<?php

$userErrorClass = '';
$passwordErrorClass = '';


if ($isUserError) {
    $userErrorClass =  'userNotFound';
} 

if ($isPasswordError) {
    $passwordErrorClass =  'passwordNotFound';
} 




?>
<?php ob_start() ?>

<style>
    .main {
        width: 500px;
        margin: 0 auto;
    }

    form {
        margin: 50px;
        border: 1px solid black;
        border-radius: 10px;
    }

    h3 {
        text-align: center;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin: 10px 15px;
    }

    label {
        margin: 5px 0;
    }

    .button {
        margin: 10px 15px;
    }

    button {
        width: 100%;
        margin: 10px auto;
        padding: 10px 0;
        background-color: firebrick;
        border-radius: 5px;
        border: none;
    }

    button:hover {
        box-shadow: 1px 1px 5px black;
        transition: 250ms ease-out;
    }

    .userNotFound,
    .passwordNotFound {
        border: 2px solid red;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .userNotFound:focus,
    .passwordNotFound:focus {
        outline: 1px solid red;
        border-radius: 5px;
    }

    .register {
        text-align: center;
    }
</style>

<?php $style = ob_get_clean(); ?>




<?php ob_start() ?>

<div class="main">

    <h3>Log In</h3>

    <form action="index.php?action=login" method="POST">
        <div class="name form-group">
            <label for="name">Username</label>
            <input type="text" id="name" class="<?= $userErrorClass?>" name="name" required placeholder="Username" autocomplete="off">
        </div>

        <div class="password form-group">
            <label for="password">Password</label>
            <input type="password" id="password" class="<?= $passwordErrorClass?>" name="password" required placeholder="Password" autocomplete="off">
        </div>

        <div class="button">
            <button type="submit" name="button" id="button">Send</button>
        </div>

        <div class="register">
            <p>Don't have an Account? <a href="index.php?action=register">Register here!</a></p>
        </div>
    </form>


</div>

<?php $content = ob_get_clean();

require_once('template.php'); ?>