<?php $title = "Register"; ?>



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

    .logIn {
        text-align: center;
    }

    .registered {
        text-align: center;
    }
</style>

<?php $style = ob_get_clean(); ?>




<?php ob_start() ?>

<div class="main">

    <h3>Registration</h3>

    <?php if ($registered) { ?>
        <h1 class="registered">You succesfully registered</h1>
    <?php } ?>

    <?php if (!empty($errors)) { ?>
        <div class="errors">
            <?php foreach($errors as $error) { ?>
                <div class="errorText"><?= $error?></div>
            <?php } ?>
        </div>
    <?php } ?>


    <form action="index.php?action=register" method="POST">
        
        <div class="name form-group">
            <label for="name">Username</label>
            <input type="text" id="name" name="name" required placeholder="Username" autocomplete="off">
        </div>

        <div class="password form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Password" autocomplete="off">
        </div>

        <div class="button">
            <button type="submit" name="button" id="button">Register</button>
        </div>
        <div class="logIn">
            <p>Already have an account? <a href="index.php?action=login">Login here!</a></p>
        </div>
    </form>
</div>

<?php $content = ob_get_clean();

require_once('template.php'); ?>