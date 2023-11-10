<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/page_base_sans_connection.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
</head>
<body>
<div id="grid-container">
    <div id='Menu'>
        <div class='PartieMenu' id="logo">
            <img src="../Images/logo.png" alt="logo" id="logoImage" >
        </div>
        <script>
            const images = [
                "../Images/logo.png",
                "../Images/logo1.png",];
            let image = 0;
            function changeImage() {
                const logoImage = document.getElementById("logoImage");
                logoImage.src = images[image];
                image = (image + 1) % images.length;
            }
            setInterval(changeImage, 1000);
        </script>

            <div class='PartieMenu'>
                <div class="profile-button-abo">Accueil</div></a>
                <div class="profile-button-abo">Profil</div></a>
                <div class="profile-button-abo">Tags</div></a>

            </div>

            <div class='PartieMenu'>
                <a href="../Compte/connexion.php"><div class="profile-button">Connexion</div></a>
                <a href="../Compte/inscription.php"><div class="profile-button">S'inscrire</div></a>
            </div>
    </div>

    <div id='Touites'>
        <div class="TOUITER">TOUITER</div>
        <?php

        use Touite\GestionImage;
        use Touite\GestionTouite;
        use Touite\GestionUser;
        use Touite\GestionTag;

        require_once "../Touite/GestionTouite.php";
        require_once "../Touite/GestionUser.php";
        require_once "../Touite/GestionImage.php";
        require_once "../Touite/GestionTag.php";

        GestionTouite::config();
        $listes = GestionTouite::getTouites();
        foreach ($listes as $liste) {
            $idTouite = $liste['idTouite'];
            $idUser = GestionTouite::getIdUserByTouite($idTouite);
            $user = GestionUser::getUserbyId($idUser);

            echo "<div class='touite'>";
            echo "<div class='nom'>" . $user['username'] . "</div>";
            if (strlen($liste['contentTouite']) > 100) {
                echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=sans\"><p>" . substr($liste['contentTouite'], 0, 100). "..." . "</p></a>";
            } else {
                echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=sans\"><p>" . $liste['contentTouite']. "</p></a>";
            }
            $t = GestionImage::getImageByTouite($liste['idTouite']);
            if ($t != null) {
                echo "<img src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
            }
            echo "<div class='date'>" . $liste['dateTouite'] . "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <div id="tags_influencer">
        <div id="tag">
            <div class="profile-button-abo">#Tags</div></a>
            <?php
            $tagTendance = GestionTag::getTagTendances();

//            $id = GestionUser::getIdByUsername($_SESSION['user']);

            if ($tagTendance != null) {
                foreach ($tagTendance as $tag) {
                    echo "<a href=\"touiteTag.php?tag=" . $tag['labelTag'] . "&page=connect\"><div class='affich'>#" . $tag['labelTag'] . "</div></a>";
                }
            }
            ?>
        </div>
        <div id="influencer">
            <div class="profile-button-abo ">#Influenceurs</div></a>
        </div>
    </div>
</div>
</body>
</html>






