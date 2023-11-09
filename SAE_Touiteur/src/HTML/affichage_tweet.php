<!DOCTYPE html>
<html>
<head>
    <title>Détail du Tweet</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/affichage_tweet.css">
</head>
<body>
<?php
use Touite\GestionTouite;
use Touite\GestionUser;
use Touite\GestionImage;

require_once '../Touite/GestionTouite.php';
require_once '../Touite/GestionUser.php';
require_once '../Touite/GestionImage.php';


$listes = GestionTouite::getTouitesByUser(GestionUser::getIdByUsername($_SESSION['user']));
$score = GestionTouite::getScoreMoyenTouite(GestionUser::getIdByUsername($_SESSION['user']));
foreach ($listes as $liste) {
    echo "<div class=\"tweet-container\">

    <a href=\"page_base_CONNECTER.php\" class=\"back-button\">&#8592;</a> <!---&#8592 represent the arrow-->
    <div class=\"tweet-author\"> " . $liste['nameuser'] . " </div>";

    echo "<div class=\"tweet-author\"> " . $liste['iduser'] . " </div>";

    echo "<div class=\"tweet-text\">" . $liste['contentTouite'] . "</div>";


    $t = GestionImage::getImageByTouite($liste['idTouite']);
    if ($t != null) {
        echo "<img class=\"tweet-image\" src='" . $t['cheminImage'] . "' alt='image touite' width='200' height='200'>";
    }

    echo "<div class=\"tweet-date\">" . $liste['dateTouite'] . "</div>";
    echo "<div class=\"tweet-text\">" . $score['notePerti'] . "</div>";
}
?>
</body>
</html>



