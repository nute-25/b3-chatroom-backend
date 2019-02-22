<?php
require_once('../classes/Connection.class.php');

Class User
{

    public $id;
    public $login;
    public $password;
    public $handle;
    // TODO : created and modified

    public $errors = [];

    public function __construct($id = null)
    {
        if (!is_null($id)) {
            $this->get($id);
        }
    }

    // retourne users
    public function get($id = null)
    {
        if (!is_null($id)) {
            $dbh = Connection::get();
            //print_r($dbh);

            $stmt = $dbh->prepare("select * from users where id = :id limit 1");
            $stmt->execute(array(
                ':id' => $id
            ));
            // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            $user = $stmt->fetch();

            $this->id = $user->id;
            $this->login = $user->login;
            $this->password = $user->password;
            $this->handle = $user->handle;
            // TODO : created and modified
        }
    }

    // verifie les champs saisis par l internaute
    public function validate($data)
    {
        $this->errors = [];

        /* required fields */
        if (!isset($data['login'])) {
            $this->errors[] = 'champ login vide';
        }
        if (!isset($data['password'])) {
            $this->errors[] = 'champ password vide';
        }
        /* tests de formats */
        if (isset($data['login'])) {
            if (empty($data['login'])) {
                $this->errors[] = 'champ login vide';
                // si name > 50 chars
            } else if (mb_strlen($data['login']) > 45) {
                $this->errors[] = 'champ login trop long (45max)';
            }
        }

        if (isset($data['password'])) {
            if (empty($data['password'])) {
                $this->errors[] = 'champ password vide';
                // si password < 8 chars
            } else if (mb_strlen($data['password']) < 8) {
                $this->errors[] = 'champ password trop court (8 min)';
            } else if (mb_strlen($data['password']) > 20) {
                $this->errors[] = 'champ password trop long (20 max)';
            }
        }

        if (isset($data['handle'])) {
            if (empty($data['handle'])) {
                $this->errors[] = 'champ handle vide';
                // si handle < 6 chars
            } else if (mb_strlen($data['handle']) < 6) {
                $this->errors[] = 'champ handle trop court (6 min)';
            } else if (mb_strlen($data['handle']) > 45) {
                $this->errors[] = 'champ handle trop long (20 max)';
            }
        }

        // TODO : created and modified

        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }

    // regarde si login existe deja dans la bdd
    private function loginExists($login = null)
    {
        if (!is_null($login)) {

            $dbh = Connection::get();
            $sql = "select count(id) from users where login = :login";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':login' => $login
            ));
            if ($sth->fetchColumn() > 0) {
                $this->errors[] = 'login deja pris blaireau';
                return true;
            }
        }
        return false;

    }

    public function findAll()
    {
        $dbh = Connection::get();
        $stmt = $dbh->query("select * from users");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $users;
    }

    // enregistrement user ou modification
    public function save($data)
    {
        // si les champs rentrés sont valident
        if ($this->validate($data)) {
            if(isset($data['id']) && !empty($data['id'])){
                // TODO : verifier que le login, si on choisit de le modifier n'est pas deja utilise par un autre user dans la bdd
                // update
                $dbh = Connection::get();
                // TODO : requete a modifier
                $sql = "update users set where id=:id limit 1";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':id' => $data['id']
                ));
            }elseif ($this->loginExists($data['login'])){
                return false;
            }
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            /* syntaxe avec preparedStatements */
            $dbh = Connection::get();
            // TODO : created and modified
            $sql = "insert into users (login, password, handle) values (:login, :password , :handle)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            // TODO : created and modified
            if ($sth->execute(array(
                ':login' => $data['login'],
                ':password' => $hashedPassword,
                ':handle' => $data['handle']
            ))) {
                return true;
            } else {
                // ERROR
                // put errors in $session
                $this->errors['pas reussi a creer le user'];
            }
        }
        return false;
    }

    public function login($data)
    {
        if ($this->validate($data)) {
            $dbh = Connection::get();
            $sql = "select password from users where login = :login limit 1";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':login' => $data['login']
            ));
            $storedPassword = $sth->fetchColumn();
            if (password_verify($data['password'], $storedPassword)) {
                return true;

            } else {
                // ERROR
                $this->errors[] = 'connexion impossible';
            }
        }
        return false;
    }

    public function delete($data){
        $dbh = Connection::get();
        $sql = "delete from users where id = :id limit 1";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':id' => $data['id']
        ));
    }
}