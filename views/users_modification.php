<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 11:23 AM
 */
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
print_r($_SESSION['users'][0]['id']);
/*require_once('../classes/Connection.class.php');
$dbh = Connection::get();
$sql = "select login, password, handle from users where id = :id limit 1";
$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(
    ':id' => $data['id']
));
$res = $sth->fetchAll();
print_r($res);*/
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
                        <input type="text" id="userLogin" name="login" value="<?php echo !empty($_POST['login']) ? ($_POST['login']) : '' ?>">
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