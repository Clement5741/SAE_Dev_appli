<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/page_base.css">
</head>
<body>
<div id="grid-container">
    <div id='Menu'>
            <div class='PartieMenu'>
                <p>[emplacement du logo]</p>
            </div>

            <div class='PartieMenu'>
                <a href="profil.html"><div class="profile-button">Profil</div></a>
                <p>Votre Mur</p>
                <p>#Tags</p>
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

    <div id='Touites'>
        <p>[emplacement des touites]</p>
    </div>

    <div id="tags_influencer">
        <div id="tag">
            <h1>#Tags</h1>
        </div>
        <div id="influencer">
            <h1>#Influenceur</h1>
        </div>
    </div>
</div>
</body>
</html>
