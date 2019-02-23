<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 6:54 PM
 */
require_once('../classes/Connection.class.php');

class Chatroom
{
    public $id;
    public $title;
    public $user_id;

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

            $stmt = $dbh->prepare("select * from chatrooms where id = :id limit 1");
            $stmt->execute(array(
                ':id' => $id
            ));
            // recupere les messages et fout le resultat dans une variable sous forme de tableau de tableaux
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Chatroom');
            $chatroom = $stmt->fetch();

            $this->id = $chatroom->id;
            $this->title = $chatroom->title;
            $this->user_id = $chatroom->user_id;
        }
    }

    // retourne chatrooms du user
    public function findAll()
    {
        $dbh = Connection::get();
        $stmt = $dbh->query("select * from chatrooms where user_id = (select id from users where login = '".$_SESSION['user_login']."')");
        // recupere les messages et fout le resultat dans une variable sous forme de tableau de tableaux
        $chatrooms = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $chatrooms;
    }
}