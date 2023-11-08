<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: accueil.html');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
    <title>Créer un Touite</title>
    <link rel="stylesheet" href="../css/creationTouite.css">
</head>
<body>
    <h1>Créer votre Touite : </h1>
    <main>
        <?php

        use Touite\GestionTouite;
        require_once '../Touite/GestionTouite.php';

        echo '
             <form action="" method="post">
                <label for="contenu">Contenu : </label><br>
                <textarea name = "contenu" rows = "10" clos="40"></textarea><br><br>
                <label for="image">Image : </label><br>
                <input type="file" name="image" id="image"><br><br>

                <input type="submit" value="Poster">

             </form>';

           $contenu = '';

           if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $contenu = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);
                GestionTouite::setTouite($contenu, $_SESSION['user']);
//                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
//                    // On récupère le chemin temporaire de l'image
//                    $tmpName = $_FILES['image']['tmp_name'];
//                    // On récupère le nom de l'image
//
//                }
                header('Location: ../HTML/page_base_CONNECTER.php');
           }
        ?>
    </main>
</body>
</html>
