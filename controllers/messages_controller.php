<?php
require_once('../models/Message.class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $message = new Message();

    switch ($action){
        case 'display':
            $_SESSION['errors'] = [];
            $messages = $message->findAll();
            $_SESSION['messages'] = $messages;
            header('Location: ../views/messages_list.php');
            break;

        case 'delete':
            if ($message->delete($_GET)){
                header('Location: ../views/messages_list.php');
                die;
            }
            header('Location: ../views/messages_list.php');
            break;
        default;
            header('Location: ../views/messages_list.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>