<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}

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
$score = GestionTouite::getScoreMoyenTouite(GestionUser::getIdByUsername($_SESSION['user']));

echo "<div class=\"tweet-container\">

    <a href=\"page_base_CONNECTER.php\" class=\"back-button\">&#8592;</a> <!---&#8592 represent the arrow-->
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



