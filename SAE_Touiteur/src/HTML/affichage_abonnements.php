<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}

use Touite\GestionUser;

require_once '../Touite/GestionUser.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/affichage_tweet.css">
</head>
<body>
<div class="tweet-container">
    <main>
        <a href="profil.php?username=<?php echo $_SESSION['user']; ?>" class="back-button">&#8592;</a> <!---&#8592 represent the arrow-->
        <?php

        $id = GestionUser::getIdByUsername($_SESSION['user']);

        echo '<p><strong>Vos abonnements : </strong></p>';
        echo '<div class="abo-container">';
        $abo = GestionUser::abonnementsUser($id);
        foreach ($abo as $a) {
            echo '<div class="abo">';
            echo "<a href=\"profil.php?username=" . $a . "\"><p>" . $a . "</p></a>";
            echo '</div>';
        }
        echo '</div>';

        ?>
    </main>
</div>
</body>
</html>

