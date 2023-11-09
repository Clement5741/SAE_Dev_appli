<!DOCTYPE html>
<html>
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/affichage_tweet.css">
</head>
<body>
<div class="tweet-container">
    <a href="profil.php" class="back-button">&#8592;</a> <!---&#8592 represent the arrow-->
    <main>
        <?php
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: accueil.html');
        }

        use Touite\GestionUser;
        use Touite\GestionTag;

        require_once '../Touite/GestionUser.php';
        require_once '../Touite/GestionTag.php';

        $id = GestionUser::getIdByUsername($_SESSION['user']);

        echo '<p><strong>Vos tags : </strong></p>';
        echo '<div class="tags-container">';
        GestionTag::abonnementsTag($id);
        echo '</div>';

        ?>
    </main>
</div>
</body>
</html>
