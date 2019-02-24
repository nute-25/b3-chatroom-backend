<?php
require_once('../models/User.class.php');
//demarrage session
session_start();


// try/catch pour lever les erreurs de connexion
try {


    $action = isset($_GET['action']) ? $_GET['action'] : '';

    $user = new User();

    switch ($action){
        case 'login':
            //récupération du JSON envoyé par VUE.js
            $json_collected = json_decode(file_get_contents('php://input'));

            //return true ou false si on est login
            /*$check = $user->login($_POST);*/
            $check = $user->login($json_collected);
            //si true
            if ($check) {
                $_SESSION['errors'] = [];
                $check = $user->retrieveUser($json_collected);
                /*$_SESSION['user_login'] = $_POST['login'];
                $users = $user->findAll();
                $_SESSION['users'] = $users;
                header('Location: ./users_controller.php?action=list');
                die;*/
            }
            else {
                // put errors in $session
                $_SESSION['errors'] = $user->errors;
                // replace value false by errors
                $check = $_SESSION['errors'];
            }
            /*header('Location: ../views/users_login.php');*/

            //Preparing headers to answer VUE
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode($check);
            break;

        case 'list':
            $_SESSION['errors'] = [];
            $users = $user->findAll();
            $_SESSION['users'] = $users;
            header('Location: ../views/users_list.php');
            break;
        case 'jsonlist':
            $users = $user->findAll();
            header("Access-Control-Allow-Origin: *");
            header('Content-type: application/json; charset=UTF-8');
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            echo json_encode($users);
            break;

        case 'delete':
            if ($user->delete($_GET)){
                header('Location: ../views/users_login.php');
                die;
            }
            header('Location: ../views/users_list.php');
            break;

        case 'update';
            if ($user->update($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ./users_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $user->errors;
            header('Location: ../views/users_modification.php');
            break;

        case 'register';
            if ($user->save($_POST)){
                $_SESSION['errors'] = [];
                header('Location: ./users_controller.php?action=list');
                die;
            }
            $_SESSION['errors'] = $user->errors;
            header('Location: ../views/users_register.php');
            break;

        case 'test' :
            $recu = json_decode(file_get_contents('php://input'));
            /*print_r($recu);
            die;*/
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header('Content-type: application/json; charset=UTF-8');
            echo json_encode($recu);
            break;

        default:
            header('Location: ../views/users_login.php');
            break;
    }
} catch (Exception $e) {
    echo('cacaboudin exception');
    print_r($e);
}
?>