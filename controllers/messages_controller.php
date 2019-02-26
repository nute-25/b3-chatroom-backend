<?php
require_once('../models/Message.class.php');
//demarrage session
session_start();

// try/catch pour lever les erreurs de connexion
try {

    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $message = new Message();

    switch ($action){
        case 'list':
            //récupération du JSON envoyé par VUE.js
            $json_collected = json_decode(file_get_contents('php://input'));

            $_SESSION['errors'] = [];
            $messages = $message->findAll($json_collected);
            /*$_SESSION['messages'] = $messages;
            header('Location: ../views/messages_list.php');*/

            //Preparing headers to answer VUE
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode($messages);
            break;

        case 'register':
            //récupération du JSON envoyé par VUE.js
            $json_collected = json_decode(file_get_contents('php://input'));

            $check = $message->save($json_collected);
            //si true
            if ($check){
                $_SESSION['errors'] = [];
            }
            else {
                // put errors in $session
                $check = $message->errors;
            }

            //Preparing headers to answer VUE
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode($check);
            break;

        case 'delete':
            if ($message->delete($_GET)){
                header('Location: ./messages_controller.php?action=list');
                die;
            }
            header('Location: ./messages_controller.php?action=list');
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