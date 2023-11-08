<?php
SESSION_START();
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
    <div class='PartieMenu'>
      <p>[emplacement du logo]</p>
    </div>

    <div class='PartieMenu'>
      <a href="page_base_CONNECTER.php"><div class="profile-button">Accueil</div></a>
      <div class="profile-button">Profil</div></a>
      <a href="affichage_tags.php"><div class="profile-button">Tags</div></a>

    </div>

    <div class='PartieMenu'>
      <!--                <button href="../Compte/connexion.php" type="button">Connexion</button>-->
      <!--                <button href="../Compte/inscription.php" type="button">S'inscrire</button>-->
      <!--                <button href="../Compte/deconnexion.php" type="button">Se déconnecter</button>-->
      <a href="../Compte/connexion.php"><div class="profile-button">Connexion</div></a>
      <a href="../Compte/inscription.php"><div class="profile-button">S'inscrire</div></a>
      <a href="#" onclick="return false"><div class="profile-button">Se déconnecter</div></a>
    </div>
  </div>

    <div id='Profils'>
        <div class="profile-button">Profil</div>

        <div class="">
            <?php
            $_SESSION['user'] = "test";
            echo "{$_SESSION['user']}";
            ?>
        </div>
        <div class="">ABONNEMENT A VOIR </div>

        <div class="">Abonnement de : A REVOIR LE FIRST NAME ET LE NAME
<!--            --><?php
//            echo GestionUser::getUserByUsername($_SESSION['user'])['name']; echo " ";
//            GestionUser::getUserByUsername($_SESSION['user'])['firstname'];
//
//            ?>
        </div>

    </div>

    <div id='right-panel'>
        <div class='abonne'>
            <div class="profile-button">Vos abonnées</div>
        </div>
        <div class='moyenne'>
            <div class="profile-button">Moyenne d'impressions de vos tweets</div>
            <div class="carré"></div>
        </div>
    </div>
</div>
</body>
</html>
