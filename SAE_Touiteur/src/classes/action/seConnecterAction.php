<?php

namespace App\classes\action;

use App\classes\Touite\GestionImage;
use App\classes\Touite\GestionTag;
use App\classes\Touite\GestionTouite;
use App\classes\Touite\GestionUser;

class seConnecterAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
        }
        $txt = '';
        $txt2 = '';
        GestionTouite::config();
        $listes = GestionTouite::getTouites();
        $txt .= "<div class='Titre'> TOUITTER </div>";

        foreach ($listes as $liste) {
            $txt .= "<div class='touite'>";
            $txt .= "<a href=\"?action=affichageProfilAction&username=" . $liste['username'] . "\"><div class='nom'>" . $liste['username'] . "</div></a>";
            if (strlen($liste['contentTouite']) > 100) {
                $txt .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "\"><div class = 'texteTouite'>" . $liste['contentTouite']. "</div></a>";
            } else {
                $txt .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "\"><div class = 'texteTouite'>" . $liste['contentTouite']. "</div></a>";
            }
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                $txt .= "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            $txt .= "<div class='date'>" . $liste['dateTouite'] . "</div>";

            $score = GestionTouite::getScoreMoyenTouite($liste['idTouite']);

            $isLiked = GestionTouite::isLiked($liste['idTouite'], GestionUser::getIdByUsername($_SESSION['user']));
            $isDisLiked = GestionTouite::isDisliked($liste['idTouite'], GestionUser::getIdByUsername($_SESSION['user']));


            // Si l'utilisateur a déjà liké le touite, on désactive le bouton like
            $boutonMoins = !$isDisLiked ? "boutonMoins" : "fake_bouton disabled";
            $boutonPlus = !$isLiked ? "boutonPlus" : "fake_bouton disabled";

            $txt .="<form method='post' id='noter' action=''>";
            $txt .="<input type='hidden' name='idTouite' value='" . $liste['idTouite'] . "'>";
            $txt .="<button class=$boutonPlus name='like'>&#128077;</button>";
            $txt .="<div class='notationPlus'> influence </div>";
            $txt .="<div class='notationMoyenne'> $score</div>";
            $txt .="<button class=$boutonMoins name='dislike'>&#128078;</button>";
            $txt .="</form>";
            $txt .="</div>";
        }

        $tagTendance = GestionTag::getTagTendances();

        $id = GestionUser::getIdByUsername($_SESSION['user']);

        if ($tagTendance != null) {
            foreach ($tagTendance as $tag) {
                $txt2 = "<a href=\"?action=touiteTagAction&tag=" . $tag['labelTag'] . "&page=connect\"><div class='affich'> - #" . $tag['labelTag'] . "</div></a>";
            }
        }


        $userTendance = GestionUser::getUserTendances();

        $id = GestionUser::getIdByUsername($_SESSION['user']);

        if ($userTendance != null) {
            foreach ($userTendance as $user) {
                $txt3 = "<a href=\"?action=affichageProfilAction&username=" . $user['username'] . "\"><div class='affich'>- " . $user['username'] . "</div></a>";
            }
        }

        $html = '';
        if ($this->http_method==='GET') {
            $html .= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="src/classes/css/page_base_CONNECTER.css">
</head>
<body>
<div id="grid-container">
    <div id='Menu'>
        <div class='PartieMenu' id="logo">
            <img src="src/classes/Images/logo.png" alt="logo" id="logoImage">
        </div>
        
            
         <script>
            const images = [
                "src/classes/Images/logo.png",
                "src/classes/Images/logo1.png",];
            let image = 0;
            function changeImage() {
                const logoImage = document.getElementById("logoImage");
                logoImage.src = images[image];
                image = (image + 1) % images.length;
            }
            setInterval(changeImage, 1000);
        </script>

        <div class='PartieMenu'>
            <a href="?action=affichageProfilAction"><div class="profile-button">Profil</div></a>
            <a href="?action=afficherToutLesTag"><div class="profile-button">Tags</div></a>
            <a href="?action=touiterAction"><div class="profile-button">TWEEEETTEEEERRRR</div></a>

        </div>

        <div class='PartieMenu'>
            <a href="index.php"><div class="profile-button">Se déconnecter</div></a>
        </div>
    </div>

    <div id='Touites'>
        $txt
    </div>

    <div id="tags_influencer">
        <div id="tag">
            <div class="profile-button-abo">#Tags
            </div>
            $txt2
        </div>
        
        <div id="influencer">
            <div class="profile-button-abo">#Influenceurs</div>
            $txt3
        </div>
    </div>
</div>
</body>
</html>

END;
        }
        else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
            $idTouite = $_POST['idTouite'];
            GestionTouite::likerTouite($idTouite);
            header('Location: index.php?action=seConnecterAction');
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dislike'])) {
            $idTouite = $_POST['idTouite'];
            GestionTouite::dislikerTouite($idTouite);
            header('Location: index.php?action=seConnecterAction');
        }


        return $html;
    }

}
