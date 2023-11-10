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
    <link rel="stylesheet" href="src/classes/css/creationTouite.css">
</head>
<body>
<h1>Créer votre Touite : </h1>
<main>
    <?php

    use Touite\GestionImage;
    use Touite\GestionTouite;

    require_once '../Touite/GestionTouite.php';
    require_once '../Touite/GestionImage.php';

    echo '
             <form action="" method="post" enctype="multipart/form-data">

                <label for="contenu">Contenu : </label><br>
                <textarea name = "contenu" rows = "10" clos="40"></textarea><br><br>
                <label for="image">Image : </label><br>
                <input type="file" name="image" id="image"><br><br>

                <input type="submit" value="Poster">

             </form>';

    $contenu = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $contenu = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);
        $idTouite = GestionTouite::setTouite($contenu, $_SESSION['user']);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            GestionImage::uploadImage($_FILES['image'], $idTouite);
        }
        header('Location: ../HTML/page_base_CONNECTER.php');
    }
    ?>
</main>
</body>
</html>
