<!DOCTYPE html>
<html>
<head>
    <title>connexion</title>
</head>
<body>
  <main>
    <?php

    echo "<h1>Connexion</h1>";

//    $connexion = new PDO('mysql:host=localhost;dbname=[nom_de_la_bd]','root','');

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
    }

//    $sql = " ";

//     $resultset = $connexion->prepare($sql);
//     $resultset->bindparam(1, ...);
//     $resultset->bindparam(2, ...);
//     $resultset->execute();

    ?>
  </main>
</body>
</html>
