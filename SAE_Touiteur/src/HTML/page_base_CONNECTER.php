<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}
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
            <img src="../Images/logo.png" alt="logo" id="logo" >
        </div>

        <div class='PartieMenu'>
            <div class="profile-button-abo">Accueil</div>
            <a href="profil.php"><div class="profile-button">Profil</div></a>
            <a href="page_ensemble_tags.php"><div class="profile-button">Tags</div></a>
            <a href="creationTouite.php"><div class="profile-button">TWEEEETTEEEERRRR</div></a>

        </div>

        <div class='PartieMenu'>
            <!--                <button href="../Compte/connexion.php" type="button">Connexion</button>-->
            <!--                <button href="../Compte/inscription.php" type="button">S'inscrire</button>-->
            <!--                <button href="../Compte/deconnexion.php" type="button">Se déconnecter</button>-->
            <!--<a href="../Compte/connexion.php"><div class="profile-button">Connexion</div></a>-->
            <!--<a href="../Compte/inscription.php"><div class="profile-button">S'inscrire</div></a>-->
            <a href="../Compte/deconnexion.php"><div class="profile-button">Se déconnecter</div></a>
        </div>
    </div>

    <div id='Touites'>
        <?php
        require_once __DIR__ . "/../Touite/GestionTouite.php";
        \Touite\GestionTouite::config();
        $listes = \Touite\GestionTouite::getTouites();
        foreach ($listes as $liste) {
            $idTouite = $liste['idTouite'];
            $idUser = \Touite\GestionTouite::getIdUserByTouite($idTouite);
            $user = \Touite\GestionUser::getUserbyId($idUser);

            echo "<div class='touite'>";
            echo "<p>" . $user['username'] . "</p>";
            echo "<p>" . $liste['contentTouite'] . "</p>";
            echo "<p>" . $liste['dateTouite'] . "</p>";
            echo "</div>";
        }
        ?>
    </div>

    <div id="tags_influencer">
        <div id="tag">
            <div class="profile-button-abo">#Tags</div>
        </div>
        <div id="influencer">
            <div class="profile-button-abo">#Influenceurs</div>
        </div>
    </div>
</div>
</body>
</html>