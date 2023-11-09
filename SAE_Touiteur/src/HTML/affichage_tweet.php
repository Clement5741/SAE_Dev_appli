<?php
session_start();

use Touite\GestionTouite;
use Touite\GestionUser;
use Touite\GestionImage;

require_once '../Touite/GestionTouite.php';
require_once '../Touite/GestionUser.php';
require_once '../Touite/GestionImage.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/affichage_tweet.css">
</head>
<body>
<?php

$liste = GestionTouite::getTouite($_GET['touite']);
$score = GestionTouite::getScoreMoyenTouite($_GET['touite']);

echo "<div class=\"tweet-container\">";

    if (isset($_SESSION['user']) && !isset($_GET['page'])){
        $t = "page_base_CONNECTER.php";
    }elseif (!isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "sans"){
        $t = "page_base_sans_connection.php";
    }else{
        $t = "profil.php?username=" . $_SESSION['user'];
    }
    echo "<a href=\"$t\" class=\"back-button\">&#8592;</a> <!---&#8592 represent the arrow-->
    <div class=\"tweet-author\"> " . $liste['name'] . " </div>";

echo "<div class=\"tweet-text\">" . $liste['contentTouite'] . "</div>";


$t = GestionImage::getImageByTouite($liste['idTouite']);
if ($t != null) {
    echo "<img class=\"tweet-image\" src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
}

echo "<div class=\"tweet-date\">" . $liste['dateTouite'] . "</div>";
echo "<div class=\"tweet-text\">Score moyen : " . $score . "</div></div>";

?>
</body>
</html>



