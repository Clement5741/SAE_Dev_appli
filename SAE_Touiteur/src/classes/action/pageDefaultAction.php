<?php

namespace App\classes\action;
use App\classes\Touite\GestionImage;
use App\classes\Touite\GestionTouite;
use App\classes\Touite\GestionUser;

class pageDefaultAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        GestionTouite::config();
        $listes = GestionTouite::getTouites();
        $txt = '';
        foreach ($listes as $liste) {
            $idTouite = $liste['idTouite'];
            $idUser = GestionTouite::getIdUserByTouite($idTouite);
            $user = GestionUser::getUserbyId($idUser);
            $txt .= "<div class='touite'>";
            $txt .= "<p>" . $user['username'] . "</p>";
            if (strlen($liste['contentTouite']) > 100) {
                $txt .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "&page=sans\"><p>" . substr($liste['contentTouite'], 0, 100). "..." . "</p></a>";
            } else {
                $txt .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "&page=sans\"><p>" . $liste['contentTouite']. "</p></a>";
            }
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                $txt .= "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            $txt .= "<p>" . $liste['dateTouite'] . "</p>";
            $txt .= "</div>";
        }
        $html = '';
        $html .= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="src/classes/css/page_base_sans_connection.css">
</head>
<body>
<div id="grid-container">
    <div id='Menu'>
        <div class='PartieMenu' id="logo">
            <img src="src/classes/Images/logo.png" alt="logo" id="logo" >
        </div>

            <div class='PartieMenu'>
                <div class="profile-button-abo">Accueil</div></a>
                <a href="?action=connexion"><div class="profile-button-abo">Profil</div></a>
                <a href="?action=connexion"><div class="profile-button-abo">Tags</div></a>

            </div>

            <div class='PartieMenu'>
                <a href="?action=connexion"><div class="profile-button">Connexion</div></a>
                <a href="?action=inscription"><div class="profile-button">S'inscrire</div></a>
            </div>
    </div>
    <div id='Touites'>
        $txt
    </div>
    <div id="tags_influencer">
        <div id="tag">
            <div class="profile-button-abo">#Tags</div></a>
        </div>
        <div id="influencer">
            <div class="profile-button-abo ">#Influenceurs</div></a>
        </div>
    </div>
</div>
</body>
</html>

END;
        return $html;
    }
}
