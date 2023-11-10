<?php

namespace App\classes\action;
use App\classes\Touite\GestionImage;
use App\classes\Touite\GestionTouite;
use App\classes\Touite\GestionUser;

class affichageProfilAction extends Action
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
        GestionUser::config();
        if(isset($_GET['username'])) {
            $profilsLsit = GestionUser::getUserByUsername($_GET['username']);
        } else {
            $profilsLsit = GestionUser::getUserByUsername($_SESSION['user']);
        }
        $txt .= "<div class='info'>";
        $txt .= "<p id='username'>Username : " . $profilsLsit['username'] . "</p>";
        $txt .= "<p id='lastname'>LastName : " . $profilsLsit['name'] . "</p>";
        $txt .= "<p id='firstname'>Firstname : " . $profilsLsit['firstname'] . "</p>";
        $txt .= "<form id='form_abo' method='post' action=''>";

        $id = GestionUser::getIdByUsername($_SESSION['user']);
        $id2 = GestionUser::getIdByUsername($profilsLsit['username']);
        $isUserLoggedIn = isset($_SESSION['user']);
        $isProfileOwner = ($isUserLoggedIn && $_SESSION['user'] == $profilsLsit['username']);
        $isNotSubscribed = (!$isProfileOwner && !GestionUser::isSubscribe($id, $id2));
        $isSubscribed = (!$isProfileOwner && GestionUser::isSubscribe($id, $id2));

        $aboButtonClass = $isNotSubscribed ? 'abo-button' : 'fake_abo-button disabled';
        $desaboButtonClass = $isSubscribed ? 'abo-button' : 'fake_abo-button disabled';


        $txt .= "<button class=$aboButtonClass type='submit' name='abo'>S'abonner</button>
            <button class=$desaboButtonClass type='submit' name='desabo'>Se désabonner</button>";

        $txt .= "</form></div>";
        $txt2 = '';
        $listes = GestionTouite::getTouitesByUser(GestionUser::getIdByUsername($profilsLsit['username']));
        foreach ($listes as $liste) {
            $txt2 .= "<div class='touite'>";
            if (strlen($liste['contentTouite']) > 100) {
                $txt2 .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "&page=profil\"><p>" . substr($liste['contentTouite'], 0, 100). "..." . "</p></a>";
            } else {
                $txt .= "<a href=\"?action=clickSurTouiteAction&touite=" . $liste['idTouite'] . "&page=profil\"><p>" . $liste['contentTouite']. "</p></a>";
            }
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                $txt2 .= "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            $txt2 .= "<p>" . $liste['dateTouite'] . "</p>";
            $txt2 .= "</div>";
        }



        $html = '';

        $txt3 = '';
        $id = GestionUser::getIdByUsername($_SESSION['user']);
        $abo = GestionUser::userAbonne($id);
        if ($abo == null) {
            $txt3 .= "<p>Vous n'avez pas d'abonnées</p>";
        } else {
            foreach ($abo as $a) {
                $txt3 .= '<div class="abo">';
                $txt3 .= "<a href=\"profil.php?username=" . $a . "\"><p>" . $a . "</p></a>";
                $txt3 .= '</div>';
            }
        }


        $moyenne = GestionTouite::getMoyenneImpression($id);
        if ($moyenne == null) {
            $txt5 = "<p>Vous n'avez pas de tweets</p>";
        } else {
            $txt5 = "<p>" . $moyenne . "</p>";
        }

        if ($this->http_method === 'GET') {
            $html .= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="src/classes/css/profil.css">
</head>
<body>
<div id="grid-container">
  <div id='Menu'>
    <div class='PartieMenu' id="logo">
      <img src="src/classes/Images/logo.png" alt="logo" id="logo" >
    </div>

    <div class='PartieMenu'>
      <a href="?action=seConnecterAction"><div class="profile-button">Accueil</div></a>
      <a href="?action=affichageAbonnementAction"><div class="profile-button">Vos Abonnements</div></a>
      <a href="?action=affichageTagsAction"><div class="profile-button">Vos Tags</div></a>
    </div>

    <div class='PartieMenu'>
      <a href="index.php""><div class="profile-button">Se déconnecter</div></a>
    </div>
  </div>

    <div id='Profils'>
        <div class="fake_profile-button">Profil</div>


        <div class="">
            $txt
        </div>
        
        $txt2
        
  </div>

    <div id='abonne_moyenne'>
        <div class='abonne'>
            <div class='abonne'>
                <div class="fake_profile-button">Vos abonnées</div>
                <div class="carré1">
                    $txt3
                </div>
            </div>
        </div>
        <div class='moyenne'>
            <div class="fake_profile-button">Moyenne d'impressions de vos tweets</div>
            <div class="carré2">
            $txt5
</div>
        </div>
    </div>
</div>
</body>
</html>
        

END;
        } else if ($this->http_method === 'POST' and isset($_POST['abo'])) {
            GestionUser::followUser($id,$id2);
            // On recharge la page pour que le bouton s'abonner devienne se désabonner
            header('Location: index.php?affichageProfilAction&username=' . $_GET['username']);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desabo'])) {
            GestionUser::unfollowUser($id,$id2);
            // On recharge la page pour que le bouton se désabonner devienne s'abonner
            header('Location: index.php?affichageProfilAction&username=' . $_GET['username']);
        }


        return $html;
    }
}