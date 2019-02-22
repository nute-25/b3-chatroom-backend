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

    // retourne messages
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
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message.class');
            $message = $stmt->fetch();

            $this->id = $message->id;
            $this->content = $message->content;
            $this->user_id = $message->user_id;
            $this->chatroom_id = $message->chatroom_id;
        }
    }

    // supprime un message de l'user
    public function delete($data) {
        $dbh = Connection::get();
        $sql = "delete from messages where id = :id limit 1";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if($sth->execute(array(
            ':id' => $data['id']
        ))) {
            return true;
        } else {
            return false;
        }
    }
}