<?php $title = "Chat"; ?>
<?php ob_start() ?>

<style>
    body {
        background-color: #404556;
    }

    .color_0 {
        color: red;
    }

    .color_1 {
        color: blue;
    }

    .color_2 {
        color: green;
    }

    .color_3 {
        color: yellow;
    }

    .color_4 {
        color: orange;
    }

    .color_5 {
        color: purple;
    }

    .color_6 {
        color: cyan;
    }

    .container {
        display: flex;
        justify-content: space-between;
    }

    .greetings {
        margin: 0 auto;
    }

    .error {
        border: 2px solid red;
    }

    .msgContainer {
        display: flex;
        flex-wrap: wrap;
        margin: 0 10px;
        margin-bottom: 20px;
    }

    .you {
        flex-direction: row-reverse;
    }

    .msgName {
        font-weight: 700;
    }

    .msgDate {
        margin: 0 10px;
        font-size: 0.7rem;
        color: grey;
        padding-top: 5px;
    }

    .msgText {
        background-color: #727687;
        border-radius: 5px;
        width: fit-content;
        padding: 5px 5px;
        color: #70FACB;
        font-family: monospace;
        animation: popup 600ms ease-out;
    }

    .msgImg {
        animation: popup 600ms ease-out;
    }

    @keyframes popup {
        0% {
            transform: scale(0);
        }

        100% {
            transform: scale(1);
        }
    }

    .messages {
        width: 350px;
        height: 70vh;
        position: sticky;
        overflow: scroll;
        padding-top: 1rem;
        margin: 0 auto;
        background-color: #A0AACE;
        border-radius: 10px 10px 0 0;
    }

    #chat {
        display: flex;
        flex-direction: row;
    }

    #chat-form {
        width: 350px;
        margin: 0 auto;
        padding: 10px 0;
        background-color: #A0AACE;
        border-radius: 0 0 10px 10px;
    }


    .containerNameDate {
        display: flex;
        width: 100%;
        margin: 5px 0;
    }

    #message {
        width: 300px;
        margin-left: 8px;
        border-radius: 5px;
        height: 1.5rem;
        padding: 0;
    }

    button {
        height: 1.6rem;
    }

    .groups,
    .onlineStatus {
        width: 15%;
        padding: 0 10px;
        background-color: #A0AACE;
        border-radius: 10px;
    }

    .online,
    .away,
    .offline {
        margin: 10px 0;
    }

    .setFlex {
        display: flex;
        margin: 0 0 0 5px;
    }

    .green {
        background-color: green;
        border-radius: 50%;
        width: 6px;
        height: 6px;
        margin: auto 5px;
    }

    .yellow {
        background-color: yellow;
        border-radius: 50%;
        width: 6px;
        height: 6px;
        margin: auto 5px;
    }

    .grey {
        background-color: grey;
        border-radius: 50%;
        width: 6px;
        height: 6px;
        margin: auto 5px;
    }
</style>

<?php $style = ob_get_clean(); ?>




<?php ob_start() ?>

<div class="container" id="container">
    <div class="greetings">
        <h1 style="margin-left: 44px;">Welcome
            <?= $userName ?>
        </h1>
    </div>
    <form action="index.php?action=login" method="GET">
        <div class="logOut">
            <input type="hidden" name="action" value="login"></input>
            <button type="submit" name="logOut" value="logOut">Log Out</button>
        </div>
    </form>
</div>
<div id="chat">
    <div class="groups">hello</div>
    <div id="messages" class="messages">
    </div>
    <div class="onlineStatus">
        <div class="online">Online</div>
        <div class="away">Away</div>
        <div class="offline">Offline</div>
    </div>
</div>
<form id="chat-form" action="index.php?action=chat" method="POST">
    <input type="text" name="message" id="message" autocomplete="off">
    <button type="submit" name="send" value="send"><i class="fa-solid fa-shield-cat"></i></button>
</form>

<script src="views/chat.js">
</script>

<?php $content = ob_get_clean();

require_once('template.php'); ?>