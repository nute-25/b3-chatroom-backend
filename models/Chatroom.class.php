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


    // verifie les champs saisis par l internaute
    public function validate($data)
    {
        $this->errors = [];

        /* required fields */
        if (!isset(/*$data['title']*/$data->title)) {
            $this->errors[] = 'Empty title field';
        }

        /* tests de formats */
        if (isset(/*$data['title']*/$data->title)) {
            if (empty(/*$data['title']*/$data->title)) {
                $this->errors[] = 'Empty title field';
                // si title > 50 chars
            } else if (mb_strlen(/*$data['title']*/$data->title) > 45) {
                $this->errors[] = 'Title field is too long (45max)';
            }
        }

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    // enregistrement chatroom d un user
    public function save($data)
    {
        // si les champs rentrés sont valident
        if ($this->validate($data)) {
            /* syntaxe avec preparedStatements */
            $dbh = Connection::get();

            /*$stmt = $dbh->query("select * from users where login = '".$_SESSION['user_login']."'");
            $users = $stmt->fetch();*/

            $sql = "insert into chatrooms (title, user_id) values (:title, :user_id)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                /*':title' => $data['title'],
                ':user_id' => $users['id']*/
                ':title' => $data->title,
                ':user_id' => $data->user_id
            ))) {
                return true;
            } else {
                // ERROR
                $this->errors['Impossible to create chatroom'];
            }
        }
        return false;
    }

    // modifier une chatroom
    public function update($data)
    {
        // si les champs rentrés sont valident
        if ($this->validate($data)) {
            // update
            $dbh = Connection::get();

            $sql = "update chatrooms set title=:title, modified=:modified where title = '".$_SESSION['chatroom_title']."'";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':title' => $data['title'],
                ':modified' => date("Y-m-d H:i:s")
            ))) {
                $_SESSION['chatroom_title'] = $data['title'];
                return true;
            } else {
                // ERROR
                // put errors in $session
                $this->errors['pas reussi a mettre a jour la chatroom'];
            }

        }
        return false;
    }

    // retourne messages d une chatroom du user
    public function findAllMessages($data)
    {
        $dbh = Connection::get();
        $req_user = "(select id from users where login = '".$_SESSION['user_login']."')";
        $req_chatroom = "(select id from chatrooms where title = '".$data."')";
        $stmt = $dbh->query("select * from messages where user_id = $req_user and chatroom_id = $req_chatroom");
        // recupere les messages et fout le resultat dans une variable sous forme de tableau de tableaux
        $chatroom_messages = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $chatroom_messages;
    }
}