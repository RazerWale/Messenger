<?php

function connect()
{
    try {
        $db = new PDO('mysql:host=localhost;dbname=mydatabase;charset=utf8', 'root', '');
    } catch (Exception $e) {
        // die('Error : ' . $e->getMessage());
        throw new Exception('connecting Error to Database!',$e->getCode());
    }
    return $db;
}


function insertUser($userName, $userPassword)
{
    $db = connect();
    $req = $db->prepare('
    INSERT INTO users (id, name, password)
    VALUES (NULL, ?, ?)
    ');
    $req->execute([$userName, $userPassword]);
}

function isUserExist($userName, $userPassword)
{

    $db = connect();
    $req = $db->prepare('
    SELECT count(*) 
    FROM users 
    WHERE name = ? AND password = ?
    ');
    $req->execute([$userName, $userPassword]);
    $result = $req->fetch(PDO::FETCH_ASSOC);

    return $result['count(*)'] > 0;
}

function getUserId($userName)
{
    $db = connect();
    $req = $db->prepare('
    SELECT id 
    FROM users 
    WHERE name = ?
    ');
    $req->execute([$userName]);
    $result = $req->fetch(PDO::FETCH_ASSOC);

    return $result['id'];
}

function getMessages($count)
{
    $db = connect();
    $req = $db->prepare('
    SELECT chat.user, users.name, chat.created_at, chat.message 
    FROM users 
    INNER JOIN chat ON users.id=chat.user 
    ORDER BY chat.created_at 
    DESC LIMIT :count
    ');
    $req->bindValue(':count', $count, PDO::PARAM_INT);
    $req->execute();
    $req = $req->fetchAll(PDO::FETCH_ASSOC);

    return $req;
}

function countMessages()
{
    $db = connect();
    $req = $db->prepare('
    SELECT COUNT(*) 
    FROM chat
    ');
    $req->execute();
    $req = $req->fetch(PDO::FETCH_ASSOC);

    return $req['COUNT(*)'];
}

function usersOnlineIds()
{
    $db = connect();
    $req = $db->prepare('
    SELECT DISTINCT user 
    FROM users_online
    ');
    $req->execute();
    $req = $req->fetchAll(PDO::FETCH_ASSOC);

    foreach ($req as $id) {
        $ids[] = $id['user'];
    }
    return $ids;
}

function updateWhoIsOnline($user, $updateTime)
{
    $db = connect();
    $req = $db->prepare('
    UPDATE users_online 
    SET updated_time = ? 
    WHERE user = ?
    ');
    $req->execute([$updateTime, $user]);
}

function insertWhoIsOnline($user, $updateTime)
{
    $db = connect();
    $req = $db->prepare('
    INSERT INTO users_online (user, updated_time)
    VALUES (?, ?)
    ');
    $req->execute([$user, $updateTime]);
}

function getUserOnline()
{
    $db = connect();
    $req = $db->prepare('
    SELECT users_online.user, users_online.updated_time, users.name 
    FROM users_online 
    INNER JOIN users 
    ON users_online.user=users.id
    ');
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

function insertNewMessage($user, $message)
{
    $db = connect();
    $req = $db->prepare('
    INSERT INTO chat (user, message)
    VALUES (?, ?)
    ');
    $req->execute([$user, $message]);
}
