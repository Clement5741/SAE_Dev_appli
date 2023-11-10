<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}
use Touite\GestionUser;
use Touite\GestionTouite;
use Touite\GestionImage;

require_once '../Touite/GestionUser.php';
require_once '../Touite/GestionTouite.php';
require_once '../Touite/GestionImage.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/profil.css">
</head>
<body>
<div id="grid-container">
  <div id='Menu'>
    <div class='PartieMenu' id="logo">
      <img src="../Images/logo.png" alt="logo" id="logo" >
    </div>

    <div class='PartieMenu'>
      <a href="page_base_CONNECTER.php"><div class="profile-button">Accueil</div></a>
      <div class="fake_profile-button">Profil</div></a>
      <a href="affichage_abonnements.php"><div class="profile-button">Vos Abonnements</div></a>
      <a href="affichage_tags.php?username=<?php echo $_SESSION['user'];?>"><div class="profile-button">Vos Tags</div></a>
    </div>

    <div class='PartieMenu'>
      <!--                <button href="../Compte/connexion.php" type="button">Connexion</button>-->
      <!--                <button href="../Compte/inscription.php" type="button">S'inscrire</button>-->
      <!--                <button href="../Compte/deconnexion.php" type="button">Se déconnecter</button>-->

      <a href="../Compte/deconnexion.php"><div class="profile-button">Se déconnecter</div></a>
    </div>
  </div>

    <div id='Profils'>
        <div class="fake_profile-button">Profil</div>


        <div class="">
            <?php
            if(isset($_GET['username'])) {
                $profilsLsit = GestionUser::getUserByUsername($_GET['username']);
            } else {
                $profilsLsit = GestionUser::getUserByUsername($_SESSION['user']);
            }
            echo "<div class='info'>";

            echo "<p id='username'>Username : " . $profilsLsit['username'] . "</p>";
            echo "<p id='lastname'>LastName : " . $profilsLsit['name'] . "</p>";
            echo "<p id='firstname'>Firstname : " . $profilsLsit['firstname'] . "</p>";

            echo "<form id='form_abo' method='post' action=''>";
            $id = GestionUser::getIdByUsername($_SESSION['user']);
            $id2 = GestionUser::getIdByUsername($profilsLsit['username']);
            $isUserLoggedIn = isset($_SESSION['user']);
            $isProfileOwner = ($isUserLoggedIn && $_SESSION['user'] == $profilsLsit['username']);
            $isNotSubscribed = (!$isProfileOwner && !GestionUser::isSubscribeUser($id, $id2));
            $isSubscribed = (!$isProfileOwner && GestionUser::isSubscribeUser($id, $id2));

            $aboButtonClass = $isNotSubscribed ? 'abo-button' : 'fake_abo-button disabled';
            $desaboButtonClass = $isSubscribed ? 'abo-button' : 'fake_abo-button disabled';


            echo "<button class=$aboButtonClass type='submit' name='abo'>S'abonner</button>
            <button class=$desaboButtonClass type='submit' name='desabo'>Se désabonner</button>";

            echo "</form></div>";
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abo'])) {
                    GestionUser::followUser($id,$id2);
                    // On recharge la page pour que le bouton s'abonner devienne se désabonner
                    header('Location: profil.php?username=' . $_GET['username']);
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desabo'])) {
                    GestionUser::unfollowUser($id,$id2);
                    // On recharge la page pour que le bouton se désabonner devienne s'abonner
                    header('Location: profil.php?username=' . $_GET['username']);
                }
            ?>
        </div>

        <?php
        $listes = GestionTouite::getTouitesByUser(GestionUser::getIdByUsername($_GET['username']));
        foreach ($listes as $liste) {
            echo "<div class='touite'>";
            if (strlen($liste['contentTouite']) > 100) {
                echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=profil\"><p>" . substr($liste['contentTouite'], 0, 100). "..." . "</p></a>";
            } else {
                echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=profil\"><p>" . $liste['contentTouite']. "</p></a>";
            }
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                echo "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            echo "<p>" . $liste['dateTouite'] . "</p>";
            echo "</div>";
        }
        ?>


    </div>

    <div id='abonne_moyenne'>
        <div class='abonne'>
            <div class='abonne'>
                <div class="fake_profile-button">Vos abonnées</div>
                <div class="carré1">
                    <?php


                    $id = GestionUser::getIdByUsername($_SESSION['user']);

                    GestionUser::userAbonne($id);

                    ?>
                </div>
            </div>
        </div>
        <div class='moyenne'>
            <div class="fake_profile-button">Moyenne d'impressions de vos tweets</div>
            <div class="carré2">Faudra mettre la moyenne ici</div>
        </div>
    </div>
</div>
</body>
</html>
