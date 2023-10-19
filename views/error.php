<?php $title = "Error page"; ?>


<?php ob_start() ?>

<style>
        .main{
            text-align: center;
        }

        a {
            text-decoration: none;
        }
    </style>

<?php $style = ob_get_clean(); ?>




<?php ob_start() ?>

<div class="main">
    <h2> Something bad happend! <?= $error->getMessage()?> Please click below!</h2>
    <button><a href="index.php?action=register">Register</a></button>
    <button><a href="index.php?action=login">Log In</a></button>
</div>

<?php $content = ob_get_clean();

require_once('template.php'); ?>