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
      <img src="../images/logo.png" alt="logo" id="logo" >
    </div>

    <div class='PartieMenu'>
      <a href="page_base_CONNECTER.php"><div class="profile-button">Accueil</div></a>
      <div class="fake_profile-button">Profil</div></a>
      <a href="affichage_abonnements.php"><div class="profile-button">Vos Abonnements</div></a>
      <a href="affichage_tags.php"><div class="profile-button">Vos Tags</div></a>
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
            GestionUser::config();
            $ProfilsLsit = GestionUser::getUserByUsername($_SESSION['user']);
            echo "<div class='info'>";
            echo "<p>" . $ProfilsLsit['username'] . "</p>";
            echo "<p>" . $ProfilsLsit['firstname'] . "</p>";
            echo "<p>" . $ProfilsLsit['name'] . "</p>";
            echo "</div>";
            ?>
        </div>


        <?php
        $listes = GestionTouite::getTouitesByUser(GestionUser::getIdByUsername($_SESSION['user']));
        foreach ($listes as $liste) {
            echo "<div class='touite'>";
            echo "<p>" . $liste['contentTouite'] . "</p>";
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
