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
            $_SESSION['chatrooms'] = $chatrooms;
            header('Location: ../views/chatrooms_list.php');
            break;

        case 'register';
            //récupération du JSON envoyé par VUE.js
            $json_collected = json_decode(file_get_contents('php://input'));

            //return true ou false si la chatroom a été enregistré
            /*$check = $chatroom->save($_POST);*/
            $check = $chatroom->save($json_collected);
            //si true
            if ($check){
                $_SESSION['errors'] = [];
                /*header('Location: ./chatrooms_controller.php?action=list');
                die;*/
            }
            else {
                // put errors in $session
                $_SESSION['errors'] = $chatroom->errors;
                // replace value false by errors
                $check = $_SESSION['errors'];
            }
            /*header('Location: ../views/chatrooms_register.php');*/

            //Preparing headers to answer VUE
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode($check);
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

        case 'displayMessages':
            $_SESSION['errors'] = [];
            $chatroom_messages = $chatroom->findAllMessages($_GET['title']);
            $_SESSION['chatroom_messages'] = $chatroom_messages;
            header('Location: ../views/chatrooms_messages_list.php');
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