<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/connection.css">
    <title>Connexion</title>
</head>
<body>
<div class="header">
    <img src="logo_twouiter.png" alt="Logo Twouiter">
    <h1>Connexion à Twouiter</h1>
</div>

<?php

use Compte\Authentification;
use Exception\AuthException;

require_once 'Authentification.php';

echo '
<div class="connexion-form">
<form action="" method="post">
    <label for="identifiant">Identifiant ou E-mail : </label>
    <input type="text" name="identifiant" id="identifiant" required>

    <label for="password">Mot de passe : </label>
    <input type="password" name="password" id="password" required>
        
    <input type="submit" value="Se Connecter">
    
    <p><a href="recuperer_mot_de_passe.html">J\'ai oublié mon mot de passe</a></p>
</form>
</div>';


$identifiant = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    try {
        Authentification::authenticate($identifiant, $password);
        if (isset($_SESSION['user'])) {
            header('Location: ../HTML/page_base_CONNECTER.php');
        }
    } catch (PDOException|AuthException $e) {
        echo $e->getMessage();
    }
}

if (isset($_SESSION['user'])) {
    // echo "<br>Les cookies de session sont activés.";
} else {
    // echo "<br>Les cookies de session ne sont pas activés.";
}


?>

</body>
</html>
