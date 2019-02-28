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
        if (!isset(/*$data['login']*/$data->login)) {
            $this->errors[] = 'Empty login field';
        }
        if (!isset(/*$data['password']*/$data->password)) {
            $this->errors[] = 'Empty password field';
        }
        /* tests de formats */
        if (isset(/*$data['login']*/$data->login)) {
            if (empty(/*$data['login']*/$data->login)) {
                $this->errors[] = 'Empty login field';
                // si name > 50 chars
            } else if (mb_strlen(/*$data['login']*/$data->login) > 45) {
                $this->errors[] = 'Login field is too long (45max)';
            }
        }

        if (isset(/*$data['password']*/$data->password)) {
            if (empty(/*$data['password']*/$data->password)) {
                $this->errors[] = 'Empty password field';
                // si password < 8 chars
            } else if (mb_strlen(/*$data['password']*/$data->password) < 8) {
                $this->errors[] = 'Password field is too short (8 min)';
            } else if (mb_strlen(/*$data['password']*/$data->password) > 20) {
                $this->errors[] = 'Password field is too long (20 max)';
            }
        }

        if (isset(/*$data['handle']*/$data->handle)) {
            if (empty(/*$data['handle']*/$data->handle)) {
                $this->errors[] = 'Empty handle field';
                // si handle < 6 chars
            } else if (mb_strlen(/*$data['handle']*/$data->handle) < 6) {
                $this->errors[] = 'Handle field is too short (6 min)';
            } else if (mb_strlen(/*$data['handle']*/$data->handle) > 45) {
                $this->errors[] = 'Handle field is too long (20 max)';
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

    // recupere l'ensemble des utilisateurs existants dans la bdd
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
                // update
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

    // modifier les champs user
    public function update($data)
    {
        // si les champs rentrés sont valident
        if ($this->validate($data)) {
            /*if(isset($data['id']) && !empty($data['id'])){
                // update
            }elseif ($this->loginExists($data['login'])){
                return false;
            }*/

            // update
            $dbh = Connection::get();
            // select password of user in bdd
            $sql = "select password from users where id = :id limit 1";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                /*':id' => $_SESSION['user_id']*/
                ':id' => $data->user_id
            ));
            $storedPassword = $sth->fetchColumn();

            // hash password saisi par l'utilisateur
            $hashedPassword = password_hash(/*$data['password']*/$data->password, PASSWORD_DEFAULT);
            if ($hashedPassword !== $storedPassword) {
                $sql = "update users set login=:login, password=:password, handle=:handle, modified=:modified where id=:id limit 1";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                if ($sth->execute(array(
                    /*':login' => $data['login'],*/
                    ':login' => $data->login,
                    ':password' => $hashedPassword,
                    /*':handle' => $data['handle'],*/
                    ':handle' => $data->handle,
                    ':modified' => date("Y-m-d H:i:s"),
                    /*':id' => $_SESSION['user_id']*/
                    ':id' => $data->user_id
                ))) {
                    return true;
                } else {
                    // ERROR
                    // put errors in $session
                    $this->errors['Fail to update user'];
                }
            } else {
                $sql = "update users set login=:login, handle=:handle where id=:id limit 1";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                if ($sth->execute(array(
                    /*':login' => $data['login'],*/
                    ':login' => $data->login,
                    /*':handle' => $data['handle'],*/
                    ':handle' => $data->handle,
                    /*':id' => $_SESSION['user_id']*/
                    ':id' => $data->user_id
                ))) {
                    return true;
                } else {
                    // ERROR
                    // put errors in $session
                    $this->errors['Fail to update user'];
                }
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
                /*':login' => $data['login']*/
                ':login' => $data->login
            ));
            $storedPassword = $sth->fetchColumn();
            if (password_verify(/*$data['password']*/$data->password, $storedPassword)) {
                return true;

            } else {
                // ERROR
                $this->errors[] = 'Cannot connect';
            }
        }
        return false;
    }

    // recupere les données de l'utilisateur ayant réussi à se connecter
    public function retrieveUser($data)
    {
        $dbh = Connection::get();
        $sql = "select * from users where login = :login limit 1";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(
            ':login' => $data->login
        ));
        // recupere l'user et fout le resultat dans une variable sous forme de tableau de tableaux
        $user = $sth->fetch();
        return $user;
    }

    public function delete($data){
        $dbh = Connection::get();
        $sql = "delete from users where id = :id limit 1";
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