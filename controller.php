<?php

// require_once('model.php');
require_once('model.php');



function register()
{
    $regex_username = '/^[a-zA-Z0-9_]{3,15}$/'; //This regular expression allows usernames that consist of 3 to 15 characters, including uppercase letters (A-Z), lowercase letters (a-z), numbers (0-9), and underscores (_).
    $regex_password = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\s]).{8,15}$/';

    $registered = false;
    $errors = [];

    if (!empty($_POST)) {
        if (!isUserExist($_POST['name'], $_POST['password'])) {

            if (preg_match($regex_username, $_POST['name']) && preg_match($regex_password, $_POST['password'])) {
                insertUser($_POST['name'], $_POST['password']);
                $registered = true;
            }
            if (!preg_match($regex_username, $_POST['name'])) {
                $errors[] = 'Invalid username format. Usernames should consist of 3 to 15 characters!';
            }
            if (!preg_match($regex_password, $_POST['password'])) {
                $errors[] = 'Invalid password format. Passwords should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';
            }
        } else {
            $errors[] = 'username ' . $_POST['name'] .  ' exist!';
        }
    }

    require_once('views/register.php');
}


function login()
{
    
    // throw new Exception('error!!!', 400);

    $isUserError = false;
    $isPasswordError = false;

    if (!empty($_POST)) {

        if (isUserExist($_POST['name'], $_POST['password'])) {

            $userId = getUserId($_POST['name']);
            setcookie('user', $_POST['name']);
            setcookie('id', $userId);
            $_SESSION['loggedIn'] = true;
            $_SESSION['userName'] = $_POST['name'];
            $_SESSION['user_id'] = $userId;

            header('Location: index.php?action=chat');
            exit;
        } else {
            echo 'username ' . $_POST['name'] .  'Doesnt exist!' . PHP_EOL;
            $isUserError = true;
            $isPasswordError = true;
        }
    }

    if (isset($_GET['logOut'])) {
        session_destroy();
        setcookie("user", "", time() - 3600);
    }

    require_once('views/login.php');
}


function chat()
{
    $requestPayload = file_get_contents("php://input");
    $data = json_decode($requestPayload, true);
    $userName = $_COOKIE['user'];

    if (isset($_GET['messages']) && $_GET['messages']  === 'count') {
        $count =  countMessages();
        $countJson = json_encode(['messages' => $count]);
        echo $countJson;
        exit;
    }
    if (isset($_GET['messages']) && $_GET['messages']  === 'new'){
        $messages = getMessages($_GET['count']);
        $messagesJson = json_encode($messages);
        echo $messagesJson;
        exit;
    }
    if (isset($_GET['messages']) && $_GET['messages'] === 'submit') {
        if ($data['user_id'] == $_SESSION['user_id']){
            insertNewMessage($data['user_id'], $data['message']);
            echo json_encode(['error' => false]);
        }else {
            echo json_encode(['error' => true]);
        }
        exit;
    }

    if (isset($_GET['online'])) {
        $isOnline = in_array($data['user_id'], usersOnlineIds());
        $onlineStatus = [];
        $currentTime = new DateTime();


        if ($isOnline) {
            updateWhoIsOnline($data['user_id'], $data['time']);
        } else {
            insertWhoIsOnline($data['user_id'], $data['time']);
        }

        foreach (getUserOnline() as $user) {
            $userDateTime = new DateTime($user['updated_time']);
            $amountOfSeconds = $currentTime->getTimestamp() - $userDateTime->getTimestamp();

            if ($amountOfSeconds < 60) {
                $onlineStatus[]['online'] = $user['name'];
            } elseif ($amountOfSeconds >= 60 && $amountOfSeconds <= 300) {
                $onlineStatus[]['away']  = $user['name'];
            } else {
                $onlineStatus[]['offline']  = $user['name'];
            }
            
        }

        $jsonOnlineStatus = json_encode($onlineStatus);

        echo $jsonOnlineStatus;
        exit;
    }
    require_once('views/chat.php');
}

function defaultPage()
{
    require_once('views/home.php');
}

function displayTheError($error)
{
    // header($_SERVER["SERVER_PROTOCOL"] . " 404 Has Found");
    if($error->getCode() >= 400 && $error->getCode() < 599){
     http_response_code($error->getCode());
    }else{
     http_response_code(500);
    }
    require_once('views/error.php');
}