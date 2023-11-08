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
      <div class="fake_profile-button">Profil</div></a>
      <a href="affichage_tags.php"><div class="profile-button">Tags</div></a>
    </div>

    <div class='PartieMenu'>
      <!--                <button href="../Compte/connexion.php" type="button">Connexion</button>-->
      <!--                <button href="../Compte/inscription.php" type="button">S'inscrire</button>-->
      <!--                <button href="../Compte/deconnexion.php" type="button">Se déconnecter</button>-->

      <a href="#" onclick="return false"><div class="profile-button">Se déconnecter</div></a>
    </div>
  </div>

    <div id='Profils'>
        <div class="fake_profile-button">Profil</div>

        <div class="">
            <?php
            $_SESSION['user'] = "testD USER";
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

    <div id='abonne_moyenne'>
        <div class='abonne'>
            <div class="fake_profile-button">Vos abonnées</div>
            <div class="carré1">Faudra mettre les abonnée ici
            ezezez
            ezezeez
            zezezeze
            ezezezez
            ezezezez
            ezezezez
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
