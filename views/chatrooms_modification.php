<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 8:43 PM
 */
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

// $_SESSION['chatroom_id'] = $chatroom->id;
$_SESSION['chatroom_title'] = $_GET['title'];
require_once('../classes/Connection.class.php');
$dbh = Connection::get();
$sql = "select * from chatrooms where title = :title limit 1";
$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(
    ':title' => $_SESSION['chatroom_title']
));
$chatrooms = $sth->fetchAll(PDO::FETCH_CLASS);


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
        <?php foreach ($chatrooms as $chatroom) {
        ?>
            <form method="post" action="../controllers/chatrooms_controller.php?action=update&title=<?php echo $chatroom->title?>" id="chatroomsUpdateForm">
                <fieldset>
                        <legend>chatroom register</legend>
                        <label for="chatroomTitle">title</label>
                        <input type="text" id="chatroomTitle" name="title" value="<?php echo $chatroom->title ?>">
                </fieldset>
                <input type="submit" value="Envoyer" class="button-primary">
            </form>
        <?php } ?>

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
