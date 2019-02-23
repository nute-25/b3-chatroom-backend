<?php
/**
 * Created by PhpStorm.
 * User: osboxes
 * Date: 2/22/19
 * Time: 7:24 PM
 */

session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$chatrooms = isset($_SESSION['chatrooms']) ? $_SESSION['chatrooms'] : [];
?>

<html>
<head>
    <title>User's Chatroom</title>
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
        <h2>Chatrooms</h2>
        <a href="../views/chatrooms_register.php">
            <button>register chatroom</button>
        </a>
        <table class="u-full-width">
            <thead>
            <tr>
                <th>title</th>
                <th>user_id</th>
                <th>created</th>
                <th>modified</th>
                <th>update</th>
                <th>messages</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($chatrooms as $chatroom) {
                ?>
                <tr>
                    <td><?= $chatroom->title ?></td>
                    <td><?= $chatroom->user_id ?></td>
                    <td><?= $chatroom->created ?></td>
                    <td><?= $chatroom->modified ?></td>
                    <td>
                        <a href="../views/chatrooms_modification.php?title=<?php echo $chatroom->title; ?>">
                            <button>update</button>
                        </a>
                    </td>
                    <td>
                        <a href="../controllers/chatrooms_controller.php?action=displayMessages&title=<?php echo $chatroom->title; ?>">
                            <button>display</button>
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