<?php

require_once '../BD/ConnectionFactory.php';
require_once '../Authentification.php';

ConnectionFactory::setConfig('db.config.ini');
$connexion = ConnectionFactory::makeConnection();


echo '<form action="" method="post">

    <label for="identifiant">Entrez votre identifiant : </label><br>
    <input type="text" name="identifiant" id="identifiant" required><br><br>

    <label for="nom">Entrez votre nom : </label><br>
    <input type="text" name="nom" id="nom" required><br><br>

    <label for="prenom">Entrez votre prenom : </label><br>
    <input type="text" name="prenom" id="prenom" required><br><br>

    <label for="email">Entrez votre adresse email : </label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Entrez votre mot de passe : </label><br>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" value="Créer mon compte">
</form>';

$identifiant = $nom = $prenom = $email = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = filter_var($_POST['identifiant'], FILTER_SANITIZE_STRING);
    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
    $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

//    $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

    // On récupère l'identifiant max dans la table Users
//    $max = "select max(idUser) from Users;";
//    $resultset = $connexion->prepare($max);
//    $resultset->execute();
//    $row = $resultset->fetch(PDO::FETCH_ASSOC);
//    $idUser = $row['max(idUser)'] + 1;
//
//    $sql = "insert into Users (idUser,username,name,firstname,email,password_hash)
//                    values ('$idUser','$identifiant','$nom','$prenom','$email','$hash');";
//
//    $connexion->query($sql);

    Authentification::register($identifiant, $nom, $prenom, $email,$password);
}




