<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}

function reload()
{
    header('Location: page_base_CONNECTER.php');
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
            <img src="../Images/logo.png" alt="logo" id="logo">
        </div>

        <div class='PartieMenu'>
            <div class="profile-button-abo">Accueil</div>
            <a href="profil.php?username=<?php echo $_SESSION['user']; ?>">
                <div class="profile-button">Profil</div>
            </a>
            <a href="page_ensemble_tags.php">
                <div class="profile-button">Tags</div>
            </a>
            <a href="creationTouite.php">
                <div class="profile-button">TOUITER</div>
            </a>
        </div>

        <div class='PartieMenu'>
            <a href="../Compte/deconnexion.php">
                <div class="profile-button">Se déconnecter</div>
            </a>
        </div>
    </div>

    <div id='Touites'>
        <?php
        $listes = GestionTouite::getTouites();
        foreach ($listes as $liste) {
            echo "<div class='touite'>";
            echo "<a href=\"profil.php?username=" . $liste['username'] . "\"><p>" . $liste['username'] . "</p></a>";
            if (strlen($liste['contentTouite']) > 100) {
                echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "\"><p>" . substr($liste['contentTouite'], 0, 100) . "..." . "</p></a>";
            } else {
                echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "\"><p>" . $liste['contentTouite'] . "</p></a>";
            }
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                echo "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            echo "<p>" . $liste['dateTouite'] . "</p>";

            $likeTouite = GestionTouite::likerTouite($liste['idTouite']);
            $dislikerTouite = GestionTouite::dislikerTouite($liste['idTouite']);

            $score = GestionTouite::getScoreMoyenTouite($liste['idTouite']);


            //            $boutonMoins = $isLiked ? "boutonMoins" : "fake_boutonMoins";
            //            $boutonPlus = $isNotLiked ? "boutonPlus" : "fake_boutonPlus";


            echo "<div id='CarréNotation'>";
            echo "<button class='boutonMoins'> &#128077;</button>";
            echo "<div class='notationPlus'> influence </div>";
            echo "<div class='notationMoyenne'> $score</div>";
            echo "<button class='boutonPlus'> &#128078;</button>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <div id="tags_influencer">
        <div id="tag">
            <div class="profile-button-abo">#Tags</div>
            <?php
            $tagTendance = GestionTag::getTagTendances();

            $id = GestionUser::getIdByUsername($_SESSION['user']);

            if ($tagTendance != null) {
                foreach ($tagTendance as $tag) {
                    echo "<a href=\"touiteTag.php?tag=" . $tag['labelTag'] . "&page=connect\"><div class='affich'>#" . $tag['labelTag'] . "</div></a>";
                }
            }
            ?>
        </div>
        <div id="influencer">
            <div class="profile-button-abo">#Influenceurs</div>
            <?php
            $userTendance = GestionUser::getUserTendances();

            $id = GestionUser::getIdByUsername($_SESSION['user']);

            if ($userTendance != null) {
                foreach ($userTendance as $user) {
                    echo "<a href=\"profil.php?username=" . $user['username'] . "\"><div class='affich'>" . $user['username'] . "</div></a>";

                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>