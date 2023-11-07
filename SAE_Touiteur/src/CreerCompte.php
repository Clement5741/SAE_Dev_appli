<!DOCTYPE html>
<html>
<head>
    <title>Créer un compte</title>
</head>
<body>
    <main>
        <?php

        include "ConnectionFactory.php";
        include 'Authentification.php';

        echo "<h1>Créer un compte</h1>";

        $connexion = ConnectionFactory::makeConnection();

        echo "<h2>Renseignez quelques informations :</h2>";

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
            $identifiant = filter_var($_POST['identifiant'],FILTER_SANITIZE_STRING);
            $nom = filter_var($_POST['nom'],FILTER_SANITIZE_STRING);
            $prenom = filter_var($_POST['prenom'],FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
        }

        Authentification::register($identifiant, $nom, $prenom, $email,$password);

        ?>
    </main>
</body>
</html>



