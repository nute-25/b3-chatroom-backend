<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/23/19
 * Time: 4:37 AM
 */

session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$chatrooms = isset($_SESSION['chatrooms']) ? $_SESSION['chatrooms'] : [];
$chatroom_messages = isset($_SESSION['chatroom_messages']) ? $_SESSION['chatroom_messages'] : [];

foreach ($chatrooms as $chatroom) {
    $chatroom_title = $chatroom->title;
}
?>

<html>
    <head>
        <title>Chatroom's messages of an user</title>
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
    </head>

    <body>
        <?php require_once('../components/nav.php') ?>
        <div class="container">

            <div class="row">
                <h2>Messages de l'user <?php echo $_SESSION['user_login']; ?> dans la chatroom <?php echo $chatroom_title; ?></h2>
                <table class="u-full-width">
                    <thead>
                    <tr>
                        <th>content</th>
                        <th>created</th>
                        <th>user_id</th>
                        <th>chatroom_id</th>
                        <th>delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($chatroom_messages as $chatroom_message) {
                        ?>
                        <tr>
                            <td><?= $chatroom_message->content ?></td>
                            <td><?= $chatroom_message->created ?></td>
                            <td><?= $chatroom_message->user_id ?></td>
                            <td><?= $chatroom_message->chatroom_id ?></td>
                            <td>
                                <a href="../controllers/messages_controller.php?action=delete&id=<?php echo $chatroom_message->id; ?>">
                                    <button>delete</button>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
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