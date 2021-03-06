<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 4:21 PM
 */
require_once('../classes/Connection.class.php');

class Message
{
    public $id;
    public $content;
    public $user_id;
    public $chatroom_id;

    public $errors = [];

    public function __construct($id = null)
    {
        if (!is_null($id)) {
            $this->get($id);
        }
    }


    public function get($id = null)
    {
        if (!is_null($id)) {
            $dbh = Connection::get();
            //print_r($dbh);

            $stmt = $dbh->prepare("select * from messages where id = :id limit 1");
            $stmt->execute(array(
                ':id' => $id
            ));
            // recupere les messages et fout le resultat dans une variable sous forme de tableau de tableaux
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
            $message = $stmt->fetch();

            $this->id = $message->id;
            $this->content = $message->content;
            $this->user_id = $message->user_id;
            $this->chatroom_id = $message->chatroom_id;
        }
    }

    // retourne messages du user
    public function findAll($data)
    {
        $dbh = Connection::get();
        /*$stmt = $dbh->query("select * from messages where user_id = (select id from users where login = '".$_SESSION['user_login']."')");*/
        $stmt = $dbh->query("select * from messages where user_id = '".$data->user_id."'");
        // recupere les messages et fout le resultat dans une variable sous forme de tableau de tableaux
        $messages = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $messages;
    }

    public function save($data)
    {
        $dbh = Connection::get();

        $sql = "insert into messages (content, user_id, chatroom_id) values (:content, :user_id, :chatroom_id)";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($sth->execute(array(
            ':content' => $data->content,
            ':user_id' => $data->user_id,
            ':chatroom_id' =>$data->chatroom_id
        ))) {
            return true;
        } else {
            // ERROR
            $this->errors['Impossible to add message'];
        }
        return false;
    }

    // supprime un message de l'user
    public function delete(/*$data*/$msg_id) {
        $dbh = Connection::get();
        $sql = "delete from messages where id = :id limit 1";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if($sth->execute(array(
            /*':id' => $data['id']*/
            ':id' => $msg_id
        ))) {
            return true;
        } else {
            return false;
        }
    }
}