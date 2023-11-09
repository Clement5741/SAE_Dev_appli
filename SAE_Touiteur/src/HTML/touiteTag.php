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
    <title>Affichage Touite d'un Tag</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/touiteTag.css">
</head>
<body>
<?php

$listes = GestionTouite::getTouitesByTag($_GET['tag']);
echo "<h1>Voici la page du Tag : " . $_GET['tag'] . "</h1>";

//Retourner à la page d'avant
if (isset($_SESSION['user']) && !isset($_GET['page'])){
    $t = "page_base_CONNECTER.php";
}elseif (isset($_SESSION['user']) &&isset($_GET['page']) && $_GET['page'] == "affichage"){
    $t = "affichage_tweet.php?username=" . $_SESSION['user'];
}elseif(isset($_SESSION['user']) &&isset($_GET['page']) && $_GET['page'] == "tag") {
    $t = "touiteTag.php?tag=" . $_GET['tag'];
} elseif (isset($_SESSION['user']) &&isset($_GET['page']) && $_GET['page'] == "vostags"){
    $t = "affichage_tags.php";
} else {
    $t = "page_base_sans_connection.php";
}

echo "<a href=\"$t\" class=\"back-button\">&#8592;</a> <!---&#8592 represent the arrow-->";

foreach ($listes as $liste) {
    $idTouite = $liste['idTouite'];
    $idUser = GestionTouite::getIdUserByTouite($idTouite);
    $user = GestionUser::getUserbyId($idUser);

    echo "<div class='touite'>";
    echo "<p>" . $user['username'] . "</p>";
    if (strlen($liste['contentTouite']) > 100) {
        echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=tag&tag=" . $_GET['tag'] . "\"><p>" . substr($liste['contentTouite'], 0, 100). "..." . "</p></a>";
    } else {
        echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=tag&tag=" . $_GET['tag'] . "\"><p>" . $liste['contentTouite']. "</p></a>";
    }
    echo "<p>" . $liste['dateTouite'] . "</p>";
    echo "</div>";
}

?>
</body>
</html>