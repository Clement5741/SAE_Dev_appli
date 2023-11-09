<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}

use Touite\GestionImage;
use Touite\GestionTag;
use Touite\GestionTouite;
use Touite\GestionUser;

require_once "../Touite/GestionImage.php";
require_once "../Touite/GestionTag.php";
require_once "../Touite/GestionTouite.php";
require_once "../Touite/GestionUser.php";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/page_base_CONNECTER.css">
</head>
<body>
<div id="grid-container">
    <div id='Menu'>
        <div class='PartieMenu' id="logo">
            <img src="../Images/logo.png" alt="logo" id="logo" >
        </div>

        <div class='PartieMenu'>
            <div class="profile-button-abo">Accueil</div>
            <a href="profil.php"><div class="profile-button">Profil</div></a>
            <a href="affichage_tags.php"><div class="profile-button">Tags</div></a>
            <a href="creationTouite.php"><div class="profile-button">TWEEEETTEEEERRRR</div></a>

        </div>

        <div class='PartieMenu'>
            <!--                <button href="../Compte/connexion.php" type="button">Connexion</button>-->
            <!--                <button href="../Compte/inscription.php" type="button">S'inscrire</button>-->
            <!--                <button href="../Compte/deconnexion.php" type="button">Se déconnecter</button>-->
            <!--<a href="../Compte/connexion.php"><div class="profile-button">Connexion</div></a>-->
            <!--<a href="../Compte/inscription.php"><div class="profile-button">S'inscrire</div></a>-->
            <a href="../Compte/deconnexion.php"><div class="profile-button">Se déconnecter</div></a>
        </div>
    </div>

    <div id='Touites'>
        <?php
        $listes = GestionTouite::getTouites();
        foreach ($listes as $liste) {
            echo "<div class='touite'>";
            echo "<a href=\"profil.php?username=". $liste['username'] ."\"><p>" . $liste['username'] . "</p></a>";
            echo "<a href=\"affichage_tweet.html\"><p>" . $liste['contentTouite'] . "</p></a>";
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                echo "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            echo "<p>" . $liste['dateTouite'] . "</p>";
            echo "</div>";
        }
        ?>
    </div>

    <div id="tags_influencer">
        <div id="tag">
            <div class="profile-button-abo">#Tags</div>
            <?php
            $tagTendance = GestionTag::getTagTendances();
            if ($tagTendance != null) {
                foreach ($tagTendance as $tag) {
                    echo "<div class='affich'>#" . $tag['labelTag'] . "</div>";
                }
            }
            ?>
        </div>
        <div id="influencer">
            <div class="profile-button-abo">#Influenceurs</div>
            <?php
            $tagTendance = GestionUser::getUserTendances();
            if ($tagTendance != null) {
                foreach ($tagTendance as $tag) {
                    echo "<div class='affich'>" . $tag['idUser2'] . "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>