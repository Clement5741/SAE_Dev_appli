<?php

namespace App\classes\action;
use App\classes\Touite\GestionImage;
use App\classes\Touite\GestionTouite;

class clickSurTouiteAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        session_start();
        $liste = GestionTouite::getTouite($_GET['touite']);
        $score = GestionTouite::getScoreMoyenTouite($_GET['touite']);
        $txt = "";

        $txt .= "<div class=\"tweet-container\">";

        if (isset($_SESSION['user']) && !isset($_GET['page'])){
            $t = "index.php?action=seConnecterAction";
        }elseif (isset($_SESSION['user']) &&isset($_GET['page']) && $_GET['page'] == "profil"){
            $t = "index.php?action=affichageProfilAction&username=" . $_SESSION['user'];
        }elseif(isset($_SESSION['user']) &&isset($_GET['page']) && $_GET['page'] == "tag") {
            $t = "index.php?action=touiteTagAction&tag=" . $_GET['tag'] . "&page=affichage" . "&touite=" . $_GET['touite'];
        }else {
            $t = "index.php?action=pageDefaultAction";
        }
        $txt .="<a href=\"$t\" class=\"back-button\">&#8592;</a>
    <div class=\"tweet-author\"> " . $liste['name'] . " </div>";

        $txt .="<div class=\"tweet-text\">" . GestionTouite::afficherContenuTouiteAvecLienTag($liste['contentTouite'], $_GET['touite']) . "</div>";


        $t = GestionImage::getImageByTouite($liste['idTouite']);
        if ($t != null) {
            $txt .= "<img class=\"tweet-image\" src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
        }

        $txt .="<div class=\"tweet-date\">" . $liste['dateTouite'] . "</div>";
        $txt .= "<div class=\"tweet-text\">Score moyen : " . $score . "</div></div>";

        $html = '';
        $html .= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="src/classes/css/affichage_tweet.css">
</head>
<body>
$txt
</body>
</html>
END;
        return $html;
    }
}

