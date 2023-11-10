<?php
session_start();

use Touite\GestionTag;
use Touite\GestionTouite;
use Touite\GestionUser;
use Touite\GestionImage;

require_once '../Touite/GestionTouite.php';
require_once '../Touite/GestionUser.php';
require_once '../Touite/GestionImage.php';
require_once '../Touite/GestionTag.php';
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

echo "<h1>Voici la page du Tag : #" . $_GET['tag'] . "</h1>";

//Retourner à la page d'avant
if (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "connect") {
    $t = "page_base_CONNECTER.php";
} elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "affichage") {
    $t = "affichage_tweet.php?username=" . $_SESSION['user'] . "&touite=" . $_GET['touite'];
} elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "tag") {
    $t = "touiteTag.php?tag=" . $_GET['tag'];
} elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "vostags") {
    $t = "affichage_tags.php?username=" . $_SESSION['user'];
} elseif (isset($_SESSION['user']) && isset($_GET['page']) && $_GET['page'] == "enstag") {
    $t = "page_ensemble_tags.php";
 }else {
    $t = "page_base_sans_connection.php";
}

echo "<a href=\"$t\" class=\"back-button\">&#8592;</a> <!---&#8592 represent the arrow-->";







// Abonnement au tag
if (isset($_SESSION['user'])) {
    echo "<form id='form_abo' method='post' action=''>";
    $idTag = GestionTag::getidTagByLabel($_GET['tag']);
    $idUser = GestionUser::getIdByUsername($_SESSION['user']);
    $isUserLoggedIn = isset($_SESSION['user']);
    $isUserSubscribed = ($isUserLoggedIn && GestionTag::isFollowedTag($idUser, $idTag));
    $isUserNotSubscribed = ($isUserLoggedIn && !GestionTag::isFollowedTag($idUser, $idTag));

    $aboButtonClass = $isUserNotSubscribed ? 'abo-button' : 'fake_abo-button disabled';
    $desaboButtonClass = $isUserSubscribed ? 'abo-button' : 'fake_abo-button disabled';

    echo "<button class=$aboButtonClass type='submit' name='abo'>S'abonner</button>
      <button class=$desaboButtonClass type='submit' name='desabo'>Se désabonner</button>";
    echo "</form>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['abo'])) {
        GestionTag::followTag($idUser, $idTag);
        // On recharge la page pour que le bouton s'abonner devienne se désabonner
        header('Location: touiteTag.php?tag=' . $_GET['tag'] . '&page=' . $_GET['page'] . '&touite=' . $_GET['touite']);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desabo'])) {
        GestionTag::unfollowTag($idUser, $idTag);
        // On recharge la page pour que le bouton se désabonner devienne s'abonner
        header('Location: touiteTag.php?tag=' . $_GET['tag'] . '&page=' . $_GET['page'] . '&touite=' . $_GET['touite']);
    }
}


echo "<h2>Voici les touites avec le tag : #" . $_GET['tag'] . "</h2>";
foreach ($listes as $liste) {
    echo "<div id='touite-info'>";

    $idTouite = $liste['idTouite'];
    $idUser = GestionTouite::getIdUserByTouite($idTouite);
    $user = GestionUser::getUserbyId($idUser);

    echo "<div class='nom'>";
    echo "<p>" . $user['username'] . "</p>";
    echo "</div>";
    echo "<div class='tag'>";
    if (strlen($liste['contentTouite']) > 100) {
       echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=tag&tag=" . $_GET['tag'] . "\"><p>" . substr($liste['contentTouite'], 0, 100) . "..." . "</p></a>";
    } else {
        echo "<a href=\"affichage_tweet.php?touite=" . $liste['idTouite'] . "&page=tag&tag=" . $_GET['tag'] . "\"><p>" . $liste['contentTouite'] . "</p></a>";
    }
    echo "</div>";
    echo "<div class='date'>";
    echo "<p>" . $liste['dateTouite'] . "</p>";
    echo "</div>";
    echo "</div>";
}


?>
</body>
</html>