<?php

namespace App\classes\action;
use App\classes\Touite\GestionTag;
use App\classes\Touite\GestionTouite;
use App\classes\Touite\GestionUser;

class touiteTagAction extends Action
{

    public function __construct()
    {
        parent::__construct();
    }


    public function execute(): string
    {
        session_start();
        $html = '';
        $txt = '';
        $listes = GestionTouite::getTouitesByTag($_GET['tag']);

        $txt .= "<h1>Voici la page du Tag : #" . $_GET['tag'] . "</h1>";

//Retourner à la page d'avant
        if (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "connect") {
            $t = "index.php?action=seConnecterAction";
        } elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "affichage") {
            $t = "?action=clickSurTouiteAction&username=" . $_SESSION['user'] . "&touite=" . $_GET['touite'];
        } elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "tag") {
            $t = "?action=touiteTagAction&?tag=" . $_GET['tag'];
        } elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "vostags") {
            $t = "?action=affichageTagAction&username=" . $_SESSION['user'];
        } elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "enstag") {
            $t = "page_ensemble_tags.php";
        }elseif (isset($_SESSION['user']) ) {
            $t = "index.php?action=seConnecterAction";
        }
        else {
            $t = "index.php?action=pageDefaultAction";
        }

        $txt .="<a href=\"$t\" class=\"back-button\">&#8592;</a> <!---&#8592 represent the arrow-->";







// Abonnement au tag
        if (isset($_SESSION['user'])) {
            $txt .= "<form id='form_abo' method='post' action=''>";
            $idTag = GestionTag::getidTagByLabel($_GET['tag']);
            $idUser = GestionUser::getIdByUsername($_SESSION['user']);
            $isUserLoggedIn = isset($_SESSION['user']);
            $isUserSubscribed = ($isUserLoggedIn && GestionTag::isFollowedTag($idUser, $idTag));
            $isUserNotSubscribed = ($isUserLoggedIn && !GestionTag::isFollowedTag($idUser, $idTag));

            $aboButtonClass = $isUserNotSubscribed ? 'abo-button' : 'fake_abo-button disabled';
            $desaboButtonClass = $isUserSubscribed ? 'abo-button' : 'fake_abo-button disabled';

            $txt .=
                "<button class=$aboButtonClass type='submit' name='abo' >S'abonner</button></>
                <button class=$desaboButtonClass type='submit' name='desabo'>Se désabonner</button>";
            $txt .= "</form>";


        }


        $txt .= "<h2>Voici les touites avec le tag : #" . $_GET['tag'] . "</h2>";
        foreach ($listes as $liste) {
            $txt .= "<div id='touite-info'>";

            $idTouite = $liste['idTouite'];
            $idUser = GestionTouite::getIdUserByTouite($idTouite);
            $user = GestionUser::getUserbyId($idUser);

            $txt .= "<div class='nom'>";
            $txt .= "<p>" . $user['username'] . "</p>";
            $txt .= "</div>";
            $txt .= "<div class='tag'>";
            if (strlen($liste['contentTouite']) > 100) {
                $txt .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "&page=tag&tag=" . $_GET['tag'] . "\"><p>" . substr($liste['contentTouite'], 0, 100) . "..." . "</p></a>";
            } else {
                $txt .="<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "&page=tag&tag=" . $_GET['tag'] . "\"><p>" . $liste['contentTouite'] . "</p></a>";
            }
            $txt .= "</div>";
            $txt .= "<div class='date'>";
            $txt .= "<p>" . $liste['dateTouite'] . "</p>";
            $txt .= "</div>";
            $txt .= "</div>";
        }


        if ($this->http_method === 'GET') {
            $html .= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Affichage Touite d'un Tag</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="src/classes/css/touiteTag.css">
</head>
<body>
$txt
</body>
</html>
END;
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abo'])) {
        GestionTag::followTag($idUser, $idTag);
        // On recharge la page pour que le bouton s'abonner devienne se désabonner
        header('Location: index.php?action=touiteTagAction&tag=' . $_GET['tag'] . '&page=' . $_GET['page']);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desabo'])) {
        GestionTag::unfollowTag($idUser, $idTag);
        // On recharge la page pour que le bouton se désabonner devienne s'abonner
        header('Location: index.php?action=touiteTagAction&tag=' . $_GET['tag'] . '&page' . $_GET['page']);
    }
        return $html;
    }
}

