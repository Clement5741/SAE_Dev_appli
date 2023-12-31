<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" type="text/css" href="../css/inscription.css">
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
<div class="header">
    <img src="../Images/logo.png" alt="logo" id="logo" >
    <h1>Inscription à Twouiter</h1>
</div>


<?php

use Compte\Authentification;
use Exception\AuthException;

require_once 'Authentification.php';


echo '
<div class="inscription-form">
<form action="" method="post">

    <label for="identifiant">Entrez votre Identifiant : </label>
    <input type="text" name="identifiant" id="identifiant" required>

    <label for="nom">Entrez votre Nom : </label>
    <input type="text" name="nom" id="nom" required>

    <label for="prenom">Entrez votre Prénom : </label>
    <input type="text" name="prenom" id="prenom" required>

    <label for="email">Entrez votre Adresse E-mail : </label>
    <input type="email" name="email" id="email" required>

    <label for="password">Entrez votre Mot de Passe : </label>
    <input type="password" name="password" id="password" required>

    <input type="submit" value="Créer mon compte">
</form>
</div>';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);
    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
    $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    try {
        Authentification::register($identifiant, $nom, $prenom, $email, $password);
        // On redirige vers la page de connexion
        header('Location: ../HTML/accueil.html');
    } catch (AuthException $e) {
        return $e->getMessage();
    }
}
?>

</body>
</html>
