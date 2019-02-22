<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 11:23 AM
 */
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$users = isset($_SESSION['users']) ? $_SESSION['users'] : [];

foreach ($users as $user) {
    $_SESSION['user_id'] = $user->id;
    require_once('../classes/Connection.class.php');
    $dbh = Connection::get();
    $sql = "select login, handle from users where id = :id limit 1";
    $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(
        ':id' => $user->id
    ));


    try {
        $result = $sth->setFetchMode(PDO::FETCH_NUM);
        while ($row = $sth->fetch()) {
            $login = $row[0];
            $handle = $row[1];
        }
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

$_POST['login'] = $login;
$_POST['handle'] = $handle;

?>

<html>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://puteborgne.sexy/_css/normalize.css"/>
    <link rel="stylesheet" href="https://puteborgne.sexy/_css/skeleton.css"/>
    <style>
        fieldset {
            border: 0.25rem solid rgba(225, 225, 225, 0.5);
            border-radius: 4px;
            padding: 1rem 2rem;
        }

        .errors {
            color: #ff5555;
        }
    </style>

    <body>
    <?php require_once('../components/nav.php') ?>

        <div class="container">

            <div class="row">

                <ul class="errors">
                    <?php
                    foreach ($errors as $error) {
                        echo("<li>" . $error . "</li>");
                    }
                    ?>
                </ul>

                <form method="post" action="../controllers/users_controller.php?action=update" id="userUpdateForm">
                    <fieldset>
                        <legend>user update</legend>
                        <label for="userLogin">login</label>
                        <input type="text" id="userLogin" name="login" value="<?php echo $login ?>">
                        <label for="userPassword">password</label>
                        <input type="password" id="userPassword" name="password" value="">
                        <label for="userHandle">handle</label>
                        <input type="text" id="userHandle" name="handle" value="<?php echo !empty($_POST['handle']) ? ($_POST['handle']) : '' ?>">
                    </fieldset>
                    <input type="submit" value="Envoyer" class="button-primary">
                </form>
            </div>

            <div class="row">
                <div class="column">
                    $_SESSION
                    <pre><?php print_r($_SESSION) ?></pre>
                </div>

            </div>

            <div class="row">
                <div class="one-half column">
                    $_GET
                    <pre><?php print_r($_GET) ?></pre>
                </div>
                <div class="one-half column">
                    $_POST :
                    <pre><?php print_r($_POST) ?></pre>
                </div>
            </div>

        </div>
    </body>
</html>