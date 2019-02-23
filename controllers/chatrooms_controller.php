<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 7:06 PM
 */
require_once('../models/Chatroom.class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $chatroom = new Chatroom();

    switch ($action){
        case 'list':
            $_SESSION['errors'] = [];
            $chatrooms = $chatroom->findAll();
            $_SESSION['messages'] = $chatrooms;
            header('Location: ../views/chatrooms_list.php');
            break;

        case 'register';
            if ($chatroom->save($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ./chatrooms_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $chatroom->errors;
            header('Location: ../views/chatrooms_register.php');
            break;

        case 'update';
            if ($chatroom->update($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ./chatrooms_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $chatroom->errors;
            header('Location: ../views/chatrooms_modification.php');
            break;

        default;
            header('Location: ../views/chatrooms_list.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>