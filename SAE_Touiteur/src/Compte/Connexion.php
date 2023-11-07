<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
  <main>
    <?php

    require_once '../BD/ConnectionFactory.php';
    require_once '../Authentification.php';

    echo "<h1>Connexion</h1>";

    ConnectionFactory::setConfig('db.config.ini');
    $connexion = ConnectionFactory::makeConnection();

    echo "<h2>Entrez vos identifiants :</h2>";

    echo '<form action="" method="post">

    <label for="identifiant">identifiant : </label>
    <input type="text" name="identifiant" id="identifiant" required><br><br>

    <label for="password">Mot de passe : </label>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" value="Connexion">
    </form>';

    $identifiant = $password = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $identifiant = filter_var($_POST['identifiant'],FILTER_SANITIZE_STRING);
      $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);

      Authentification::authenticate($identifiant,$password);
    }

    ?>
  </main>
</body>
</html>
